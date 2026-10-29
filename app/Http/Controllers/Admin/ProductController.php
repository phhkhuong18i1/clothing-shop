<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColorVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', 'in:published,draft']]);
        $products = Product::with(['category', 'images'])
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($query) => $query->where('name', 'like', '%'.$term.'%')->orWhere('sku', 'like', '%'.$term.'%')))
            ->when(($filters['status'] ?? null) === 'published', fn ($q) => $q->where('is_published', true))
            ->when(($filters['status'] ?? null) === 'draft', fn ($q) => $q->where('is_published', false))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create() { return view('admin.products.form', ['product' => new Product(), 'categories' => Category::with('parent')->orderByRaw('COALESCE(parent_id, id)')->orderByRaw('parent_id is not null')->orderBy('name')->get()]); }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $files = $request->file('images', []);
        $variants = $data['color_variants'] ?? [];
        unset($data['color_variants']);
        $variantFiles = $request->file('color_variants', []);
        $paths = $colorPaths = $removedColorPaths = [];
        $primaryChoice = $request->input('primary_choice');
        try {
            DB::transaction(function () use ($data, $files, $variants, $variantFiles, $primaryChoice, &$paths, &$colorPaths, &$removedColorPaths): void {
                $product = Product::create($this->attributes($data, $this->uniqueSlug($data['name'])));
                foreach ($files as $index => $file) {
                    $path = $file->store('products', 'public');
                    if (! $path) throw new \RuntimeException('The image could not be stored.');
                    $paths[] = $path;
                    $product->images()->create(['image_path' => $path, 'alt_text' => $product->name, 'sort_order' => $index, 'is_primary' => $primaryChoice === "new:$index" || (! $primaryChoice && $index === 0)]);
                }
                if ($files && ! $product->images()->where('is_primary', true)->exists()) $product->images()->oldest('sort_order')->first()?->update(['is_primary' => true]);
                $this->syncColorVariants($product, $variants, $variantFiles, $colorPaths, $removedColorPaths);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete([...$paths, ...$colorPaths]);
            throw $exception;
        }
        return redirect()->route('admin.products.index')->with('status', 'Đã tạo sản phẩm.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'colorVariants']);
        return view('admin.products.form', ['product' => $product, 'categories' => Category::with('parent')->orderByRaw('COALESCE(parent_id, id)')->orderByRaw('parent_id is not null')->orderBy('name')->get()]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product);
        $files = $request->file('images', []);
        $variants = $data['color_variants'] ?? [];
        unset($data['color_variants']);
        $variantFiles = $request->file('color_variants', []);
        $paths = $removedPaths = $colorPaths = $removedColorPaths = [];
        $removeIds = collect($request->input('remove_images', []))->map(fn ($id) => (int) $id)->all();
        $primaryChoice = $request->input('primary_choice');
        $primaryExisting = str_starts_with((string) $primaryChoice, 'existing:') ? (int) substr($primaryChoice, 9) : null;
        if ($primaryExisting && ! $product->images()->whereKey($primaryExisting)->exists()) abort(422);

        try {
            $newImageIds = [];
            DB::transaction(function () use ($product, $data, $files, $variants, $variantFiles, &$paths, &$newImageIds, &$removedPaths, &$colorPaths, &$removedColorPaths, $removeIds, $primaryExisting, $primaryChoice): void {
                $product->update($this->attributes($data, $product->slug));
                if ($removeIds) {
                    $images = $product->images()->whereIn('id', $removeIds)->get();
                    $removedPaths = $images->pluck('image_path')->all();
                    $images->each->delete();
                }
                foreach ($files as $index => $file) {
                    $path = $file->store('products', 'public');
                    if (! $path) throw new \RuntimeException('The image could not be stored.');
                    $paths[] = $path;
                    $newImage = $product->images()->create(['image_path' => $path, 'alt_text' => $product->name, 'sort_order' => (int) $product->images()->max('sort_order') + 1, 'is_primary' => false]);
                    $newImageIds[] = $newImage->id;
                }
                if ($primaryExisting && ! in_array((int) $primaryExisting, $removeIds, true)) {
                    $product->images()->update(['is_primary' => false]);
                    $product->images()->whereKey($primaryExisting)->update(['is_primary' => true]);
                } elseif (str_starts_with((string) $primaryChoice, 'new:')) {
                    $newIndex = (int) substr((string) $primaryChoice, 4);
                    if (isset($newImageIds[$newIndex])) {
                        $product->images()->update(['is_primary' => false]);
                        $product->images()->whereKey($newImageIds[$newIndex])->update(['is_primary' => true]);
                    }
                } elseif (! $product->images()->where('is_primary', true)->exists()) {
                    $product->images()->oldest('sort_order')->first()?->update(['is_primary' => true]);
                }
                $this->syncColorVariants($product, $variants, $variantFiles, $colorPaths, $removedColorPaths);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete([...$paths, ...$colorPaths]);
            throw $exception;
        }
        Storage::disk('public')->delete($this->storedImagePaths([...$removedPaths, ...$removedColorPaths]));
        return redirect()->route('admin.products.index')->with('status', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        $paths = [...$product->images()->pluck('image_path')->all(), ...$product->colorVariants()->pluck('image_path')->all()];
        DB::transaction(fn () => $product->delete());
        Storage::disk('public')->delete($this->storedImagePaths($paths));
        return redirect()->route('admin.products.index')->with('status', 'Đã xóa sản phẩm.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        abort_unless((int) $image->product_id === (int) $product->getKey(), 404);
        $path = $image->image_path; $wasPrimary = $image->is_primary;
        DB::transaction(function () use ($image, $product, $wasPrimary): void {
            $image->delete();
            if ($wasPrimary) $product->images()->oldest('sort_order')->first()?->update(['is_primary' => true]);
        });
        Storage::disk('public')->delete($this->storedImagePaths([$path]));
        return back()->with('status', 'Image removed.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'], 'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sku' => ['nullable', 'string', 'max:80', 'unique:products,sku'.($product ? ','.$product->id : '')],
            'description' => ['nullable', 'string', 'max:10000'], 'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'], 'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'sizes' => ['nullable', 'string', 'max:200'],
            'is_published' => ['nullable', 'boolean'], 'is_featured' => ['nullable', 'boolean'], 'is_best_seller' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:6'], 'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'color_variants' => ['nullable', 'array', 'max:12'],
            'color_variants.*.id' => ['nullable', 'integer', 'distinct'],
            'color_variants.*.name' => ['required', 'string', 'max:80'],
            'color_variants.*.color_hex' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'color_variants.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'color_variants.*.show_on_home' => ['nullable', 'boolean'],
            'primary_choice' => ['nullable', 'regex:/^(existing:\\d+|new:\\d+)$/'],
            'remove_images' => ['nullable', 'array'], 'remove_images.*' => ['integer'],
        ]);

        foreach ($data['color_variants'] ?? [] as $key => $variant) {
            $id = $variant['id'] ?? null;
            $file = $request->file("color_variants.$key.image");
            if (! $id && ! $file) {
                throw ValidationException::withMessages(["color_variants.$key.image" => 'Hãy tải ảnh cho màu mới.']);
            }
            if ($id) {
                abort_unless($product, 422);
                $existingVariant = $product->colorVariants()->whereKey($id)->first();
                abort_unless($existingVariant, 422);
                if (! $existingVariant->image_path && ! $file) {
                    throw ValidationException::withMessages(["color_variants.$key.image" => 'Hãy tải ảnh cho màu này.']);
                }
            }
        }

        return $data;
    }

    private function attributes(array $data, string $slug): array
    {
        return [
            'category_id' => $data['category_id'], 'name' => $data['name'], 'slug' => $slug,
            'sku' => $data['sku'] ?: null, 'description' => $data['description'] ?? null,
            'price' => $data['price'], 'sale_price' => $data['sale_price'] ?: null, 'stock_quantity' => $data['stock_quantity'],
            'sizes' => $this->tokens($data['sizes'] ?? ''), 'colors' => null,
            'is_published' => (bool) ($data['is_published'] ?? false), 'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_best_seller' => (bool) ($data['is_best_seller'] ?? false),
        ];
    }

    private function tokens(string $value): array { return collect(explode(',', $value))->map(fn ($item) => trim($item))->filter()->unique()->take(20)->values()->all(); }
    private function syncColorVariants(Product $product, array $variants, array $files, array &$uploadedPaths, array &$removedPaths): void
    {
        $existing = $product->colorVariants()->get()->keyBy('id');
        $kept = [];
        $sortOrder = 0;
        foreach ($variants as $key => $row) {
            $id = filled($row['id'] ?? null) ? (int) $row['id'] : null;
            $file = $files[$key]['image'] ?? null;
            $path = $file?->store('product-color-variants', 'public');
            if ($file && ! $path) throw new \RuntimeException('Không thể lưu ảnh màu.');
            if ($path) $uploadedPaths[] = $path;

            if ($id) {
                $variant = $existing->get($id);
                abort_unless($variant, 422);
                if ($path && $variant->image_path) $removedPaths[] = $variant->image_path;
                $variant->update(['name' => $row['name'], 'color_hex' => $row['color_hex'], 'image_path' => $path ?: $variant->image_path, 'show_on_home' => (bool) ($row['show_on_home'] ?? false), 'sort_order' => $sortOrder++]);
            } else {
                $variant = $product->colorVariants()->create(['name' => $row['name'], 'color_hex' => $row['color_hex'], 'image_path' => $path, 'show_on_home' => (bool) ($row['show_on_home'] ?? false), 'sort_order' => $sortOrder++]);
            }
            $kept[] = $variant->id;
        }

        foreach ($existing->reject(fn (ProductColorVariant $variant) => in_array($variant->id, $kept, true)) as $variant) {
            if ($variant->image_path) $removedPaths[] = $variant->image_path;
            $variant->delete();
        }
    }

    private function storedImagePaths(array $paths): array
    {
        return collect($paths)->reject(fn ($path) => Str::startsWith((string) $path, ['http://', 'https://']))->values()->all();
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product'; $slug = $base; $i = 2;
        while (Product::where('slug', $slug)->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FashionCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $clothing = $this->category('clothing', 'Trang phục', 'Phom dáng dễ mặc cho nhịp sống mỗi ngày.');
        $bottoms = $this->category('bottoms', 'Quần & váy', 'Những phom dáng thoải mái để phối cùng tủ đồ.', $clothing->id);
        $accessories = $this->category('accessories', 'Phụ kiện', 'Điểm chạm cuối cùng cho bộ trang phục.');
        $knitwear = $this->category('knitwear', 'Đồ dệt kim', 'Lớp áo mềm mại cho những ngày dịu mát.');
        $tops = $this->category('tops', 'Áo', 'Áo sơ mi, áo kiểu và những lớp mặc hằng ngày.', $clothing->id);

        $photo = fn (string $id, int $width = 1000): string => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w={$width}&q=85";

        $groupDefinitions = [
            'tops' => ['category' => $tops, 'names' => [
                'Áo sơ mi Linen Gió', 'Áo thun Cotton Dịu', 'Áo kiểu Nếp Gấp', 'Sơ mi Kẻ Thanh', 'Áo blouse Nắng Mai',
                'Áo cổ vuông An Nhiên', 'Áo polo Thường Ngày', 'Áo hai dây Mộc', 'Áo tunic Gió Nhẹ', 'Sơ mi Dáng Rộng',
            ], 'description' => 'Thiết kế dễ mặc với chất liệu nhẹ và phom dáng thoải mái.', 'price' => [390000, 990000], 'photos' => ['photo-1598554747436-c9293d6a588f', 'photo-1598033129183-c4f50c736f10', 'photo-1603252109303-2751441dd157']],
            'bottoms' => ['category' => $bottoms, 'names' => [
                'Quần suông An Nhiên', 'Quần suông Chớm Thu', 'Quần Linen Mộc', 'Quần jeans Thảnh Thơi', 'Chân váy midi Nắng',
                'Chân váy chữ A Mây', 'Quần short Đi Biển', 'Quần cạp cao Dáng Xinh', 'Chân váy xếp ly Nhịp', 'Quần ống đứng Tối Giản',
            ], 'description' => 'Phom quần và chân váy linh hoạt, dễ phối cho nhiều dịp.', 'price' => [490000, 1490000], 'photos' => ['photo-1490114538077-0a7f8cb49891', 'photo-1473966968600-fa801b869a1a', 'photo-1509631179647-0177331693ae']],
            'dresses' => ['category' => Category::firstOrCreate(['slug' => 'vay-dam'], ['name' => 'Váy & đầm', 'description' => 'Thiết kế nữ tính cho ngày thường và những dịp đặc biệt.', 'parent_id' => $bottoms->id]), 'names' => [
                'Đầm suông Chiều Hạ', 'Váy midi Hoa Nắng', 'Đầm Linen Dịu Êm', 'Váy quấn Thong Dong', 'Đầm cổ vuông Mơ',
                'Váy xếp ly Ngày Xanh', 'Đầm tay phồng Ban Mai', 'Váy hai dây Thảnh Thơi', 'Đầm dài Gió Mát', 'Váy tầng Chạm Nắng',
            ], 'description' => 'Đường cắt mềm mại và chất liệu thoáng nhẹ để mặc cả ngày.', 'price' => [790000, 1890000], 'photos' => ['photo-1515886657613-9f3515b0c78f', 'photo-1539109136881-3be0616acf4b', 'photo-1483985988355-763728e1935b']],
            'knitwear' => ['category' => $knitwear, 'names' => [
                'Áo len Form Dịu', 'Cardigan Màu Yến Mạch', 'Áo len cổ tròn Dịu Dàng', 'Áo dệt kim Gió Thu', 'Cardigan Cúc Gỗ',
                'Áo len mỏng Sớm Mai', 'Áo cổ lọ Thảnh Thơi', 'Gile len Mộc Nhiên', 'Áo dệt kim Vân Nổi', 'Cardigan Dáng Rộng',
            ], 'description' => 'Lớp dệt kim mềm mại, giữ ấm vừa đủ và dễ phối quanh năm.', 'price' => [690000, 1690000], 'photos' => ['photo-1576566588028-4147f3842f27', 'photo-1618354691373-d851c5c3a990', 'photo-1620799140408-edc6dcb6d633']],
            'outerwear' => ['category' => Category::firstOrCreate(['slug' => 'ao-khoac'], ['name' => 'Áo khoác', 'description' => 'Lớp khoác nhẹ cho những ngày thời tiết chuyển mùa.', 'parent_id' => $clothing->id]), 'names' => [
                'Áo khoác Chạm Gió', 'Blazer Dáng Thư Thái', 'Áo khoác Linen Mộc', 'Jacket Dạo Phố', 'Áo khoác Dạ Mềm',
                'Blazer Màu Đá', 'Áo khoác Gió Nhẹ', 'Cardigan Khoác Ngoài', 'Áo khoác Cổ Ve', 'Jacket Tối Giản',
            ], 'description' => 'Lớp khoác nhẹ với bảng màu trầm ấm, hoàn thiện bộ trang phục.', 'price' => [990000, 2490000], 'photos' => ['photo-1548126032-079a0fb0099d', 'photo-1529139574466-a303027c1d8b', 'photo-1544022613-e87ca75a784a']],
            'shoes' => ['category' => Category::firstOrCreate(['slug' => 'giay-dep'], ['name' => 'Giày dép', 'description' => 'Những thiết kế thoải mái để đồng hành mỗi ngày.', 'parent_id' => $accessories->id]), 'names' => [
                'Giày bệt Bước Nhẹ', 'Sneaker Ngày Xanh', 'Dép quai ngang Mộc', 'Giày Mary Jane Dịu Êm', 'Sandal Nắng Hạ',
                'Giày loafer Tối Giản', 'Sandal Quai Mảnh', 'Sneaker Dạo Phố', 'Dép da Thảnh Thơi', 'Giày búp bê An Nhiên',
            ], 'description' => 'Thiết kế êm chân, dễ kết hợp cùng trang phục hằng ngày.', 'price' => [490000, 1790000], 'photos' => ['photo-1543163521-1bf539c55dd2', 'photo-1542291026-7eec264c27ff', 'photo-1549298916-b41d501d3772']],
            'bags' => ['category' => Category::firstOrCreate(['slug' => 'tui-xach'], ['name' => 'Túi xách', 'description' => 'Phụ kiện gọn gàng cho những vật dụng cần thiết.', 'parent_id' => $accessories->id]), 'names' => [
                'Túi đeo vai Mộc', 'Túi tote Đi Chợ', 'Túi mini Ngày Nắng', 'Túi đeo chéo Thong Dong', 'Túi cói Cuối Tuần',
                'Túi hộp Tối Giản', 'Túi kẹp nách Mây', 'Túi da Màu Đất', 'Túi bucket Nhẹ Nhàng', 'Túi vải Dạo Phố',
            ], 'description' => 'Chiếc túi tiện dụng với phom dáng gọn gàng và dễ phối đồ.', 'price' => [390000, 1590000], 'photos' => ['photo-1590874103328-eac38a683ce7', 'photo-1544816155-12df9643f363', 'photo-1553062407-98eeb64c6a62']],
            'jewelry' => ['category' => Category::firstOrCreate(['slug' => 'trang-suc'], ['name' => 'Trang sức', 'description' => 'Điểm nhấn tinh tế cho phong cách riêng.', 'parent_id' => $accessories->id]), 'names' => [
                'Khuyên tai Giọt Nắng', 'Vòng cổ Mảnh Mai', 'Nhẫn Bạc An Yên', 'Khuyên tai Nụ Hoa', 'Vòng tay Dáng Tròn',
                'Dây chuyền Mặt Đá', 'Bông tai Ngọc Nhỏ', 'Nhẫn Bản Mảnh', 'Vòng cổ Chạm Khắc', 'Khuyên tai Hình Học',
            ], 'description' => 'Món trang sức nhỏ với sắc độ trang nhã, dễ kết hợp mỗi ngày.', 'price' => [190000, 890000], 'photos' => ['photo-1611652022419-a9419f74343d', 'photo-1617038220319-276d3cfab638', 'photo-1611591437281-460bfbe1220a']],
            'eyewear' => ['category' => Category::firstOrCreate(['slug' => 'kinh-mat'], ['name' => 'Kính mát', 'description' => 'Phụ kiện hoàn thiện diện mạo ngày nắng.', 'parent_id' => $accessories->id]), 'names' => [
                'Kính gọng Màu Hổ Phách', 'Kính mát Dáng Mèo', 'Kính tròn Ngày Nắng', 'Kính gọng mảnh Tối Giản', 'Kính mắt Chớm Hè',
                'Kính vuông Dạo Phố', 'Kính gọng nâu Mộc', 'Kính oval Nhẹ Nhàng', 'Kính mát Dáng Cổ Điển', 'Kính mắt Màu Khói',
            ], 'description' => 'Gọng kính nhẹ với kiểu dáng vượt thời gian cho ngày nắng.', 'price' => [290000, 990000], 'photos' => ['photo-1511499767150-a48a237f0083', 'photo-1508296695146-257a814070b4', 'photo-1517841905240-472988babdf9']],
            'watches' => ['category' => Category::firstOrCreate(['slug' => 'dong-ho'], ['name' => 'Đồng hồ', 'description' => 'Thiết kế chỉ giờ tinh giản, đồng hành mỗi ngày.', 'parent_id' => $accessories->id]), 'names' => [
                'Đồng hồ dây da Màu Đất', 'Đồng hồ mặt nhỏ Mây', 'Đồng hồ dây kim loại Thanh', 'Đồng hồ mặt vuông Mộc', 'Đồng hồ dây vải Cuối Tuần',
                'Đồng hồ cổ điển An Nhiên', 'Đồng hồ mặt tròn Dịu Êm', 'Đồng hồ dây nâu Tối Giản', 'Đồng hồ thanh mảnh Ngày Xanh', 'Đồng hồ Mặt Trời',
            ], 'description' => 'Thiết kế xem giờ gọn gàng với dây đeo thoải mái và bền đẹp.', 'price' => [690000, 2490000], 'photos' => ['photo-1524805444758-089113d48a6d', 'photo-1523170335258-f5ed11844a49', 'photo-1434056886845-dac89ffe9b56']],
        ];

        foreach ($groupDefinitions as $definition) {
            $definition['category']->image_path ??= $photo($definition['photos'][0], 900);
            $definition['category']->save();
        }

        $samplePath = database_path('seeders/data/fashion-sample.json');
        $samples = json_decode(file_get_contents($samplePath), true, flags: JSON_THROW_ON_ERROR);
        $sampleGroups = [
            'tops' => ['mens-shirts', 'tops'],
            'dresses' => ['womens-dresses'],
            'shoes' => ['mens-shoes', 'womens-shoes'],
            'bags' => ['womens-bags'],
            'jewelry' => ['womens-jewellery'],
            'eyewear' => ['sunglasses'],
            'watches' => ['mens-watches', 'womens-watches'],
        ];
        $imagesByGroup = [];
        foreach ($samples as $sample) {
            foreach ($sampleGroups as $group => $sourceCategories) {
                if (in_array($sample['category'], $sourceCategories, true)) {
                    $imagesByGroup[$group][] = $sample['images'];
                }
            }
        }

        $index = 0;
        foreach ($groupDefinitions as $group => $definition) {
            foreach ($definition['names'] as $itemIndex => $name) {
                $index++;
                $imageSets = $imagesByGroup[$group] ?? [];
                $imagePaths = $imageSets
                    ? $imageSets[$itemIndex % count($imageSets)]
                    : [];

                foreach ($definition['photos'] as $photoId) {
                    $imagePaths[] = $photo($photoId);
                }
                $imagePaths = array_slice(array_values(array_unique($imagePaths)), 0, 3);

                $minimum = $definition['price'][0];
                $maximum = $definition['price'][1];
                $price = (int) (round(($minimum + (($itemIndex * 73000) % ($maximum - $minimum))) / 10000) * 10000);
                $onSale = $index % 7 === 0;
                $product = Product::updateOrCreate(['sku' => 'DMO-'.str_pad((string) $index, 4, '0', STR_PAD_LEFT)], [
                    'category_id' => $definition['category']->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $definition['description'],
                    'price' => $price,
                    'sale_price' => $onSale ? (int) (round(($price * 0.85) / 10000) * 10000) : null,
                    'stock_quantity' => 4 + (($index * 7) % 24),
                    'sizes' => in_array($group, ['shoes'], true) ? ['36', '37', '38', '39', '40'] : (in_array($group, ['bags', 'jewelry', 'eyewear', 'watches'], true) ? ['Một cỡ'] : ['S', 'M', 'L', 'XL']),
                    'colors' => null,
                    'is_published' => true,
                    'is_featured' => $index % 11 === 0,
                    'is_best_seller' => $index % 5 === 0,
                ]);

                foreach ($imagePaths as $sortOrder => $imagePath) {
                    $product->images()->updateOrCreate(['sort_order' => $sortOrder], [
                        'image_path' => $imagePath,
                        'alt_text' => $product->name.' — ảnh '.($sortOrder + 1),
                        'is_primary' => $sortOrder === 0,
                    ]);
                }
            }
        }
    }

    private function category(string $slug, string $name, string $description, ?int $parentId = null): Category
    {
        $category = Category::query()->where('name', $name)->orderBy('id')->first()
            ?? Category::where('slug', $slug)->first();

        if (! $category) {
            $category = new Category(['slug' => $slug]);
        }

        $category->name = $name;
        $category->description = $description;
        $category->parent_id = $parentId;
        $category->save();

        return $category;
    }
}

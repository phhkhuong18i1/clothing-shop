@extends('layouts.store')
@section('title', $product->name)
@section('content')
@php($representativeImage = $product->images->firstWhere('is_primary', true) ?: $product->images->first())
<section class="container-page py-8 sm:py-14">
    <nav class="mb-7 text-[10px] uppercase tracking-[.16em] text-stone-500" aria-label="Đường dẫn"><a href="{{ route('home') }}">Trang chủ</a><span class="px-2">/</span><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a><span class="px-2">/</span><span class="text-ink">{{ $product->name }}</span></nav>
    <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(320px,.78fr)] lg:gap-14" data-product-visual>
        <div class="mx-auto w-full max-w-[540px] lg:mx-0" data-gallery>
            @if($product->images->isNotEmpty() || $product->colorVariants->first()?->image_url)
                <div class="overflow-hidden rounded-sm border border-stone-200 bg-[#e8e4dc]">
                    <img data-product-main data-fallback-image class="aspect-[4/5] w-full object-cover" src="{{ $representativeImage?->url ?: $product->colorVariants->first()->image_url }}" alt="{{ $representativeImage?->alt_text ?: $product->name }}">
                </div>
                @if($product->images->count() > 1)<div class="mt-3 flex gap-2.5 overflow-x-auto pb-1" aria-label="Gallery ảnh sản phẩm">@foreach($product->images as $image)<button type="button" data-gallery-thumb data-src="{{ $image->url }}" data-alt="{{ $image->alt_text ?: $product->name }}" aria-label="Xem ảnh {{ $loop->iteration }} trong {{ $product->images->count() }} ảnh" aria-pressed="{{ $representativeImage && $image->is($representativeImage) ? 'true' : 'false' }}" class="w-[4.25rem] shrink-0 cursor-pointer overflow-hidden rounded-sm border border-stone-200 bg-white p-1 transition-colors hover:border-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay aria-pressed:border-clay aria-pressed:ring-1 aria-pressed:ring-clay/30"><img data-fallback-image class="aspect-[4/5] w-full rounded-[2px] object-cover" src="{{ $image->url }}" alt="" loading="lazy"></button>@endforeach</div>@endif
            @else<div class="grid aspect-[4/5] place-items-center bg-[#e8e4dc]"><span class="font-display text-4xl text-stone-400">A·8</span></div>@endif
        </div>
        <div class="relative rounded-md border border-stone-200 bg-white p-5 sm:p-7 lg:sticky lg:top-24 lg:self-start">
            <div class="pr-12"><p class="eyebrow">{{ $product->category->name }}@if($product->sku) <span class="mx-2">·</span> Mã {{ $product->sku }}@endif</p><h1 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">{{ $product->name }}</h1></div>
            <div class="absolute right-5 top-5 z-20" x-data="{ shareOpen: false }" @keydown.escape.window="shareOpen = false">
                <button type="button" class="grid h-10 w-10 place-items-center rounded-md border border-stone-300 text-ink transition-colors hover:border-ink hover:bg-[#f8f7f4] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" aria-label="Chia sẻ sản phẩm" :aria-expanded="shareOpen" @click="shareOpen = !shareOpen">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.7 10.7 6.6-4.4m-6.6 7 6.6 4.4"/></svg>
                </button>
                <div x-cloak x-show="shareOpen" x-transition.opacity @click.outside="shareOpen = false" class="absolute right-0 top-12 w-56 overflow-hidden rounded-md border border-stone-200 bg-white p-2 shadow-lg shadow-ink/10" aria-label="Chia sẻ sản phẩm">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" @click="shareOpen = false" class="flex min-h-11 items-center gap-3 rounded-sm px-3 text-sm text-stone-700 transition-colors hover:bg-[#f8f7f4] hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-ink">
                        <svg class="h-5 w-5 text-[#1877F2]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.4 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H7.5v3.1h2.7v8z"/></svg>
                        Chia sẻ lên Facebook
                    </a>
                    <button type="button" data-share-native data-share-title="{{ $product->name }}" data-share-url="{{ url()->current() }}" @click="shareOpen = false" class="flex min-h-11 w-full items-center gap-3 rounded-sm px-3 text-left text-sm text-stone-700 transition-colors hover:bg-[#f8f7f4] hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-ink">
                        <svg class="h-5 w-5 text-[#C13584]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.8" r=".8" fill="currentColor" stroke="none"/></svg>
                        Instagram / ứng dụng khác
                    </button>
                    <a href="https://wa.me/?text={{ urlencode($product->name.' '.url()->current()) }}" target="_blank" rel="noopener noreferrer" @click="shareOpen = false" class="flex min-h-11 items-center gap-3 rounded-sm px-3 text-sm text-stone-700 transition-colors hover:bg-[#f8f7f4] hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-ink">
                        <svg class="h-5 w-5 text-[#25D366]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20.2 11.8a8.2 8.2 0 0 1-12.1 7.1L4 20l1.2-4A8.2 8.2 0 1 1 20.2 11.8Z"/><path d="M9 8.5c.3 2.5 2 4.2 4.5 4.8l1-1 1.8.8-.2 1.5c-4.4.9-8.4-3.2-7.7-7.5L10 7z"/></svg>
                        Chia sẻ qua WhatsApp
                    </a>
                    <button type="button" data-copy-share data-share-url="{{ url()->current() }}" @click="shareOpen = false" class="flex min-h-11 w-full items-center gap-3 rounded-sm border-t border-stone-100 px-3 text-left text-sm text-stone-700 transition-colors hover:bg-[#f8f7f4] hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-ink">
                        <svg class="h-5 w-5 text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3"/></svg>
                        Sao chép liên kết
                    </button>
                </div>
            </div>
            <div class="mt-5 flex flex-wrap items-baseline gap-3">@if($product->sale_price)<span class="font-display text-2xl text-clay">{{ number_format((float) $product->sale_price, 0, ',', '.') }}₫</span><span class="text-sm text-stone-400 line-through">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span><span class="tag px-2 py-1 text-[10px] font-semibold uppercase tracking-wider">Ưu đãi</span>@else<span class="font-display text-2xl text-ink">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span>@endif</div>
            <div class="mt-6 border-y border-stone-200 py-5"><p class="text-sm leading-7 text-stone-600">{{ $product->description ?: 'Một thiết kế tinh giản, dễ mặc và được tạo nên để đồng hành cùng bạn mỗi ngày.' }}</p></div>
            @if($product->sizes)<div class="mt-6"><p class="eyebrow mb-3">Kích cỡ hiện có</p><div class="flex flex-wrap gap-2">@foreach($product->sizes as $size)<span class="border border-stone-300 bg-white px-4 py-2 text-xs">{{ $size }}</span>@endforeach</div></div>@endif
            @if($product->colorVariants->isNotEmpty())<fieldset class="mt-6" data-color-selector>
                <legend class="mb-3 flex items-baseline gap-2"><span class="eyebrow">Màu sắc</span><span class="text-xs text-stone-600" data-selected-color>Chọn màu</span></legend>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($product->colorVariants as $variant)
                        <label class="cursor-pointer" title="{{ $variant->color_hex ?: 'Màu sắc' }}">
                            <input type="radio" name="product_color" value="{{ $variant->id }}" data-product-color-radio data-src="{{ $variant->image_url }}" data-alt="{{ $product->name }} — {{ $variant->name }}" data-color="{{ $variant->name }}" class="peer sr-only" aria-label="Chọn màu {{ $variant->name }}">
                            <span style="background-color: {{ $variant->color_hex ?: '#b8b0a4' }}" class="block h-8 w-8 rounded-full border border-stone-400 transition-colors hover:border-clay peer-checked:border-ink peer-checked:ring-2 peer-checked:ring-ink/30 peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-clay"></span>
                        </label>
                    @endforeach
                </div>
            </fieldset>@endif
            <p class="mt-7 text-xs {{ $product->stock_quantity > 0 ? 'text-olive' : 'text-stone-500' }}">{{ $product->stock_quantity > 0 ? 'Còn hàng · '.$product->stock_quantity.' sản phẩm' : 'Tạm hết hàng' }}</p>
            <p class="mt-4 text-[10px] uppercase tracking-[.12em] text-stone-500">Danh mục sản phẩm · Liên hệ studio để được tư vấn đặt hàng</p>
        </div>
    </div>
</section>
<section id="reviews" class="container-page border-t border-stone-300 py-14 sm:py-20" aria-labelledby="reviews-title">
    <div class="grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,.9fr)] lg:gap-14">
        <div>
            <p class="eyebrow">Chia sẻ trải nghiệm</p>
            <h2 id="reviews-title" class="mt-2 font-display text-3xl sm:text-4xl">Đánh giá khách hàng <span class="text-base font-sans text-stone-500">({{ $reviews->total() }})</span></h2>
            @if($reviews->isNotEmpty())
                <div class="mt-7 divide-y divide-stone-300">
                    @foreach($reviews as $review)
                        <article class="py-5 first:pt-0"><div class="flex flex-wrap items-center justify-between gap-2"><p class="text-sm font-medium">{{ $review->customer_name }}</p><time class="text-xs text-stone-500" datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->format('d.m.Y') }}</time></div><p class="mt-1 text-sm tracking-wide text-clay" aria-label="{{ $review->rating }} trên 5 sao">{{ str_repeat('★', $review->rating) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $review->rating) }}</span></p><p class="mt-3 whitespace-pre-line text-sm leading-6 text-stone-600">{{ $review->body }}</p></article>
                    @endforeach
                </div>
                <div class="mt-5">{{ $reviews->links() }}</div>
            @else
                <p class="mt-5 border-y border-stone-200 py-6 text-sm leading-6 text-stone-600">Sản phẩm chưa có đánh giá. Nếu bạn đã trải nghiệm sản phẩm, hãy chia sẻ cảm nhận đầu tiên.</p>
            @endif
        </div>
        <form method="POST" action="{{ route('products.reviews.store', $product) }}" class="h-fit border border-stone-200 bg-white p-5 sm:p-7">
            @csrf
            <h3 class="font-display text-2xl">Viết đánh giá</h3>
            <p class="mt-2 text-xs leading-5 text-stone-500">Đánh giá sẽ xuất hiện sau khi cửa hàng duyệt.</p>
            @if($errors->has('customer_name') || $errors->has('rating') || $errors->has('body'))<div class="mt-4 border border-red-200 bg-red-50 p-3 text-xs text-red-800" role="alert">@foreach(['customer_name', 'rating', 'body'] as $field)@error($field)<p>{{ $message }}</p>@enderror @endforeach</div>@endif
            <label class="mt-5 block"><span class="eyebrow">Tên hiển thị *</span><input class="form-control mt-2" name="customer_name" maxlength="80" required value="{{ old('customer_name') }}" autocomplete="name"></label>
            <fieldset class="mt-5"><legend class="eyebrow">Đánh giá sao *</legend><div class="mt-2 flex flex-row-reverse justify-end gap-1" data-rating-group aria-label="Chọn số sao">
                @for($rating = 5; $rating >= 1; $rating--)
                    <label class="relative grid h-10 w-8 cursor-pointer place-items-center"><input class="peer absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay" type="radio" name="rating" value="{{ $rating }}" data-review-rating @checked(old('rating') == $rating) required><span data-rating-star="{{ $rating }}" class="pointer-events-none px-1 text-2xl transition-colors {{ (int) old('rating', 0) >= $rating ? 'text-clay' : 'text-stone-300' }}" aria-hidden="true">★</span></label>
                @endfor
            </div></fieldset>
            <label class="mt-5 block"><span class="eyebrow">Cảm nhận của bạn *</span><textarea class="form-control mt-2" name="body" rows="5" minlength="10" maxlength="1500" required>{{ old('body') }}</textarea><span class="mt-1 block text-xs text-stone-500">Từ 10 đến 1.500 ký tự.</span></label>
            <button class="button-primary mt-5 w-full">Gửi đánh giá</button>
        </form>
    </div>
</section>
@if($related->isNotEmpty())<section class="container-page border-t border-stone-300 py-14 sm:py-20"><p class="eyebrow">Dành cho bạn</p><h2 class="mb-8 mt-2 font-display text-3xl">Có thể bạn cũng thích</h2><div class="grid grid-cols-2 gap-x-4 gap-y-9 md:grid-cols-4 md:gap-6">@foreach($related as $item)@include('storefront._product-card', ['product' => $item])@endforeach</div></section>@endif
@endsection

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
        <div class="rounded-md border border-stone-200 bg-white p-5 sm:p-7 lg:sticky lg:top-24 lg:self-start">
            <p class="eyebrow">{{ $product->category->name }}@if($product->sku) <span class="mx-2">·</span> Mã {{ $product->sku }}@endif</p><h1 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">{{ $product->name }}</h1><div class="mt-5 flex flex-wrap items-baseline gap-3">@if($product->sale_price)<span class="font-display text-2xl text-clay">{{ number_format((float) $product->sale_price, 0, ',', '.') }}₫</span><span class="text-sm text-stone-400 line-through">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span><span class="rounded-sm bg-[#f5e9e3] px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-clay">Ưu đãi</span>@else<span class="font-display text-2xl text-ink">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span>@endif</div>
            <div class="mt-6 border-y border-stone-200 py-5"><p class="text-sm leading-7 text-stone-600">{{ $product->description ?: 'Một thiết kế tinh giản, dễ mặc và được tạo nên để đồng hành cùng bạn mỗi ngày.' }}</p></div>
            @if($product->sizes)<div class="mt-6"><p class="eyebrow mb-3">Kích cỡ hiện có</p><div class="flex flex-wrap gap-2">@foreach($product->sizes as $size)<span class="border border-stone-300 bg-white px-4 py-2 text-xs">{{ $size }}</span>@endforeach</div></div>@endif
            @if($product->colorVariants->isNotEmpty())<div class="mt-6" data-color-selector aria-label="Chọn màu sắc">
                <div class="mb-3 flex items-baseline gap-2"><p class="eyebrow">Màu sắc</p><span class="text-xs text-stone-600" data-selected-color>Chọn màu</span></div>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($product->colorVariants as $variant)
                        <button type="button" data-product-color data-src="{{ $variant->image_url }}" data-alt="{{ $product->name }} — {{ $variant->name }}" data-color="{{ $variant->name }}" aria-label="Chọn màu {{ $variant->name }}" aria-pressed="false" title="{{ $variant->name }}" class="grid h-14 w-12 cursor-pointer place-items-center overflow-hidden rounded-sm border border-stone-300 bg-white p-1 transition-colors hover:border-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay aria-pressed:border-clay aria-pressed:ring-1 aria-pressed:ring-clay/30">
                            @if($variant->image_url)<img data-fallback-image class="h-full w-full object-cover" src="{{ $variant->image_url }}" alt="" loading="lazy">@else<span class="px-0.5 text-center text-[8px] leading-tight text-stone-500">{{ $variant->name }}</span>@endif
                        </button>
                    @endforeach
                </div>
            </div>@endif
            <p class="mt-7 text-xs {{ $product->stock_quantity > 0 ? 'text-olive' : 'text-stone-500' }}">{{ $product->stock_quantity > 0 ? 'Còn hàng · '.$product->stock_quantity.' sản phẩm' : 'Tạm hết hàng' }}</p>
            <p class="mt-4 text-[10px] uppercase tracking-[.12em] text-stone-500">Danh mục sản phẩm · Liên hệ studio để được tư vấn đặt hàng</p>
        </div>
    </div>
</section>
@if($related->isNotEmpty())<section class="container-page border-t border-stone-300 py-14 sm:py-20"><p class="eyebrow">Dành cho bạn</p><h2 class="mb-8 mt-2 font-display text-3xl">Có thể bạn cũng thích</h2><div class="grid grid-cols-2 gap-x-4 gap-y-9 md:grid-cols-4 md:gap-6">@foreach($related as $item)@include('storefront._product-card', ['product' => $item])@endforeach</div></section>@endif
@endsection

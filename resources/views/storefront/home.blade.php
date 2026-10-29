@extends('layouts.store')
@section('title', 'Thời trang tinh giản cho mỗi ngày')
@section('content')
@php($slideCount = $slides->count())

@if($slides->isNotEmpty())
<section class="container-page py-6 sm:py-9">
    <div class="grid items-stretch gap-8 lg:grid-cols-[1fr_.92fr] lg:gap-12" x-data="{ active: 0, total: {{ $slideCount }}, labels: @js($slides->map(fn ($item) => $item->eyebrow ?: $item->title)->values()), timer: null, start() { if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) this.timer = setInterval(() => this.active = (this.active + 1) % this.total, 5000); }, select(index) { this.active = index; clearInterval(this.timer); this.start(); } }" x-init="start()" role="region" aria-label="Bộ sưu tập nổi bật" aria-roledescription="băng chuyền">
        <div class="relative flex min-w-0 flex-col justify-center py-2 sm:py-4">
            <div class="relative z-10">
                <div class="grid">
                    @foreach($slides as $index => $item)
                        <div class="col-start-1 row-start-1 transition-opacity"
                            :class="active === {{ $index }} ? 'opacity-100 duration-300 delay-200' : 'pointer-events-none opacity-0 duration-200'"
                            :aria-hidden="active !== {{ $index }}"
                            :inert="active !== {{ $index }}">
                            @if($item->eyebrow)<p class="eyebrow text-stone-600">{{ $item->eyebrow }}</p>@else<p class="eyebrow text-stone-600">Bộ sưu tập mới</p>@endif
                            <h1 class="mt-4 max-w-2xl font-display text-3xl font-normal leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">{{ $item->title }}</h1>
                            @if($item->description)<p class="mt-4 max-w-lg text-sm leading-6 text-stone-600 sm:text-base sm:leading-7">{{ $item->description }}</p>@endif
                            <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3">
                                <a href="{{ $item->link_url ?: route('products.index') }}" class="inline-flex min-h-11 items-center rounded-md bg-clay px-6 text-sm font-medium text-paper transition-colors hover:bg-clay/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">{{ $item->link_label ?: 'Khám phá bộ sưu tập' }} <span class="ml-3" aria-hidden="true">↗</span></a>
                                <a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center rounded-md px-2 text-sm font-medium underline decoration-stone-400 underline-offset-4 transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">Xem tất cả sản phẩm</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <ol class="mt-7 max-w-xl border-t border-[#d9d0bf]" aria-label="Chọn bộ sưu tập">
                    @foreach($slides as $index => $item)
                        <li class="border-b border-[#d9d0bf]">
                            <button type="button" class="flex min-h-11 w-full items-center gap-3 py-2 text-left transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" :class="active === {{ $index }} ? 'text-clay' : 'text-ink/80'" :aria-pressed="active === {{ $index }}" aria-label="Xem bộ sưu tập {{ $item->title }}" @click="select({{ $index }})">
                                <span class="w-7 font-display text-lg italic">0{{ $index + 1 }}</span><span class="min-w-0 flex-1 truncate text-sm">{{ $item->title }}</span><span class="text-xs text-stone-500">{{ $item->link_label ?: 'Khám phá' }} ↗</span>
                            </button>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="relative min-h-[350px] overflow-hidden rounded-t-[999px] bg-[#dcd0bb] sm:min-h-[450px] lg:min-h-[510px]">
            @foreach($slides as $index => $item)
                <img data-fallback-image src="{{ $item->image_url }}" alt="{{ $item->title }}"
                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
                    :class="active === {{ $index }} ? 'opacity-100' : 'opacity-0'"
                    @if($index === 0) fetchpriority="high" @endif decoding="async"
                    :aria-hidden="active !== {{ $index }}">
            @endforeach
            <div class="absolute inset-x-4 bottom-4 bg-paper px-4 py-3 text-xs text-ink sm:inset-x-6 sm:bottom-6 sm:w-fit sm:px-5"><span class="font-medium">{{ $storeSettings->shop_name }}</span><span class="mx-2 text-stone-400">·</span><span x-text="labels[active]"></span></div>
        </div>
    </div>
</section>
@else
<section class="container-page py-6 sm:py-9">
    <div class="grid items-center gap-8 lg:grid-cols-[1fr_.92fr] lg:gap-14">
        <div class="relative py-5 sm:py-8"><div class="relative z-10"><p class="eyebrow">Bộ sưu tập mới</p><h1 class="mt-4 max-w-2xl font-display text-3xl font-normal leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">Mặc nhẹ nhàng, <span class="italic text-clay">sống trọn vẹn.</span></h1><p class="mt-4 max-w-lg text-sm leading-6 text-stone-600 sm:text-base sm:leading-7">Phom dáng dễ chịu, chất liệu tự nhiên và những gam màu khiến bạn muốn mặc mỗi ngày.</p><div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3"><a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center rounded-full bg-clay px-6 text-sm font-medium text-paper transition-colors hover:bg-ink">Khám phá bộ sưu tập <span class="ml-3" aria-hidden="true">↗</span></a><a href="#san-pham" class="text-sm font-medium underline decoration-stone-400 underline-offset-4 hover:text-clay">Xem hàng mới</a></div></div></div>
        <div class="relative min-h-[350px] overflow-hidden rounded-t-[999px] bg-[#dcd0bb] sm:min-h-[450px]"><img data-fallback-image src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1400&q=85" alt="Bộ sưu tập thời trang {{ $storeSettings->shop_name }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high"><div class="absolute inset-x-4 bottom-4 bg-paper px-4 py-3 text-xs text-ink sm:inset-x-6 sm:bottom-6 sm:w-fit sm:px-5"><span class="font-medium">{{ $storeSettings->shop_name }}</span><span class="mx-2 text-stone-400">·</span>Trang phục thường ngày</div></div>
    </div>
</section>
@endif

<section class="container-page" aria-label="Điểm nổi bật của {{ $storeSettings->shop_name }}">
    <div class="grid border-y border-[#d9d0bf] sm:grid-cols-2 lg:grid-cols-4 sm:divide-x sm:divide-[#d9d0bf]">
        <div class="flex items-start gap-4 border-b border-stone-200 py-5 sm:border-0 sm:px-5 sm:py-7 first:sm:pl-0"><span class="font-display text-2xl italic text-clay">01</span><div><h2 class="text-sm font-medium">Chất liệu dễ chịu</h2><p class="mt-1 text-xs leading-5 text-stone-600">Linen, cotton và len mềm cho những ngày thoải mái.</p></div></div>
        <div class="flex items-start gap-4 border-b border-stone-200 py-5 sm:border-0 sm:px-5 sm:py-7"><span class="font-display text-2xl italic text-clay">02</span><div><h2 class="text-sm font-medium">Phom dáng dễ mặc</h2><p class="mt-1 text-xs leading-5 text-stone-600">Thiết kế tinh giản, dễ kết hợp theo cách của bạn.</p></div></div>
        <div class="flex items-start gap-4 border-b border-stone-200 py-5 sm:border-0 sm:px-5 sm:py-7"><span class="font-display text-2xl italic text-clay">03</span><div><h2 class="text-sm font-medium">Gam màu dễ phối</h2><p class="mt-1 text-xs leading-5 text-stone-600">Sắc độ nhẹ nhàng để mặc qua nhiều mùa.</p></div></div>
        <div class="flex items-start gap-4 py-5 sm:px-5 sm:py-7 sm:pr-0"><span class="font-display text-2xl italic text-clay">04</span><div><h2 class="text-sm font-medium">Tư vấn tận tâm</h2><p class="mt-1 text-xs leading-5 text-stone-600">Liên hệ cửa hàng để được hỗ trợ chọn mẫu và kích cỡ.</p></div></div>
    </div>
</section>

<section class="container-page pt-20 sm:pt-24" aria-labelledby="categories-title">
    <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Tìm phong cách của bạn</p><h2 id="categories-title" class="mt-2 font-display text-2xl font-normal sm:text-5xl">Danh mục sản phẩm</h2></div><a href="{{ route('products.index') }}" class="text-sm font-medium underline decoration-stone-400 underline-offset-4 hover:text-clay">Xem tất cả ↗</a></div>
    <div class="home-category-grid mt-8 grid grid-cols-2 gap-x-4 gap-y-6 sm:mt-10 sm:gap-6 lg:grid-cols-4">
        @forelse($categories as $index => $category)
            @php($categoryProducts = $category->products->concat($category->children->flatMap->products))
            @php($categoryProductCount = $category->products_count + $category->children->sum(fn ($child) => $child->products->count()))
            <div class="min-w-0">
                <a href="{{ route('categories.show', $category) }}" class="group relative flex min-h-[230px] aspect-[4/5] flex-col items-center justify-between overflow-hidden rounded-t-[999px] px-4 pb-5 pt-10 text-center transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink sm:min-h-[320px] sm:px-6 sm:pb-7 sm:pt-14" style="background-color: {{ ['#e8dfd0', '#ded8c9', '#e7e1d6', '#dcd0bb'][$index % 4] }}">
                    @if($category->image_url)<img src="{{ $category->image_url }}" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]"><span aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-ink/70 via-ink/10 to-ink/5"></span>@endif
                    <span class="relative z-10 mt-auto max-w-full break-words px-1 font-display text-base leading-snug {{ $category->image_url ? 'text-white' : 'text-ink' }} sm:text-2xl">{{ $category->name }}</span><span class="relative z-10 mt-2 text-[9px] uppercase tracking-[.12em] {{ $category->image_url ? 'text-white/90' : 'text-stone-600' }}">{{ $categoryProductCount }} sản phẩm ↗</span>
                </a>
                @if($category->children->isNotEmpty())<div class="mt-2 flex flex-wrap gap-x-2 gap-y-1">@foreach($category->children as $child)<a href="{{ route('categories.show', $child) }}" class="inline-flex min-h-11 items-center rounded-sm px-2 text-xs text-stone-600 underline decoration-stone-300 underline-offset-2 transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-ink">{{ $child->name }}</a>@endforeach</div>@endif
            </div>
        @empty
            <div class="col-span-full border-y border-stone-300 py-14 text-center"><p class="font-display text-2xl">Bộ sưu tập mới đang được chuẩn bị.</p><a href="{{ route('products.index') }}" class="mt-3 inline-block text-sm underline underline-offset-4">Xem tất cả sản phẩm</a></div>
        @endforelse
    </div>
</section>

<section id="san-pham" class="container-page pt-20 sm:pt-24" x-data="{ tab: 'new' }" aria-labelledby="products-title">
    <div class="flex flex-wrap items-end justify-between gap-5"><div><p class="eyebrow">Vừa có mặt tại studio</p><h2 id="products-title" class="mt-2 font-display text-3xl font-normal sm:text-5xl">Chọn lọc cho bạn</h2></div><div class="flex gap-2" role="tablist" aria-label="Chọn nhóm sản phẩm">
        <button type="button" role="tab" :aria-selected="tab === 'new'" :class="tab === 'new' ? 'bg-ink text-paper' : 'border border-[#d9d0bf] text-ink hover:border-ink'" class="min-h-11 rounded-md px-4 text-xs transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" @click="tab = 'new'">Mới về</button>
        <button type="button" role="tab" :aria-selected="tab === 'best'" :class="tab === 'best' ? 'bg-ink text-paper' : 'border border-[#d9d0bf] text-ink hover:border-ink'" class="min-h-11 rounded-md px-4 text-xs transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" @click="tab = 'best'">Bán chạy</button>
    </div></div>
    <div class="mt-8 sm:mt-10">
        <div x-cloak x-show="tab === 'new'" role="tabpanel"><div class="grid grid-cols-2 gap-x-4 gap-y-9 lg:grid-cols-4 lg:gap-6">@forelse($newArrivals as $product)@include('storefront._product-card', ['product' => $product, 'homeCard' => true])@empty<div class="col-span-full border border-stone-300 py-12 text-center text-sm text-stone-500">Sản phẩm mới sẽ sớm được cập nhật.</div>@endforelse</div></div>
        <div x-cloak x-show="tab === 'best'" role="tabpanel"><div class="grid grid-cols-2 gap-x-4 gap-y-9 lg:grid-cols-4 lg:gap-6">@forelse($bestSellers as $product)@include('storefront._product-card', ['product' => $product, 'homeCard' => true])@empty<div class="col-span-full border border-stone-300 py-12 text-center text-sm text-stone-500">Danh sách bán chạy đang được cập nhật.</div>@endforelse</div></div>
    </div>
    <div class="mt-8 text-center"><a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center rounded-md border-2 border-ink px-6 text-xs font-medium transition-colors hover:bg-ink hover:text-paper">Xem tất cả sản phẩm <span class="ml-3">↗</span></a></div>
</section>

@if($saleProducts->isNotEmpty() || $featured->isNotEmpty())
    @php($studioPick = $featured->first() ?: $saleProducts->first())
    @php($studioImage = $studioPick->images->firstWhere('is_primary', true) ?: $studioPick->images->first())
    @php($studioSale = $studioPick->sale_price && (float) $studioPick->sale_price < (float) $studioPick->price)
    @php($studioColors = $studioPick->colorVariants->where('show_on_home', true))
    <section class="mt-20 bg-[#ede6d8] py-16 sm:mt-24 sm:py-20" aria-labelledby="studio-pick-title">
        <div class="container-page grid items-center gap-10 md:grid-cols-2 md:gap-14" data-product-visual>
            <a href="{{ route('products.show', $studioPick) }}" class="group relative mx-auto block min-h-[420px] w-full max-w-[480px] overflow-hidden rounded-t-[999px] bg-[#d8cbb2] sm:min-h-[570px]" aria-label="Xem {{ $studioPick->name }}">
                @if($studioImage)<img data-product-main data-fallback-image src="{{ $studioImage->url }}" alt="{{ $studioImage->alt_text ?: $studioPick->name }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]" loading="lazy">@elseif($studioColors->isNotEmpty())<img data-product-main data-fallback-image class="absolute inset-0 h-full w-full object-cover" alt="{{ $studioPick->name }}" hidden>@else<div class="absolute inset-0 bg-[#e8dfd0]"></div>@endif
                <span class="absolute bottom-5 left-5 bg-paper px-4 py-2 text-[9px] font-semibold uppercase tracking-[.14em]">{{ $studioSale ? ($storeSettings->badge_sale ?: 'Sale') : ($storeSettings->badge_featured ?: 'Sản phẩm nổi bật') }}</span>
            </a>
            <div class="py-4"><p class="eyebrow">Một món đồ, nhiều ngày yêu thích</p><h2 id="studio-pick-title" class="mt-4 max-w-lg font-display text-3xl font-normal leading-tight sm:text-5xl">Lựa chọn dành riêng <span class="italic text-clay">cho bạn.</span></h2><p class="mt-8 text-xs uppercase tracking-[.14em] text-stone-500">{{ $studioPick->category->name }}</p><h3 class="mt-2 font-display text-2xl sm:text-3xl">{{ $studioPick->name }}</h3>@if($studioPick->description)<p class="mt-3 max-w-md text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($studioPick->description, 180) }}</p>@endif
                <div class="mt-5 flex flex-wrap items-baseline gap-3">@if($studioSale)<span class="font-display text-2xl">{{ number_format((float) $studioPick->sale_price, 0, ',', '.') }}₫</span><span class="text-sm text-stone-500 line-through">{{ number_format((float) $studioPick->price, 0, ',', '.') }}₫</span><span class="bg-ink px-2 py-1 text-[10px] font-semibold text-paper">−{{ (int) round((1 - (float) $studioPick->sale_price / (float) $studioPick->price) * 100) }}%</span>@else<span class="font-display text-2xl">{{ number_format((float) $studioPick->price, 0, ',', '.') }}₫</span>@endif</div>
                @if($studioColors->isNotEmpty())<fieldset class="mt-5" data-color-selector><legend class="mb-2 text-xs text-stone-600">Màu <span class="ml-2" data-selected-color>Chọn màu</span></legend><div class="flex flex-wrap gap-0">@foreach($studioColors as $variant)<label class="grid min-h-11 min-w-8 cursor-pointer place-items-center rounded-full focus-within:outline focus-within:outline-2 focus-within:outline-offset-1 focus-within:outline-ink" title="{{ $variant->name }}"><input type="radio" name="home_spotlight_color" value="{{ $variant->id }}" data-product-color-radio data-src="{{ $variant->image_url }}" data-alt="{{ $studioPick->name }} — {{ $variant->name }}" data-color="{{ $variant->name }}" class="peer sr-only" aria-label="Chọn màu {{ $variant->name }}"><span style="background-color: {{ $variant->color_hex ?: '#b8b0a4' }}" class="block h-6 w-6 rounded-full border border-stone-400 transition-colors peer-checked:border-ink peer-checked:ring-2 peer-checked:ring-ink/30"></span></label>@endforeach</div></fieldset>@endif
                @if($studioPick->sizes)<fieldset class="mt-4"><legend class="mb-2 text-xs text-stone-600">Size</legend><div class="flex flex-wrap gap-2">@foreach($studioPick->sizes as $size)<label class="cursor-pointer"><input type="radio" name="home_spotlight_size" value="{{ $size }}" class="peer sr-only"><span class="grid min-h-10 min-w-10 place-items-center border border-ink px-3 text-xs peer-checked:bg-ink peer-checked:text-paper peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-clay">{{ $size }}</span></label>@endforeach<a href="{{ route('products.show', $studioPick) }}" class="ml-1 self-center text-xs underline underline-offset-4">Bảng size</a></div></fieldset>@endif
                <a href="{{ route('products.show', $studioPick) }}" class="mt-7 inline-flex min-h-12 items-center rounded-full bg-clay px-7 text-sm font-medium text-white transition-colors hover:bg-clay/90">Khám phá thiết kế <span class="ml-3">↗</span></a>
            </div>
        </div>
    </section>
@endif

<section class="mt-20 bg-olive py-16 text-paper sm:mt-24 sm:py-24">
    <div class="container-page">
        <div class="mx-auto max-w-4xl"><p class="eyebrow text-paper/70">Về {{ $storeSettings->shop_name }}</p><h2 class="mt-3 max-w-2xl font-display text-3xl font-normal leading-tight sm:text-5xl">Tủ đồ của bạn nên giống chính bạn.</h2><p class="mt-6 max-w-2xl text-base leading-7 text-paper/80">{{ $storeSettings->shop_name }} bắt đầu từ một ý niệm giản dị: quần áo nên khiến bạn cảm thấy thoải mái và tự tin trong ngày thường. Chúng tôi tuyển chọn những thiết kế tinh giản, dễ kết hợp và có thể trở thành món đồ bạn muốn mặc lại.</p><div class="mt-9 grid gap-6 border-t border-paper/25 pt-6 sm:grid-cols-3"><div><h3 class="text-sm font-medium">Chọn kỹ</h3><p class="mt-2 text-xs leading-5 text-paper/70">Tập trung vào chất liệu, phom dáng và tính ứng dụng.</p></div><div><h3 class="text-sm font-medium">Mặc lâu</h3><p class="mt-2 text-xs leading-5 text-paper/70">Ưu tiên thiết kế dễ kết hợp qua nhiều mùa.</p></div><div><h3 class="text-sm font-medium">Là chính mình</h3><p class="mt-2 text-xs leading-5 text-paper/70">Phong cách cá nhân bắt đầu từ cảm giác thoải mái.</p></div></div><a href="{{ route('about') }}" class="mt-9 inline-flex min-h-11 items-center rounded-full border border-paper/70 px-6 text-xs font-medium transition-colors hover:bg-paper hover:text-ink">Tìm hiểu về chúng tôi <span class="ml-3">↗</span></a></div>
    </div>
</section>

<section class="bg-paper py-16 sm:py-20" aria-labelledby="customer-notes-title">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Khách hàng của {{ $storeSettings->shop_name }}</p><h2 id="customer-notes-title" class="mt-2 font-display text-3xl font-normal sm:text-5xl">Họ mặc lại mỗi tuần</h2></div><a href="{{ route('products.index') }}" class="text-sm underline decoration-stone-400 underline-offset-4 hover:text-clay">Khám phá sản phẩm ↗</a></div>
        @if($homeReviews->isNotEmpty())
            <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($homeReviews as $review)
                    <figure class="flex h-full flex-col bg-[#ede6d8] p-6 sm:p-8"><div class="text-clay" aria-label="{{ $review->rating }} trên 5 sao">{{ str_repeat('★', $review->rating) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $review->rating) }}</span></div><blockquote class="mt-4 flex-1 font-display text-xl leading-relaxed">“{{ $review->body }}”</blockquote><figcaption class="mt-6 border-t border-[#d9d0bf] pt-4 text-xs text-stone-600">{{ $review->customer_name }}@if($review->product)<span class="mx-2">·</span>{{ $review->product->name }}@endif</figcaption></figure>
                @endforeach
            </div>
        @else
            <p class="mt-8 border-t border-[#d9d0bf] py-8 text-sm text-stone-600">Chưa có đánh giá được duyệt. Hãy ghé trang sản phẩm để gửi cảm nhận đầu tiên.</p>
        @endif
    </div>
</section>

@if($latestNews->isNotEmpty())
<section class="container-page py-16 sm:py-24"><div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Nhật ký của Atelier</p><h2 class="mt-2 font-display text-3xl font-normal sm:text-5xl">Tin tức & cảm hứng</h2></div><a href="{{ route('news.index') }}" class="text-sm font-medium underline decoration-stone-400 underline-offset-4 hover:text-clay">Đọc thêm ↗</a></div><div class="mt-8 grid gap-7 sm:mt-10 sm:grid-cols-2 lg:grid-cols-3">@foreach($latestNews as $article)<article class="group min-w-0"><a href="{{ route('news.show', $article) }}" class="block"><div class="aspect-[4/3] overflow-hidden bg-[#e8e1d5]">@if($article->cover_url)<img data-fallback-image src="{{ $article->cover_url }}" alt="{{ $article->title }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.025]">@endif</div><p class="eyebrow mt-4">{{ $article->published_at->format('d.m.Y') }}</p><h3 class="mt-2 line-clamp-2 font-display text-xl leading-snug sm:text-2xl">{{ $article->title }}</h3><p class="mt-2 line-clamp-2 text-sm leading-6 text-stone-600">{{ $article->excerpt }}</p><span class="mt-4 inline-block text-xs font-medium underline decoration-stone-400 underline-offset-4">Đọc câu chuyện ↗</span></a></article>@endforeach</div></section>
@endif

<section class="container-page pb-16 sm:pb-20">
    <div class="grid gap-6 bg-[#ede6d8] p-6 sm:p-10 md:grid-cols-2 md:items-center md:gap-12">
        <div><p class="eyebrow">Kết nối cùng {{ $storeSettings->shop_name }}</p><h2 class="mt-2 font-display text-2xl leading-tight sm:text-4xl">Nhận tư vấn chọn món đồ dành riêng cho bạn.</h2><p class="mt-3 max-w-lg text-sm leading-6 text-stone-600">Liên hệ với studio để hỏi về chất liệu, kích cỡ và những thiết kế phù hợp với phong cách của bạn.</p></div>
        <div class="flex flex-wrap items-center gap-3 md:justify-end">
            @if($storeSettings->email)
                <a href="mailto:{{ $storeSettings->email }}" class="inline-flex min-h-12 items-center rounded-full bg-clay px-6 text-sm font-medium text-white transition-colors hover:bg-clay/90">Gửi email tư vấn ↗</a>
            @else
                <a href="{{ route('about') }}" class="inline-flex min-h-12 items-center rounded-full bg-clay px-6 text-sm font-medium text-white transition-colors hover:bg-clay/90">Thông tin liên hệ ↗</a>
            @endif
            @if($storeSettings->phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $storeSettings->phone) }}" class="inline-flex min-h-12 items-center rounded-full border border-ink px-6 text-sm transition-colors hover:bg-ink hover:text-paper">Gọi {{ $storeSettings->phone }}</a>
            @endif
        </div>
    </div>
</section>
@endsection

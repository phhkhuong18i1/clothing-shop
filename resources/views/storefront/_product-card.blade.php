@php($isOnSale = $product->sale_price && (float) $product->sale_price < (float) $product->price)
@php($representativeImage = $product->images->firstWhere('is_primary', true) ?: $product->images->first())
@php($homeCard = $homeCard ?? false)
@php($colorChoices = $homeCard ? $product->colorVariants->where('show_on_home', true) : $product->colorVariants)
<article class="group min-w-0" data-product-visual>
    <a href="{{ route('products.show', $product) }}" class="block rounded-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink" aria-label="Xem {{ $product->name }}">
        <div class="relative {{ $homeCard ? 'aspect-[3/4]' : 'aspect-[4/5]' }} overflow-hidden rounded-md bg-[#e8e4dc]">
            @if($representativeImage)
                <img data-product-main data-fallback-image class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.035]" src="{{ $representativeImage->url }}" alt="{{ $representativeImage->alt_text ?: $product->name }}" loading="lazy">
            @else
                @if($colorChoices->isNotEmpty())<img data-product-main data-fallback-image class="h-full w-full object-cover" alt="{{ $product->name }}" hidden>@endif
                <div data-product-placeholder class="grid h-full place-items-center"><span class="font-display text-3xl text-stone-400">{{ $storeSettings->shop_name }}</span></div>
            @endif

            <div class="absolute left-3 top-3 flex max-w-[calc(100%-1.5rem)] flex-wrap gap-1.5 sm:left-4 sm:top-4 sm:gap-2">
                @if($isOnSale && $storeSettings->badge_sale)<span class="tag max-w-full px-2 py-1.5 text-[8px] font-semibold uppercase leading-tight tracking-[.1em] sm:px-3 sm:py-2 sm:text-[9px] sm:tracking-[.14em]">{{ $storeSettings->badge_sale }}</span>@endif
                @if($product->is_best_seller && $storeSettings->badge_best_seller)<span class="tag max-w-full px-2 py-1.5 text-[8px] font-semibold uppercase leading-tight tracking-[.08em] sm:px-3 sm:py-2 sm:text-[9px] sm:tracking-[.12em]">{{ $storeSettings->badge_best_seller }}</span>
                @elseif($product->is_featured && $storeSettings->badge_featured)<span class="tag max-w-full px-2 py-1.5 text-[8px] font-semibold uppercase leading-tight tracking-[.08em] sm:px-3 sm:py-2 sm:text-[9px] sm:tracking-[.12em]">{{ $storeSettings->badge_featured }}</span>@endif
            </div>

            <span class="absolute bottom-3 right-3 grid h-10 w-10 translate-y-1 place-items-center rounded-md bg-paper text-ink opacity-100 transition-all duration-200 group-hover:translate-y-0 group-hover:bg-ink group-hover:text-white group-focus-visible:translate-y-0 sm:bottom-4 sm:right-4 sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-visible:opacity-100" aria-hidden="true">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 10h12m-5-5 5 5-5 5"/></svg>
            </span>
        </div>
    </a>

    @if(!$homeCard && $colorChoices->isNotEmpty())
        <div class="mt-2 flex min-h-9 items-center gap-2" data-color-selector role="group" aria-label="Chọn màu cho {{ $product->name }}">
            <div class="flex items-center gap-0">
                @foreach($colorChoices as $variant)
                    <button type="button" data-product-color data-src="{{ $variant->image_url }}" data-alt="{{ $product->name }} — {{ $variant->name }}" data-color="{{ $variant->name }}" aria-label="Chọn màu {{ $variant->name }}" aria-pressed="false" title="{{ $variant->name }}" class="group grid h-11 w-8 shrink-0 cursor-pointer place-items-center rounded-full transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-clay"><span style="background-color: {{ $variant->color_hex ?: '#b8b0a4' }}" class="h-5 w-5 rounded-full border border-stone-400 transition-shadow group-aria-pressed:border-ink group-aria-pressed:ring-2 group-aria-pressed:ring-ink/30"></span></button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="pt-2">
        @if($homeCard)<p class="eyebrow text-[9px]">{{ $product->category->name }}</p>@endif
        <h3 class="mt-1 min-h-10 line-clamp-2 font-display {{ $homeCard ? 'text-xl sm:text-2xl' : 'text-base sm:text-lg' }} leading-snug text-ink transition-colors group-hover:text-clay"><a href="{{ route('products.show', $product) }}" class="rounded-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink">{{ $product->name }}</a></h3>
        @if($homeCard && $colorChoices->isNotEmpty())
            <div class="mt-2 flex min-h-6 items-center gap-2" data-color-selector role="group" aria-label="Chọn màu cho {{ $product->name }}"><div class="flex items-center gap-0">
                @foreach($colorChoices as $variant)<button type="button" data-product-color data-src="{{ $variant->image_url }}" data-alt="{{ $product->name }} — {{ $variant->name }}" data-color="{{ $variant->name }}" aria-label="Chọn màu {{ $variant->name }}" aria-pressed="false" title="{{ $variant->name }}" class="group grid h-11 w-8 shrink-0 cursor-pointer place-items-center rounded-full transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-clay"><span style="background-color: {{ $variant->color_hex ?: '#b8b0a4' }}" class="h-5 w-5 rounded-full border border-stone-400 transition-shadow group-aria-pressed:border-ink group-aria-pressed:ring-2 group-aria-pressed:ring-ink/30"></span></button>@endforeach
            </div></div>
        @endif
        <div class="mt-1 flex flex-wrap items-baseline gap-x-2 gap-y-1">
            @if($isOnSale)
                <span class="text-sm font-semibold text-clay sm:text-base">{{ number_format((float) $product->sale_price, 0, ',', '.') }}₫</span>
                <span class="text-xs text-stone-400 line-through">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span>
                @if((float) $product->price > 0)<span class="ml-auto text-[10px] font-semibold uppercase tracking-wider text-clay">−{{ (int) round((1 - (float) $product->sale_price / (float) $product->price) * 100) }}%</span>@endif
            @else
                <span class="text-sm font-medium sm:text-base">{{ number_format((float) $product->price, 0, ',', '.') }}₫</span>
            @endif
        </div>

    </div>
</article>

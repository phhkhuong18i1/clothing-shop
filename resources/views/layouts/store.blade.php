<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thời trang tinh giản cho mỗi ngày') — {{ $storeSettings->shop_name }}</title>
    @if($storeSettings->logo_url)<link rel="icon" href="{{ $storeSettings->logo_url }}"><link rel="apple-touch-icon" href="{{ $storeSettings->logo_url }}">@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink">
    <div class="bg-ink px-4 py-2 text-center text-[9px] font-medium uppercase tracking-[.18em] text-white sm:text-[10px]">Chọn lọc tinh giản <span class="mx-1 text-white/50">·</span> Mặc mỗi ngày</div>
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-paper" x-data="{ open: false }">
        <div class="container-page flex min-h-[68px] items-center justify-between gap-4 sm:min-h-[78px]">
            <div class="flex min-w-0 items-center gap-2 sm:gap-4">
                <button type="button" class="grid h-10 w-10 shrink-0 place-items-center rounded-full transition-colors hover:bg-[#eeece6] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay lg:hidden" @click="open = !open" :aria-expanded="open" aria-controls="mobile-navigation" :aria-label="open ? 'Đóng điều hướng' : 'Mở điều hướng'">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path :d="open ? 'M6 6l12 12M18 6L6 18' : 'M3 6h18M3 12h18M3 18h18'"/></svg>
                </button>
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2 whitespace-nowrap font-display text-lg text-ink transition-colors hover:text-clay sm:gap-3 sm:text-2xl">
                    @if($storeSettings->logo_url)<img src="{{ $storeSettings->logo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-sm object-contain sm:h-11 sm:w-11">@endif
                    <span class="max-w-[150px] truncate sm:max-w-[220px]">{{ $storeSettings->shop_name }}</span>
                </a>
            </div>

            <nav class="hidden min-w-0 flex-1 flex-wrap items-center justify-center gap-x-4 xl:gap-x-6 lg:flex" aria-label="Điều hướng chính">
                <a class="group relative whitespace-nowrap py-4 text-[9px] font-medium uppercase tracking-[.12em] transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay {{ request()->routeIs('products.index') ? 'text-clay' : 'text-ink' }}" href="{{ route('products.index') }}" @if(request()->routeIs('products.index')) aria-current="page" @endif>Tất cả sản phẩm<span class="absolute inset-x-0 bottom-0 h-px origin-left scale-x-0 bg-clay transition-transform duration-200 group-hover:scale-x-100 {{ request()->routeIs('products.index') ? 'scale-x-100' : '' }}"></span></a>
                @foreach(($navCategories ?? collect()) as $navCategory)
                    <div class="group relative py-4">
                        <a class="inline-flex items-center gap-1 whitespace-nowrap text-[9px] font-medium uppercase tracking-[.12em] text-ink transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay" href="{{ route('categories.show', $navCategory) }}" @if(request()->routeIs('categories.show') && request()->route('category')?->is($navCategory)) aria-current="page" @endif>{{ $navCategory->name }}@if($navCategory->children->isNotEmpty()) <svg class="h-3 w-3 shrink-0 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="m4 6 4 4 4-4"/></svg>@endif</a>
                        @if($navCategory->children->isNotEmpty())
                            <div class="invisible absolute left-1/2 top-full z-40 w-56 -translate-x-1/2 translate-y-2 border border-stone-200 bg-paper p-3 opacity-0 shadow-lg shadow-ink/5 transition-all duration-200 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
                                <p class="border-b border-stone-200 px-3 pb-3 text-[9px] font-semibold uppercase tracking-[.17em] text-stone-400">Khám phá {{ $navCategory->name }}</p>
                                <div class="pt-1">@foreach($navCategory->children as $child)<a class="block px-3 py-2.5 text-xs text-stone-700 transition-colors hover:bg-[#eeece6] hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-clay" href="{{ route('categories.show', $child) }}">{{ $child->name }}</a>@endforeach</div>
                            </div>
                        @endif
                    </div>
                @endforeach
                <a class="group relative whitespace-nowrap py-4 text-[9px] font-semibold uppercase tracking-[.12em] text-clay transition-colors hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay" href="{{ route('sale.index') }}" @if(request()->routeIs('sale.*')) aria-current="page" @endif>Sale<span class="absolute inset-x-0 bottom-0 h-px origin-left scale-x-0 bg-clay transition-transform duration-200 group-hover:scale-x-100 {{ request()->routeIs('sale.*') ? 'scale-x-100' : '' }}"></span></a>
                <a class="group relative whitespace-nowrap py-4 text-[9px] font-medium uppercase tracking-[.12em] text-ink transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay {{ request()->routeIs('news.*') ? 'text-clay' : '' }}" href="{{ route('news.index') }}" @if(request()->routeIs('news.*')) aria-current="page" @endif>Tin tức<span class="absolute inset-x-0 bottom-0 h-px origin-left scale-x-0 bg-clay transition-transform duration-200 group-hover:scale-x-100 {{ request()->routeIs('news.*') ? 'scale-x-100' : '' }}"></span></a>
                <a class="group relative whitespace-nowrap py-4 text-[9px] font-medium uppercase tracking-[.12em] text-ink transition-colors hover:text-clay focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay {{ request()->routeIs('about') ? 'text-clay' : '' }}" href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>Giới thiệu<span class="absolute inset-x-0 bottom-0 h-px origin-left scale-x-0 bg-clay transition-transform duration-200 group-hover:scale-x-100 {{ request()->routeIs('about') ? 'scale-x-100' : '' }}"></span></a>
            </nav>

            <a href="{{ route('search') }}" class="grid h-10 w-10 shrink-0 place-items-center rounded-full transition-colors hover:bg-[#eeece6] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-clay {{ request()->routeIs('search') ? 'bg-[#eeece6] text-clay' : '' }}" aria-label="Tìm kiếm sản phẩm" @if(request()->routeIs('search')) aria-current="page" @endif>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
            </a>
        </div>

        <nav id="mobile-navigation" x-cloak x-show="open" x-transition.opacity class="max-h-[calc(100dvh-4.25rem)] overflow-y-auto border-t border-stone-200 bg-paper px-5 pb-6 pt-3 lg:hidden" aria-label="Điều hướng di động">
            <a class="flex min-h-12 items-center justify-between border-b border-stone-200 text-[11px] font-semibold uppercase tracking-[.14em] text-ink transition-colors hover:text-clay" href="{{ route('products.index') }}" @click="open = false">Tất cả sản phẩm <span aria-hidden="true">↗</span></a>
            @foreach(($navCategories ?? collect()) as $navCategory)
                <div class="border-b border-stone-200" @if($navCategory->children->isNotEmpty()) x-data="{ expanded: false }" @endif>
                    <div class="flex min-h-12 items-center justify-between gap-3">
                        <a class="flex min-h-12 flex-1 items-center text-[11px] font-semibold uppercase tracking-[.14em] text-ink transition-colors hover:text-clay" href="{{ route('categories.show', $navCategory) }}" @click="open = false">{{ $navCategory->name }}</a>
                        @if($navCategory->children->isNotEmpty())
                            <button type="button" class="grid h-10 w-10 place-items-center rounded-full text-stone-500 transition-colors hover:bg-[#eeece6] hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-clay" @click="expanded = !expanded" :aria-expanded="expanded" aria-label="Mở danh mục con {{ $navCategory->name }}">
                                <svg class="h-4 w-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="m3 6 5 5 5-5"/></svg>
                            </button>
                        @endif
                    </div>
                    @if($navCategory->children->isNotEmpty())
                        <div x-cloak x-show="expanded" x-transition class="pb-3 pl-3">
                            @foreach($navCategory->children as $child)<a class="flex min-h-10 items-center border-l border-stone-300 pl-4 text-sm text-stone-600 transition-colors hover:border-clay hover:text-clay" href="{{ route('categories.show', $child) }}" @click="open = false">{{ $child->name }}</a>@endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <a class="flex min-h-12 items-center border-b border-stone-200 text-[11px] font-semibold uppercase tracking-[.14em] text-clay transition-colors hover:text-ink" href="{{ route('sale.index') }}" @click="open = false">Sale</a>
            <a class="flex min-h-12 items-center border-b border-stone-200 text-[11px] font-semibold uppercase tracking-[.14em] text-ink transition-colors hover:text-clay" href="{{ route('news.index') }}" @click="open = false">Tin tức & cảm hứng</a>
            <a class="flex min-h-12 items-center text-[11px] font-semibold uppercase tracking-[.14em] text-ink transition-colors hover:text-clay" href="{{ route('about') }}" @click="open = false">Giới thiệu</a>
        </nav>
    </header>

    @if(session('status'))@include('components.toast', ['message' => session('status')])@endif
    @if($errors->any())@include('components.toast', ['message' => $errors->first(), 'type' => 'error'])@endif
    <main>@yield('content')</main>

    <footer class="mt-20 bg-ink text-paper sm:mt-28">
        <div class="container-page grid gap-10 py-14 sm:py-16 md:grid-cols-[1.2fr_.8fr_1fr] md:gap-14">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 font-display text-3xl tracking-tight text-paper">
                    @if($storeSettings->logo_url)<img src="{{ $storeSettings->logo_url }}" alt="" class="h-12 w-12 shrink-0 rounded-sm bg-white object-contain p-1">@endif
                    <span>{{ $storeSettings->shop_name }}</span>
                </a>
                <p class="mt-5 max-w-sm text-sm leading-7 text-paper/70">Một studio dành cho những món đồ bạn muốn mặc lại. Chúng tôi chọn chất liệu dễ chịu, phom dáng thư thái và sắc màu có thể đi cùng bạn qua nhiều mùa.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($storeSettings->zalo_url)<a class="inline-flex min-h-10 items-center rounded-full border border-paper/50 px-5 text-xs transition-colors hover:bg-paper hover:text-ink" href="{{ $storeSettings->zalo_url }}" target="_blank" rel="noopener noreferrer">Chat Zalo ↗</a>@endif
                    @if($storeSettings->messenger_url)<a class="inline-flex min-h-10 items-center rounded-full border border-paper/50 px-5 text-xs transition-colors hover:bg-paper hover:text-ink" href="{{ $storeSettings->messenger_url }}" target="_blank" rel="noopener noreferrer">Messenger ↗</a>@endif
                    @if($storeSettings->instagram_url)<a class="inline-flex min-h-10 items-center rounded-full border border-paper/50 px-5 text-xs transition-colors hover:bg-paper hover:text-ink" href="{{ $storeSettings->instagram_url }}" target="_blank" rel="noopener noreferrer">Instagram ↗</a>@endif
                </div>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[.2em] text-paper/60">Khám phá</p>
                <div class="mt-5 space-y-3 text-sm text-paper/80">
                    <a class="block transition-colors hover:text-[#d5c6ac]" href="{{ route('products.index') }}">Tất cả sản phẩm</a>
                    @foreach(($navCategories ?? collect()) as $navCategory)
                        <a class="block transition-colors hover:text-[#d5c6ac]" href="{{ route('categories.show', $navCategory) }}">{{ $navCategory->name }}</a>
                        @foreach($navCategory->children as $child)<a class="block pl-3 text-xs text-paper/60 transition-colors hover:text-[#d5c6ac]" href="{{ route('categories.show', $child) }}">↳ {{ $child->name }}</a>@endforeach
                    @endforeach
                    <a class="block transition-colors hover:text-[#d5c6ac]" href="{{ route('sale.index') }}">Sale</a>
                    <a class="block transition-colors hover:text-[#d5c6ac]" href="{{ route('news.index') }}">Tin tức & cảm hứng</a>
                    <a class="block transition-colors hover:text-[#d5c6ac]" href="{{ route('about') }}">Giới thiệu</a>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[.2em] text-paper/60">Liên hệ {{ $storeSettings->shop_name }}</p>
                <p class="mt-5 text-sm leading-6 text-paper/70">Cần tư vấn về chất liệu, kích cỡ hoặc cách chọn đồ? Chúng tôi sẵn sàng hỗ trợ bạn.</p>
                <div class="mt-5 space-y-3 text-sm text-paper/85">
                    @if($storeSettings->email)<p><span class="mr-2 text-paper/50">Email</span><a class="underline underline-offset-4 hover:text-[#d5c6ac]" href="mailto:{{ $storeSettings->email }}">{{ $storeSettings->email }}</a></p>@endif
                    @if($storeSettings->phone)<p><span class="mr-2 text-paper/50">Điện thoại</span><a class="underline underline-offset-4 hover:text-[#d5c6ac]" href="tel:{{ preg_replace('/[^0-9+]/', '', $storeSettings->phone) }}">{{ $storeSettings->phone }}</a></p>@endif
                    @if($storeSettings->address)<p class="text-paper/75"><span class="mr-2 text-paper/50">Địa chỉ</span>{{ $storeSettings->address }}</p>@endif
                    @unless($storeSettings->email || $storeSettings->phone || $storeSettings->address)<p class="text-xs text-paper/60">Thông tin liên hệ sẽ sớm được cập nhật.</p>@endunless
                </div>
            </div>
        </div>
        <div class="border-t border-paper/20"><div class="container-page flex flex-wrap justify-between gap-3 py-5 text-[10px] uppercase tracking-[.14em] text-paper/60"><span>© {{ date('Y') }} {{ $storeSettings->shop_name }} · Thời trang tinh giản</span><a href="{{ route('products.index') }}" class="transition-colors hover:text-paper">Được chọn kỹ, dành cho mỗi ngày</a></div></div>
    </footer>
    @if($storeSettings->zalo_url || $storeSettings->messenger_url)
        <nav class="fixed bottom-4 right-3 z-30 flex flex-col gap-2 sm:bottom-5 sm:right-5 sm:gap-3" aria-label="Liên hệ nhanh">
            @if($storeSettings->zalo_url)<a href="{{ $storeSettings->zalo_url }}" target="_blank" rel="noopener noreferrer" aria-label="Liên hệ {{ $storeSettings->shop_name }} qua Zalo" title="Liên hệ qua Zalo" class="grid h-10 w-10 place-items-center rounded-full bg-[#0866ff] text-[11px] font-bold text-white shadow-lg ring-2 ring-white/90 transition-transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink sm:h-12 sm:w-12 sm:text-[12px] sm:ring-4">Zalo</a>@endif
            @if($storeSettings->messenger_url)<a href="{{ $storeSettings->messenger_url }}" target="_blank" rel="noopener noreferrer" aria-label="Liên hệ {{ $storeSettings->shop_name }} qua Messenger" title="Liên hệ qua Messenger" class="grid h-10 w-10 place-items-center rounded-full bg-[#0866ff] text-white shadow-lg ring-2 ring-white/90 transition-transform hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink sm:h-12 sm:w-12 sm:ring-4"><svg class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.15 2 11.27c0 2.92 1.48 5.52 3.8 7.2V22l3.47-1.9c.87.24 1.78.37 2.73.37 5.52 0 10-4.15 10-9.27S17.52 2 12 2Zm1 12.48-2.55-2.72-4.98 2.72 5.48-5.82 2.61 2.72 4.92-2.72L13 14.48Z"/></svg></a>@endif
        </nav>
    @endif
</body>
</html>

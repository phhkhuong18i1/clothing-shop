<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title', 'Quản trị') — {{ $storeSettings->shop_name }}</title>
    @if($storeSettings->logo_url)<link rel="icon" href="{{ $storeSettings->logo_url }}"><link rel="apple-touch-icon" href="{{ $storeSettings->logo_url }}">@endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f3f2ed]" x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false">
    <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-stone-300 bg-white transition-transform duration-200 md:translate-x-0" :class="menuOpen ? 'translate-x-0' : '-translate-x-full'" aria-label="Thanh điều hướng quản trị">
        <div class="border-b border-stone-200 px-6 py-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-display text-xl tracking-[.08em]">
                @if($storeSettings->logo_url)<img src="{{ $storeSettings->logo_url }}" alt="" class="h-10 w-10 shrink-0 rounded-sm border border-stone-200 object-contain p-0.5">@endif
                <span class="block max-w-[190px] truncate">{{ $storeSettings->shop_name }}</span>
            </a>
            <p class="mt-1 text-[10px] uppercase tracking-[.16em] text-stone-400">Quản lý cửa hàng</p>
        </div>
        <nav class="flex-1 space-y-1 px-3 py-5" aria-label="Khu vực quản trị">
            <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[.18em] text-stone-400">Tổng quan</p>
            <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7"/><rect x="13.5" y="3.5" width="7" height="7"/><rect x="3.5" y="13.5" width="7" height="7"/><rect x="13.5" y="13.5" width="7" height="7"/></svg>
                Bảng điều khiển
            </a>
            <p class="px-3 pb-2 pt-7 text-[10px] font-semibold uppercase tracking-[.18em] text-stone-400">Danh mục cửa hàng</p>
            <a href="{{ route('admin.products.index') }}" @if(request()->routeIs('admin.products.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.products.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 8.5 12 4l8 4.5v9L12 22l-8-4.5z"/><path d="m4.5 8.7 7.5 4.2 7.5-4.2M12 13v8.5M8 6.2l8 4.6"/></svg>
                Sản phẩm
            </a>
            <a href="{{ route('admin.categories.index') }}" @if(request()->routeIs('admin.categories.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.categories.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 5.5h6v6H4zM14 5.5h6v6h-6zM4 15.5h6v4H4zM14 15.5h6v4h-6z"/></svg>
                Danh mục
            </a>
            <a href="{{ route('admin.news.index') }}" @if(request()->routeIs('admin.news.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.news.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 5.5h16v13H4zM8 9h8M8 12h8m-8 3h5"/></svg>
                Tin tức
            </a>
            <a href="{{ route('admin.reviews.index') }}" @if(request()->routeIs('admin.reviews.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.reviews.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="m12 3 2.75 5.57 6.15.9-4.45 4.34 1.05 6.13L12 17.05l-5.5 2.89 1.05-6.13L3.1 9.47l6.15-.9L12 3Z"/></svg>
                Đánh giá khách hàng
            </a>
            <a href="{{ route('admin.sliders.index') }}" @if(request()->routeIs('admin.sliders.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.sliders.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="1"/><circle cx="8" cy="9" r="1.5"/><path d="m4 18 5-5 3 3 3-4 5 6"/></svg>
                Slider trang chủ
            </a>
            <a href="{{ route('admin.settings.edit') }}" @if(request()->routeIs('admin.settings.*')) aria-current="page" @endif class="flex min-h-11 items-center gap-3 border-l-2 px-3 text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'border-clay bg-[#f8f7f4] font-medium text-ink' : 'border-transparent text-stone-600 hover:bg-[#f8f7f4] hover:text-ink' }}">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a7.8 7.8 0 0 1-1.5.9l-.3 1.8h-2.8l-.3-1.8a7.8 7.8 0 0 1-1.5-.9l-1.7.7-1.4-2.4 1.4-1.1a7.5 7.5 0 0 1 0-1.8l-1.4-1.1 1.4-2.4 1.7.7a7.8 7.8 0 0 1 1.5-.9l.3-1.8h2.8l.3 1.8a7.8 7.8 0 0 1 1.5.9l1.7-.7 1.4 2.4-1.4 1.1a7.5 7.5 0 0 1 0 1.7Z"/></svg>
                Cài đặt cửa hàng
            </a>
        </nav>
        <div class="space-y-1 border-t border-stone-200 px-3 py-4">
            <a href="{{ route('home') }}" class="flex min-h-11 items-center gap-3 px-3 text-sm text-stone-600 transition-colors hover:bg-[#f8f7f4] hover:text-ink">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5z"/><path d="M9 21v-8h6v8"/></svg>
                Xem cửa hàng
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="flex min-h-11 w-full items-center gap-3 px-3 text-left text-sm text-stone-600 transition-colors hover:bg-[#f8f7f4] hover:text-clay">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M10 4H5.5A1.5 1.5 0 0 0 4 5.5v13A1.5 1.5 0 0 0 5.5 20H10M15 16l4-4-4-4M19 12H9"/></svg>
                Đăng xuất
            </button></form>
        </div>
    </aside>

    <div x-cloak x-show="menuOpen" x-transition.opacity class="fixed inset-0 z-30 bg-ink/40 md:hidden" @click="menuOpen = false" aria-hidden="true"></div>
    <div class="min-h-screen md:pl-64">
        <header class="sticky top-0 z-20 flex h-14 items-center gap-4 border-b border-stone-300 bg-white px-4 sm:px-6 md:hidden">
            <button type="button" class="grid h-10 w-10 place-items-center text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-ink" @click="menuOpen = !menuOpen" :aria-expanded="menuOpen" aria-label="Mở hoặc đóng menu">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2 font-display text-base tracking-[.08em]">
                @if($storeSettings->logo_url)<img src="{{ $storeSettings->logo_url }}" alt="" class="h-8 w-8 shrink-0 rounded-sm border border-stone-200 object-contain p-0.5">@endif
                <span class="max-w-[190px] truncate">{{ $storeSettings->shop_name }} <span class="text-clay">STUDIO</span></span>
            </a>
        </header>
        <main class="mx-auto max-w-[1440px] px-5 py-7 sm:px-8 sm:py-10 lg:px-12">
            @if(session('status'))@include('components.toast', ['message' => session('status')])@endif
            @if($errors->any())@include('components.toast', ['message' => $errors->first(), 'type' => 'error'])@endif
            @yield('content')
        </main>
    </div>
</body>
</html>

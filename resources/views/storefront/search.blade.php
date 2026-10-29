@extends('layouts.store')
@section('title', 'Tìm kiếm sản phẩm')
@section('content')
<section class="container-page py-12 sm:py-20">
    <div class="mx-auto max-w-4xl">
        <p class="eyebrow text-clay">Khám phá {{ $storeSettings->shop_name }}</p>
        <h1 class="mt-3 font-display text-4xl leading-tight sm:text-6xl">Bạn đang tìm gì?</h1>
        <p class="mt-4 max-w-xl text-sm leading-6 text-stone-600">Nhập tên sản phẩm, mã sản phẩm hoặc từ khóa để tìm trong bộ sưu tập.</p>

        <form method="GET" action="{{ route('search') }}" role="search" class="mt-8 flex flex-col gap-3 border-b border-ink pb-3 sm:flex-row sm:items-center">
            <label class="sr-only" for="product-search">Từ khóa tìm kiếm</label>
            <div class="flex min-w-0 flex-1 items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-stone-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
                <input id="product-search" class="min-h-12 w-full border-0 bg-transparent px-0 text-base text-ink placeholder:text-stone-400 focus:ring-0" type="search" name="q" value="{{ $term }}" placeholder="Ví dụ: áo linen, AT-1001" autocomplete="off" autofocus>
            </div>
            <button class="button-primary min-h-11 w-full shrink-0 sm:w-auto" type="submit">Tìm kiếm</button>
        </form>

        @if($term === '')
            <div class="mt-10 border-l-2 border-clay pl-5">
                <p class="font-display text-xl">Bắt đầu với một từ khóa</p>
                <p class="mt-2 text-sm leading-6 text-stone-600">Bạn có thể tìm theo tên, mã sản phẩm hoặc chất liệu.</p>
                <a class="mt-4 inline-flex text-xs font-semibold uppercase tracking-[.12em] text-ink underline decoration-stone-400 underline-offset-4 transition-colors hover:text-clay" href="{{ route('products.index') }}">Xem tất cả sản phẩm <span class="ml-2" aria-hidden="true">↗</span></a>
            </div>
        @else
            <div class="mt-10 flex flex-wrap items-baseline justify-between gap-2 border-b border-stone-300 pb-4">
                <h2 class="font-display text-2xl">Kết quả cho “{{ $term }}”</h2>
                <p class="text-xs text-stone-500">{{ number_format($products->total()) }} sản phẩm</p>
            </div>
            @if($products->isNotEmpty())
                <div class="mt-7 grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-3 md:gap-x-6 lg:grid-cols-4">
                    @foreach($products as $product)@include('storefront._product-card', ['product' => $product])@endforeach
                </div>
                <div class="mt-12">{{ $products->links() }}</div>
            @else
                <div class="py-16 text-center sm:py-24">
                    <p class="font-display text-3xl">Chưa tìm thấy sản phẩm phù hợp.</p>
                    <p class="mt-3 text-sm text-stone-600">Thử từ khóa khác hoặc xem toàn bộ bộ sưu tập.</p>
                    <a href="{{ route('products.index') }}" class="button-outline mt-6">Khám phá sản phẩm</a>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

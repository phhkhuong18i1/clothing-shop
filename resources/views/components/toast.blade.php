@props(['message', 'type' => 'success'])
<div data-toast class="fixed right-4 top-20 z-[100] mx-0 flex w-[calc(100%-2rem)] max-w-md items-start gap-3 rounded-md border p-4 shadow-lg shadow-ink/10 sm:right-6 sm:top-6 {{ $type === 'error' ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50' }}" role="{{ $type === 'error' ? 'alert' : 'status' }}" aria-live="{{ $type === 'error' ? 'assertive' : 'polite' }}">
    <span class="mt-0.5 shrink-0 {{ $type === 'error' ? 'text-red-700' : 'text-green-700' }}" aria-hidden="true">
        @if($type === 'error')
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3h.01"/></svg>
        @else
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg>
        @endif
    </span>
    <p class="min-w-0 flex-1 text-sm leading-6 {{ $type === 'error' ? 'text-red-900' : 'text-green-900' }}">{{ $message }}</p>
    <button type="button" data-toast-close class="grid h-8 w-8 shrink-0 place-items-center rounded-sm text-stone-500 transition-colors hover:bg-stone-100 hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" aria-label="Đóng thông báo">
        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="m5 5 10 10M15 5 5 15"/></svg>
    </button>
</div>

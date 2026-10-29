<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerReviewController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['status' => ['nullable', 'in:pending,published,all']]);
        $reviews = CustomerReview::with('product')
            ->when(($filters['status'] ?? 'pending') === 'pending', fn ($query) => $query->where('is_published', false))
            ->when(($filters['status'] ?? 'pending') === 'published', fn ($query) => $query->where('is_published', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'filters'));
    }

    public function update(Request $request, CustomerReview $review): RedirectResponse
    {
        $data = $request->validate(['is_published' => ['required', 'boolean']]);
        $review->update($data);

        return back()->with('status', $review->is_published ? 'Đã duyệt đánh giá.' : 'Đã ẩn đánh giá.');
    }

    public function destroy(CustomerReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Đã xóa đánh giá.');
    }
}

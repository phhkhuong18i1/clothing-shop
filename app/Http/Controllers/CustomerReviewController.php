<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_published, 404);

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:80'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:1500'],
        ]);

        $product->reviews()->create($data + ['is_published' => false]);

        return to_route('products.show', $product)
            ->with('status', 'Cảm ơn bạn đã gửi đánh giá. Đánh giá sẽ hiển thị sau khi được cửa hàng duyệt.')
            ->withFragment('reviews');
    }
}

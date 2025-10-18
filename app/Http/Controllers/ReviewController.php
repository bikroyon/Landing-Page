<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
        public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Optional search by user or product
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                  ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('reviews/Index', [
            'reviews' => $reviews,
            'filters' => $request->only('search'),
        ]);
    }
public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $user = Auth::user();


    // Check if user has completed order for this product
$hasCompletedOrder = DB::table('orders')
    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
    ->where('orders.user_id', $user->id)
    ->where('orders.status', 'completed')
    ->where('order_items.product_id', $product->id)
    ->exists();


    if (!$hasCompletedOrder) {
        return response()->json(['message' => 'You can only review after purchase completion.'], 403);
    }

    $review = $product->reviews()->updateOrCreate(
        ['user_id' => $user->id],
        ['rating' => $request->rating, 'comment' => $request->comment]
    );

    return response()->json(['message' => 'Review submitted successfully!', 'review' => $review]);
}
   public function togglePublish(Review $review)
    {
        $review->is_published = !$review->is_published;
        $review->save();

        return redirect()->back()->with('success', 'Review status updated!');
    }
        public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
    
public function myReviews()
{
    $user = Auth::user();

    $orders = Order::with(['items.product.reviews' => function ($q) use ($user) {
        $q->where('user_id', $user->id);
    }])
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->latest()
        ->get();

    return Inertia::render('reviews/MyReviews', [
        'orders' => $orders,
    ]);
}
public function update(Request $request, Review $review)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $user = Auth::user();

    if ($review->user_id !== $user->id) {
        abort(403, 'Unauthorized');
    }

    $review->update([
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json(['message' => 'Review updated successfully!', 'review' => $review]);
}

}

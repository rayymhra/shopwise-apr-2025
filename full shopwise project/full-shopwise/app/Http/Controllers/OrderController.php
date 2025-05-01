<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem; // Don't forget to use the CartItem model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use Auth facade for consistency

class OrderController extends Controller
{
    public function store(Request $r)
    {
        $user = Auth::user(); // Get the authenticated user

        if (!$user) {
            return response(['message' => 'Unauthorized'], 401);
        }

        // 1. Get the user's cart items with their products
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        // 2. Calculate the total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }

        // 3. Create the order with the calculated total
        $order = Order::create([
            'user_id' => $user->id,
            'address' => $r->address,
            'total' => $totalAmount, // Use the calculated total
            'status' => 'pending'
        ]);

        // 4. Create order items from the cart items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price, // Use the price from the product at the time of order
            ]);
        }

        // 5. Clear the user's cart after checkout
        $user->cartItems()->delete();

        return response(['message' => 'Checkout sukses']);
    }

    public function history(Request $r)
    {
        return $r->user()->orders()->with('orderItems')->get();
    }
}
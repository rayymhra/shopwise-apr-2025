<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $carts = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        $total = 0;
        foreach ($carts as $cart) {
            $total += $cart->product->price * $cart->quantity;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'status' => 'pending',
            'address' => $validated['address'],
            'phone' => $validated['phone'],
        ]);

        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);

            $cart->product->decrement('stock', $cart->quantity);
        }

        Cart::where('user_id', auth()->id())->delete();

        return response()->json(['message' => 'Checkout successful.', 'order' => $order], 201);
    }

    public function index(Request $request)
    {
        $orders = Order::with('orderItems.product')->where('user_id', auth()->id())->get();
        return response()->json($orders, 200);
    }
}

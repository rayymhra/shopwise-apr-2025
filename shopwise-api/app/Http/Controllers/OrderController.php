<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $r)
    {
        $order = Order::create([
            'user_id' => $r->user()->id,
            'address' => $r->address,
            'total' => $r->total,
            'status' => 'pending'
        ]);

        foreach ($r->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Kosongkan keranjang setelah checkout
        $r->user()->cartItems()->delete();

        return response(['message' => 'Checkout sukses']);
    }

    public function history(Request $r)
    {
        return $r->user()->orders()->with('orderItems')->get();
    }
}

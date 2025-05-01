<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $r)
    {
        return $r->user()->cartItems()->with('product')->get();
    }

    public function add(Request $r)
    {
        $r->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer'
        ]);

        return $r->user()->cartItems()->create([
            'product_id' => $r->product_id,
            'quantity' => $r->quantity
        ]);
    }

    public function delete($id)
    {
        CartItem::findOrFail($id)->delete();
        return response(['message' => 'Item dihapus']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::with('product')->where('user_id', $request->user()->id)->get();
        return response()->json($carts, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cart) {
            $cart->quantity += $validated['quantity'];
            $cart->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json(['message' => 'Product added to cart successfully.'], 200);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cart->delete();

        return response()->json(['message' => 'Cart item deleted successfully.'], 200);
    }
}

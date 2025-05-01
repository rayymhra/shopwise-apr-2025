<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            return CartItem::with('product')->where('user_id', $user->id)->get();
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:10', // Adjust max as needed
        ]);

        $productId = $request->product_id;
        $quantityToAdd = $request->input('quantity', 1); // Default to 1 if not provided

        // Check if the product is already in the user's cart
        $existingCartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            // If it exists, increment the quantity
            $existingCartItem->quantity += $quantityToAdd;
            $existingCartItem->save();
            return response()->json(['message' => 'Cart updated'], 200);
        } else {
            // If it doesn't exist, create a new cart item
            $cartItem = new CartItem();
            $cartItem->user_id = $user->id;
            $cartItem->product_id = $productId;
            $cartItem->quantity = $quantityToAdd;
            $cartItem->save();
            return response()->json(['message' => 'Product added to cart'], 201);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $cartItem->delete();
        return response()->json(['message' => 'Product removed from cart'], 200);
    }
}
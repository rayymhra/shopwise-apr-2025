<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        return response()->json(['message' => 'Product created successfully.', 'data' => $product], 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'), 200);
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($validated);

        return response()->json(['message' => 'Product updated successfully.', 'data' => $product], 200);
    }

    public function destroy(Product $product)
    {
        // if (auth()->user()->role !== 'admin') {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
}

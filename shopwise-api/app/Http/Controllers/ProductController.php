<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'category_id' => 'required',
        ]);
        return Product::create($r->all());
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Request $r, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($r->all());
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response(['message' => 'Produk dihapus']);
    }
}

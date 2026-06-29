<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $products = Product::all();

        return response()->json([
            'data' => $products
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama_produk' => 'required',

            'harga' => 'required|numeric'
        ]);

        $product = Product::create($validated);

        return response()->json([

            'message' => 'Product berhasil ditambahkan',

            'data' => $product
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PRODUCT
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'data' => $product
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $product = Product::findOrFail($id);

        $validated = $request->validate([

            'nama_produk' => 'required',

            'harga' => 'required|numeric'
        ]);

        $product->update($validated);

        return response()->json([

            'message' => 'Product berhasil diupdate',

            'data' => $product
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'Product berhasil dihapus'
        ]);
    }
}
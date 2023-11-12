<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json(['product' => $product], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer',
            'category' => 'required|string',
            'brand' => 'required|string',
            'rating' => 'required|string', // Update to accept string
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $img = $request->file('img');
        $imgName = 'product_' . time() . '.' . $img->getClientOriginalExtension();
        $img->storeAs('products', $imgName, 'public');

        $product = Product::create([
            'img' => 'storage/products/' . $imgName,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'qty' => $request->input('qty'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'rating' => $request->input('rating'),
        ]);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'img' => 'image|mimes:jpeg,png,jpg,gif',
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric',
            'qty' => 'integer',
            'category' => 'string',
            'brand' => 'string',
            'rating' => 'string', // Update to accept string
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        if ($request->hasFile('img')) {
            // Handle image update
            $img = $request->file('img');
            $imgName = 'product_' . time() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('products', $imgName, 'public');
            $product->img = 'storage/products/' . $imgName;
        }

        $product->name = $request->input('name', $product->name);
        $product->description = $request->input('description', $product->description);
        $product->price = $request->input('price', $product->price);
        $product->qty = $request->input('qty', $product->qty);
        $product->category = $request->input('category', $product->category);
        $product->brand = $request->input('brand', $product->brand);
        $product->rating = $request->input('rating', $product->rating);
        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

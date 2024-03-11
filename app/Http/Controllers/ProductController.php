<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'products' => Product::all()
        ]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->json()->all();

        $product = Product::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'cost' => $data['cost']
        ]);

        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Product added',
            'product' => $product
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Product shown',
            'product' => $product
        ]);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->json()->all();

        $product->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'cost' => $data['cost'],
        ]);

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Product updated',
            'product' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'code' => 204,
            'message' => 'Product deleted',
        ]);
    }
}

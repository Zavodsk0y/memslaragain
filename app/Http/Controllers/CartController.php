<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        $cartProducts = $cart->cartProducts;

        $cartProducts = $cartProducts->each(function ($cartProducts) {
           Arr::except($cartProducts, ['cart_id']);
        });

        return response()->json([
            'products' => $cartProducts
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        if (!$user->cart) Cart::create(['user_id' => Auth::id()]);

        $cartProduct = CartProduct::create([
            'cart_id' => $user->cart->id,
            'product_id' => $product->id
        ]);

        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Product added to cart',
            'product' => $cartProduct
        ]);
    }

    public function destroy(Request $request, CartProduct $cartProduct)
    {
        $user = $request->user();
        $cart = $user->cart;

        CartProduct::where('id', $cartProduct->id)
            ->where('cart_id', $cart->id)
            ->delete();

        return response()->json([
            'success' => true,
            'code' => 204,
            'message' => 'Product removed from cart',
        ]);
    }
}

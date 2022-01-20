<?php

namespace App\Services;

use App\Order;
use App\Product;
use App\RelatedProduct;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function getProductsBySearch($query)
    {
        $products = $this->searchService->searchProducts($query);

        if (auth()->user() && auth()->user()->isClient()) {
            foreach ($products as $product) {
                $product->price = $product->getPriceWithDiscount(auth()->user());
            }
        } else {
            foreach ($products as $product) {
                $product->price = $product->getPriceWithGeneralDiscount();
            }
        }

        if (auth()->user() && auth()->user()->isClient() && session()->has('current_order')) {
            $orderId = session('current_order');
            $order = Order::find($orderId);
            if ($order === null || $order->user_id !== auth()->user()->id) abort(404);

            $products = $order->attachQuantities($products);
        }

        return $products;
    }

    public function getProductWithRelatedProducts($id)
    {
        $product = Product::find($id);
        if ($product === null) {
            return null;
        }

        $product->price = $this->getProductPriceWithDiscount($product);

        $relatedProducts = RelatedProduct::where('product_id', $product->id)->get()->map(function ($rel) {
            $relatedProduct = $rel->getProduct();
            $relatedProduct->price = $this->getProductPriceWithDiscount($relatedProduct);
            return $relatedProduct;
        });

        if (auth()->user() && auth()->user()->isClient() && session()->has('current_order')) {
            $orderID = session('current_order');
            $order = Order::find($orderID);
            if ($order === null || $order->user_id !== auth()->user()->id) {
                return null;
            }

            $relatedProducts = $order->attachQuantities($relatedProducts);
            $product = $order->attachQuantity($product);
        }

        return [$product, $relatedProducts];
    }

    public function add(Request $request)
    {
        $product = new Product;
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->unit = $request->input('unit');
        $product->price = $request->input('price');
        $product->currency = $request->input('currency');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->save();
        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->unit = $request->input('unit');
        $product->price = $request->input('price');
        $product->currency = $request->input('currency');
        $product->save();
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product === null) {
            return null;
        }
        $redirectUrl = $product->subcategory->getDisplayUrl();
        $product->safeDelete();
        return $redirectUrl;
    }

    private function getProductPriceWithDiscount($product)
    {
        if (auth()->user() && auth()->user()->isClient()) {
            return $product->getPriceWithDiscount(auth()->user());
        }
        return $product->getPriceWithGeneralDiscount();
    }
}

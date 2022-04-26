<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\RelatedProduct;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(ParameterService $parameterService)
    {
        $this->parameterService = $parameterService;
    }

    public function all()
    {
        return Product::all();
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function getProductsBySearch($query)
    {
        $products = $this->searchProducts($query);
        return $this->prepareProductList($products);
    }

    public function getProductsByParametersAndUsage($parameters, $usage)
    {
        $products = $this->parameterService->getProductsByParametersAndUsage($parameters, $usage);
        return $this->prepareProductList($products);
    }

    public function getProductsByCategory($id)
    {
        $products = Product::where('category_id', $id)->get();
        return $this->prepareProductList($products);
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

    public function searchProducts($query) 
    {
        $sanitized_query = preg_replace("/[^A-Za-z0-9 ]/", '', $query);
        if (strlen($sanitized_query) == 0) {
            return [];
        }
        $products = Product::whereRaw("MATCH (code, name, description) AGAINST (? IN BOOLEAN MODE)", $sanitized_query)
            ->take(config('custom.search.results_limit'))->get();
        return $products;
    }

    public function add(Request $request)
    {
        $product = new Product;
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->unit = $request->input('unit');
        $product->price = $request->input('price');
        $product->currency = $request->input('currency');
        $product->category_id = $request->input('category_id');
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
        $redirectUrl = $product->category->getDisplayUrl();
        $product->safeDelete();
        return $redirectUrl;
    }

    public function storeRelatedProducts(Request $request)
    {
        $product = Product::find($request->input('product'));
        if ($product === null) return null;

        RelatedProduct::where('product_id', $product->id)->delete();

        $allProducts = Product::all();
        $inputs = $request->all();
        foreach ($allProducts as $prod) {
            if (array_key_exists($prod->id, $inputs)) {
                $relatedProduct = new RelatedProduct;
                $relatedProduct->product_id = $product->id;
                $relatedProduct->related_product_id = $prod->id;
                $relatedProduct->save();
            }
        }

        return $product;
    }

    public function getRelatedProducts($productId)
    {
        return RelatedProduct::where('product_id', $productId)->get();
    }

    private function prepareProductList($products)
    {
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
            if ($order === null || $order->user_id !== auth()->user()->id) {
                return null;
            }

            $products = $order->attachQuantities($products);
        }

        return $products;
    }

    private function getProductPriceWithDiscount($product)
    {
        if (auth()->user() && auth()->user()->isClient()) {
            return $product->getPriceWithDiscount(auth()->user());
        }
        return $product->getPriceWithGeneralDiscount();
    }
}

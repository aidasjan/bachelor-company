<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class RelatedProductsController extends Controller
{
    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $product = $this->productService->storeRelatedProducts($request);
            if ($product === null) abort(400);
            return redirect('/products' . '/' . $product->id);
        } else abort(404);
    }

    private function validateStoreRequest(Request $request)
    {
        $this->validate($request, [
            'product' => 'required|numeric'
        ]);
    }

    public function edit($productId)
    {
        if (auth()->user()->isAdmin()) {
            $product = $this->productService->find($productId);
            if ($product === null) abort(404);

            $allProducts = $this->productService->all();
            $relatedProducts = $this->productService->getRelatedProducts($product->id);

            $data = array(
                'product' => $product,
                'products' => $allProducts,
                'relatedProducts' => $relatedProducts
            );

            return view('pages.related_products.edit')->with($data);
        } else abort(404);
    }
}

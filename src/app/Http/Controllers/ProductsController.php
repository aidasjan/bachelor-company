<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Services\ProductService;

class ProductsController extends Controller
{

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'search']]);
        $this->productService = $productService;
    }

    public function search(Request $request)
    {
        $this->validateSearchRequest($request);
        $query = $request->input('search_query');

        $products = $this->productService->getProductsBySearch($query);

        $data = array(
            'products' => $products,
            'headline' => __('main.search_results')
        );

        return view('pages.products.index')->with($data);
    }

    private function validateSearchRequest($request)
    {
        $this->validate($request, [
            'search_query' => 'required|string|max:512'
        ]);
    }

    public function create($subcategoryId)
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.products.create')->with('subcategory_id', $subcategoryId);
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $product = $this->productService->add($request);
            return redirect($product->subcategory->getDisplayUrl());
        } else abort(404);
    }

    private function validateStoreRequest($request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'unit' => 'required',
            'currency' => 'required',
            'price' => 'required',
            'subcategory_id' => 'required'
        ]);
    }

    public function show($id)
    {
        $result = $this->productService->getProductWithRelatedProducts($id);
        if ($result == null) {
            abort(404);
        }

        [$product, $relatedProducts] = $result;

        $data = array(
            'pageName' => $product->name,
            'pageDescription' => $product->name,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'documents' => $product->files->filter(function ($file) {
                return !$file->isImage();
            }),
            'images' => $product->files->filter(function ($file) {
                return $file->isImage();
            })
        );

        return view('pages.products.show')->with($data);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $product = Product::find($id);
            if ($product === null) {
                abort(404);
            }
            return view('pages.products.edit')->with('product', $product);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateUpdateRequest($request);
            $product = $this->productService->update($request, $id);
            if ($product === null) {
                abort(404);
            }
            return redirect($product->subcategory->getDisplayUrl());
        } else abort(404);
    }

    private function validateUpdateRequest($request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'unit' => 'required',
            'currency' => 'required',
            'price' => 'required'
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $redirectUrl = $this->productService->destroy($id);
            if ($redirectUrl === null) {
                abort(404);
            }
            return redirect($redirectUrl);
        } else abort(404);
    }
}

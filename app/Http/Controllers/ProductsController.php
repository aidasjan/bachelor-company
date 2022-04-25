<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ParameterService;
use App\Services\ProductService;
use App\Services\UsageService;

class ProductsController extends Controller
{

    public function __construct(ProductService $productService, UsageService $usageService, ParameterService $parameterService)
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'search']]);
        $this->productService = $productService;
        $this->usageService = $usageService;
        $this->parameterService = $parameterService;
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

    public function create($categoryId)
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.products.create')->with('categoryId', $categoryId);
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $product = $this->productService->add($request);
            return redirect($product->category->getDisplayUrl());
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
            'category_id' => 'required'
        ]);
    }

    public function show($id)
    {
        $result = $this->productService->getProductWithRelatedProducts($id);
        if ($result == null) {
            abort(404);
        }

        [$product, $relatedProducts] = $result;
        $usages = $this->usageService->all();
        $parameters = $this->parameterService->getParametersByProduct($product);

        $data = array(
            'pageName' => $product->name,
            'pageDescription' => $product->name,
            'product' => $product,
            'parameters' => $parameters,
            'relatedProducts' => $relatedProducts,
            'documents' => $product->files->filter(function ($file) {
                return !$file->isImage();
            }),
            'images' => $product->files->filter(function ($file) {
                return $file->isImage();
            }),
            'usages' => $usages
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

    public function editParameters(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $product = $this->productService->find($id);
            $usage = $this->usageService->find($request->input('usage'));
            if ($product === null || $usage === null) {
                abort(404);
            }
            $parameters = $this->parameterService->getParametersWithProductValues($product, $usage);
            return view('pages.products.parameters.edit')->with(['product' => $product, 'parameters' => $parameters, 'usage' => $usage]);
        } else abort(404);
    }

    public function updateParameters(Request $request, $productId, $usageId)
    {
        if (auth()->user()->isAdmin()) {
            $product = $this->productService->find($productId);
            $usage = $this->usageService->find($usageId);
            if ($product === null || $usage === null) {
                abort(404);
            }
            $this->parameterService->updateProductParameters($request, $product, $usage);
            return redirect($product->category->getDisplayUrl());
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
            return redirect($product->category->getDisplayUrl());
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

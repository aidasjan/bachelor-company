<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Services\ProductService;

class ProductFilesController extends Controller
{
    public function __construct(ProductService $productService, FileService $fileService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
        $this->fileService = $fileService;
    }

    public function create($productId)
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.products.files.create')->with('productId', $productId);
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $product = $this->productService->storeProductFile($request);
            if ($product === null) {
                abort(404);
            }
            return redirect('/products' . '/' . $product->id);
        } else abort(404);
    }

    private function validateStoreRequest(Request $request)
    {
        $allowed_mimes = config('custom.files.product_file.allowed_file_types') ?? '';
        $max_file_size = config('custom.files.product_file.max_file_size') ?? 0;
        $request->validate([
            'product_id' => 'required',
            'product_file' => [
                'required',
                'file',
                'mimes:' . $allowed_mimes,
                'max:' . $max_file_size
            ]
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $file = $this->fileService->find($id);
            if ($file == null || !$file->isProductFile()) {
                abort(404);
            }
            return view('pages.products.files.edit')->with('productFile', $file);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateUpdateRequest($request);
            $file = $this->productService->updateProductFile($request, $id);
            if ($file === null) {
                abort(404);
            }
            return redirect('products/' . $file->products->first()->id);
        } else abort(404);
    }

    private function validateUpdateRequest(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $product = $this->productService->destroyFileById($id);
            if ($product === null) {
                abort(404);
            }
            return redirect('products/' . $product->id);
        } else abort(404);
    }
}

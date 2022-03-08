<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\File;
use App\Services\FileService;

class ProductFilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create($product_id)
    {
        if (auth()->user()->isAdmin()){
            return view('pages.products.files.create')->with('product_id', $product_id);
        }
        else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()){
            $this->validateStoreRequest($request);
            $product = Product::find($request->input('product_id'));
            if ($product == null) { abort(404); }
            $file_service = new FileService;
            $file = $file_service->uploadFile($request->file('product_file'), 'product_file', $request->input('name'), 'public');
            $product->files()->attach($file->id);
            return redirect('/products'.'/'.$product->id);
        }
        else abort(404);
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
                'mimes:'.$allowed_mimes,
                'max:'.$max_file_size
            ]
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()){
            $file = File::find($id);
            if ($file == null || !$file->isProductFile()) {
                abort(404); return;
            }
            return view('pages.products.files.edit') -> with('product_file', $file);
        }
        else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()){
            $this->validateUpdateRequest($request);
            $file = File::find($id);
            if ($file == null || !$file->isProductFile()) {
                abort(404); return;
            }
            $file->name = $request->input('name');
            $file->save();
            return redirect('products/'.$file->products->first()->id);
        }
        else abort(404);
    }

    private function validateUpdateRequest(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()){
            $file = File::find($id);
            if ($file == null || !$file->isProductFile()) { abort(404); }
            $product_id = $file->products->first()->id;
            $file->safeDelete();
            return redirect('products/'.$product_id);
        }
        else abort(404);
    }
}

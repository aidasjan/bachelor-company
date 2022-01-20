<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Product;
use App\OrderProduct;
use App\Order;
use App\File;
use App\Services\FileService;

class SubcategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'showImage']]);
    }

    public function index()
    {
        //
    }

    public function create($category_id)
    {
        if (auth()->user()->isAdmin()){
            return view('pages.subcategories.create')->with('category_id', $category_id);
        }
        else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()){
            $this->validateStoreRequest($request);
            $subcategory = new Subcategory;
            $subcategory->code = $request->input('code');
            $subcategory->name = $request->input('name');
            $subcategory->name_ru = $request->input('name_ru');
            $subcategory->discount = $request->input('discount');
            $subcategory->category_id = $request->input('category_id');
            $subcategory->save();
            return redirect($subcategory->category->getDisplayUrl());
        }
        else abort(404);
    }

    private function validateStoreRequest($request) 
    {
        $this->validate($request, [
            'code' => 'required|unique:subcategories',
            'name' => 'required',
            'name_ru' => 'required',
            'discount' => 'required',
            'category_id' => 'required'
        ]);
    }

    public function show($category_code, $code)
    {
        $subcategory = Subcategory::where('code', $code)->first();
        if ($subcategory === null) abort(404);

        $data = array(
            'pageName' => $subcategory->name,
            'products' => $subcategory->products->sortBy('position'),
            'subcategory' => $subcategory,
            'subcategory_files' => $subcategory->files,
            'headline' => $subcategory->name
        );

        if (auth()->user() && auth()->user()->isClient()){
            $data['discount'] = auth()->user()->getDiscount($subcategory);
            foreach ($data['products'] as $product){
                $product->price = $product->getPriceWithDiscount(auth()->user());
            }
        }
        else {
            $data['discount'] = $subcategory->discount;
            foreach ($data['products'] as $product){
                $product->price = $product->getPriceWithGeneralDiscount();
            }
        }

        if (auth()->user() && auth()->user()->isClient() && session()->has('current_order')){
            $orderID = session('current_order');
            $order = Order::find($orderID);
            if ($order === null || $order->user_id !== auth()->user()->id) abort(404);

            $data['products'] = $order->attachQuantities($data['products']);
        }
            
        return view('pages.products.index')->with($data);
        
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()){
            $subcategory = Subcategory::find($id);
            if ($subcategory === null) abort(404);
            return view('pages.subcategories.edit') -> with('subcategory', $subcategory);
        }
        else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()){
            $this->validateUpdateRequest($request);
            $subcategory = Subcategory::find($id);
            if ($subcategory === null) abort(404);
            $subcategory->name = $request->input('name');
            $subcategory->name_ru = $request->input('name_ru');
            $subcategory->discount = $request->input('discount');
            $subcategory->save();
            return redirect($subcategory->category->getDisplayUrl());
        }
        else abort(404);
    }

    private function validateUpdateRequest($request) 
    {
        $this->validate($request, [
            'name' => 'required',
            'name_ru' => 'required',
            'discount' => 'required'
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()){
            $subcategory = Subcategory::find($id);
            if ($subcategory === null) { abort(404); }
            $redirect_url = $subcategory->category->getDisplayUrl();
            $subcategory->safeDelete();
            return redirect($redirect_url);
        }
        else abort(404);
    }

    public function uploadImage($id)
    {
        if (auth()->user()->isAdmin()) {
            $subcategory = Subcategory::find($id);
            if ($subcategory === null) { abort(404); }
            return view('pages.subcategories.file_upload')->with('subcategory', $subcategory);
        }
        else abort(404);
    }

    public function storeImage(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreImageRequest($request);
            $subcategory = Subcategory::find($id);
            if ($subcategory === null) { abort(404); }
            $file_service = new FileService;
            $file = $file_service->uploadFile($request->file('subcategory_file'), 'subcategory_file', null, 'public');
            $subcategory->files()->attach($file->id);
            return redirect($subcategory->getDisplayUrl());
        }
        else abort(404);
    }

    private function validateStoreImageRequest(Request $request)
    {
        $allowed_mimes = config('custom.files.subcategory_file.allowed_file_types') ?? '';
        $max_file_size = config('custom.files.subcategory_file.max_file_size') ?? 0;
        $request->validate([
            'subcategory_file' => [
                'required',
                'file',
                'mimes:'.$allowed_mimes,
                'max:'.$max_file_size
            ]
        ]);
    }

    public function destroyImage($id)
    {
        if (auth()->user()->isAdmin()){
            $file = File::find($id);
            if ($file == null || !$file->isSubcategoryFile()) { abort(404); }
            $subcategory = $file->subcategories->first();
            $file->safeDelete();
            return redirect($subcategory->getDisplayUrl());
        }
        else abort(404);
    }
}

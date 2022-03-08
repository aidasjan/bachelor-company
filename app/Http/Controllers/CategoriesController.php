<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ProductService;

class CategoriesController extends Controller
{
    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function index()
    {
        $categories = $this->categoryService->index();
        $discountCategories = $this->categoryService->getDiscountCategories();
        $data = array(
            'categories' => $categories,
            'discountCategories' => $discountCategories
        );
        return view('pages.categories.index')->with($data);
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.categories.create')->with('parentId', null);
        } else abort(404);
    }

    public function createChild($parentId)
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.categories.create')->with('parentId', $parentId);
        } else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $this->categoryService->store($request);
            return redirect('/');
        } else abort(404);
    }

    private function validateStoreRequest($request)
    {
        $this->validate($request, [
            'code' => 'required|unique:categories',
            'name' => 'required',
            'name_ru' => 'required',
            'discount' => 'required'
        ]);
    }

    public function show($code)
    {
        $category = $this->categoryService->findByCode($code);
        if ($category === null) {
            abort(404);
        }
        if (count($category->products) > 0) {
            $products = $this->productService->getProductsByCategory($category->id);
            $data = array(
                'pageName' => $category->name,
                'products' => $products->sortBy('position'),
                'category' => $category,
                'categoryFiles' => $category->files,
                'headline' => $category->name
            );

            if (auth()->user() && auth()->user()->isClient()){
                $data['discount'] = auth()->user()->getDiscount($category);
            }
            else {
                $data['discount'] = $category->discount;
            }

            return view('pages.products.index')->with($data);
        } else {
            if ($category === null) abort(404);
            $data = array(
                'pageName' => $category->name,
                'childCategories' => $category->childCategories->sortBy('position'),
                'category' => $category
            );
            return view('pages.categories.show')->with($data);
        }
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = Category::find($id);
            if ($category === null) abort(404);
            return view('pages.categories.edit')->with('category', $category);
        } else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateUpdateRequest($request);
            $category = $this->categoryService->update($request, $id);
            if ($category === null) {
                abort(404);
            }
            return redirect($category->getDisplayUrl());
        } else abort(404);
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
        if (auth()->user()->isAdmin()) {
            $redirectUrl = $this->categoryService->destroy($id);
            return redirect($redirectUrl);
        } else abort(404);
    }

    public function uploadImage($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = Category::find($id);
            if ($category === null) {
                abort(404);
            }
            return view('pages.categories.file_upload')->with('category', $category);
        } else abort(404);
    }

    public function storeImage(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreImageRequest($request);
            $category = $this->categoryService->storeImage($request->file('category_file'), $id);
            return redirect($category->getDisplayUrl());
        } else abort(404);
    }

    private function validateStoreImageRequest(Request $request)
    {
        $allowedMimes = config('custom.files.category_file.allowed_file_types') ?? '';
        $maxFileSize = config('custom.files.category_file.max_file_size') ?? 0;
        $request->validate([
            'category_file' => [
                'required',
                'file',
                'mimes:' . $allowedMimes,
                'max:' . $maxFileSize
            ]
        ]);
    }

    public function destroyImage($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = $this->categoryService->destroyImage($id);
            if ($category == null) {
                abort(404);
            }
            return redirect($category->getDisplayUrl());
        } else abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Services\CategoryService;

class CategoriesController extends Controller
{
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->index();
        $discountCategories = $this->categoryService->getDiscountCategories();
        $data = array(
            'categories'=>$categories,
            'discountCategories'=>$discountCategories
        );
        return view('pages.categories.index')->with($data);
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.categories.create');
        }
        else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateStoreRequest($request);
            $category = new Category;
            $category->code = $request->input('code');
            $category->name = $request->input('name');
            $category->name_ru = $request->input('name_ru');
            $category->save();
            return redirect('/');
        }
        else abort(404);
    }

    private function validateStoreRequest($request) 
    {
        $this->validate($request, [
            'code' => 'required|unique:categories',
            'name' => 'required',
            'name_ru' => 'required'
        ]);
    }

    public function show($code)
    {
        $category = Category::where('code', $code)->first();
        if ($category === null) abort(404);
        $data = array(
            'pageName' => $category->name,
            'subcategories' => $category->categories->sortBy('position'),
            'category' => $category
        );
        return view('pages.subcategories/index')->with($data);
    }

    public function edit($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = Category::find($id);
            if ($category === null) abort(404);
            return view('pages.categories.edit')->with('category', $category);
        }
        else abort(404);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAdmin()) {
            $this->validateUpdateRequest($request);
            $category = Category::find($id);
            if ($category === null) abort(404);
            $category->name = $request->input('name');
            $category->name_ru = $request->input('name_ru');
            $category->save();
            return redirect('/');
        }
        else abort(404);
    }

    private function validateUpdateRequest($request) 
    {
        $this->validate($request, [
            'name' => 'required',
            'name_ru' => 'required'
        ]);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = Category::find($id);
            if ($category === null) abort(404);
            $category->safeDelete();
            return redirect('/');
        }
        else abort(404);
    }
}

<?php

namespace App\Services;

use App\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function index()
    {
        return Category::where('parent_id', null)->orderBy('position')->get();
    }

    public function getDiscountCategories()
    {
        return Category::where('discount', '>', 0)->orderBy('discount', 'desc')->get();
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return view('pages.categories.create');
        } else abort(404);
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
        } else abort(404);
    }

    public function show($code)
    {
        $category = Category::where('code', $code)->first();
        if ($category === null) abort(404);
        $data = array(
            'pageName' => $category->name,
            'subcategories' => $category->subcategories->sortBy('position'),
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
        } else abort(404);
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
        } else abort(404);
    }

    public function destroy($id)
    {
        if (auth()->user()->isAdmin()) {
            $category = Category::find($id);
            if ($category === null) abort(404);
            $category->safeDelete();
            return redirect('/');
        } else abort(404);
    }
}

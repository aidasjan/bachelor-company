<?php

namespace App\Services;

use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;

class CategoryService
{
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        return Category::where('parent_id', null)->orderBy('position')->get();
    }

    public function all()
    {
        return Category::all();
    }

    public function findByCode($code)
    {
        return Category::where('code', $code)->first();
    }

    public function find($id)
    {
        return Category::find($id);
    }

    public function getDiscountCategories()
    {
        return Category::where('discount', '>', 0)->orderBy('discount', 'desc')->get();
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->code = $request->input('code');
        $category->name = $request->input('name');
        $category->name_ru = $request->input('name_ru');
        $category->discount = $request->input('discount');
        $category->parent_id = $request->input('parent_id');
        $category->save();
        return $category;
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category === null) return null;
        $category->name = $request->input('name');
        $category->name_ru = $request->input('name_ru');
        $category->discount = $request->input('discount');
        $category->save();
        return $category;
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category === null) return null;
        $redirectUrl = $category->parentCategory ? $category->parentCategory->getDisplayUrl() : '/';
        $category->safeDelete();
        return $redirectUrl;
    }

    public function storeImage($requestFile, $id)
    {
        $category = Category::find($id);
        if ($category === null) {
            abort(404);
        }
        $file = $this->fileService->uploadFile($requestFile, 'category_file', null, 'public');
        $category->files()->attach($file->id);
        return $category;
    }

    public function destroyImage($id)
    {
        $file = File::find($id);
        if ($file == null || !$file->isCategoryFile()) {
            return null;
        }
        $category = $file->categories->first();
        $file->safeDelete();
        return $category;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_files');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_files');
    }

    public function isImage()
    {
        $imageMimeTypes = collect(['image/jpg', 'image/jpeg', 'image/png']);
        return $imageMimeTypes->contains($this->file_mime_type);
    }

    public function isProductFile()
    {
        return $this->products->count() === 1;
    }

    public function isCategoryFile()
    {
        return $this->categories->count() === 1;
    }

    public function getNameWithExtension()
    {
        return $this->name . '.' . $this->file_extension;
    }

    public function safeDelete()
    {
        $this->products()->detach();
        $this->categories()->detach();
        $fileService = new FileService;
        $fileService->deleteFile($this->id);
    }
}

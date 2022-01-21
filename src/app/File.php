<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\FileService;

class File extends Model
{
    protected $table = 'files';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function products() {
        return $this->belongsToMany('App\Product', 'product_files');
    }

    public function categories() {
        return $this->belongsToMany('App\Category', 'category_files');
    }

    public function isImage() {
        $image_mime_types = collect(['image/jpg', 'image/jpeg', 'image/png']);
        return $image_mime_types->contains($this->file_mime_type);
    }

    public function isProductFile() {
        return $this->products->count() === 1;
    }

    public function isCategoryFile() {
        return $this->categories->count() === 1;
    }

    public function getNameWithExtension() {
        return $this->name.'.'.$this->file_extension;
    }

    public function safeDelete() {
        $this->products()->detach();
        $this->categories()->detach();
        $fileService = new FileService;
        $fileService->deleteFile($this->id);
    }
}

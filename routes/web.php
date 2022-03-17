<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\CategoriesController@index')->name('index');

// Categories routes
Route::post('categories', 'App\Http\Controllers\CategoriesController@store');
Route::get('categories/create', 'App\Http\Controllers\CategoriesController@create');
Route::get('categories/create/{parent_id}', 'App\Http\Controllers\CategoriesController@createChild');
Route::get('categories/{category_code}', 'App\Http\Controllers\CategoriesController@show');
Route::put('categories/{category}', 'App\Http\Controllers\CategoriesController@update');
Route::delete('categories/{category}', 'App\Http\Controllers\CategoriesController@destroy');
Route::get('categories/{category}/edit', 'App\Http\Controllers\CategoriesController@edit');
Route::get('categories/{category}/images/create', 'App\Http\Controllers\CategoriesController@uploadImage');
Route::post('categories/{category}/images', 'App\Http\Controllers\CategoriesController@storeImage');
Route::delete('categories/{category}/images', 'App\Http\Controllers\CategoriesController@destroyImage');

// Products routes
Route::post('products', 'App\Http\Controllers\ProductsController@store')->name('products.store');
Route::get('products/create/{category}', 'App\Http\Controllers\ProductsController@create')->name('products.create');
Route::get('products/{product}', 'App\Http\Controllers\ProductsController@show')->name('products.show');
Route::put('products/{product}', 'App\Http\Controllers\ProductsController@update')->name('products.update');
Route::delete('products/{product}', 'App\Http\Controllers\ProductsController@destroy')->name('products.destroy');
Route::get('products/{product}/edit', 'App\Http\Controllers\ProductsController@edit')->name('products.edit');

Route::get('search/products', 'App\Http\Controllers\ProductsController@search')->name('products.search');

// Product Files routes
Route::post('product-files', 'App\Http\Controllers\ProductFilesController@store')->name('product_files.store');
Route::get('product-files/create/{product}', 'App\Http\Controllers\ProductFilesController@create')->name('product_files.create');
Route::put('product-files/{product_file}', 'App\Http\Controllers\ProductFilesController@update')->name('product_files.update');
Route::delete('product-files/{product_file}', 'App\Http\Controllers\ProductFilesController@destroy')->name('product_files.destroy');
Route::get('product-files/{product_file}/edit', 'App\Http\Controllers\ProductFilesController@edit')->name('product_files.edit');

// Related Products routes
Route::get('related-products/{product}/edit', 'App\Http\Controllers\RelatedProductsController@edit')->name('related_products.edit');
Route::post('related-products', 'App\Http\Controllers\RelatedProductsController@store')->name('related_products.store');

// Users routes
Route::get('users', 'App\Http\Controllers\UsersController@index')->name('users.index');
Route::get('tutorial', 'App\Http\Controllers\UsersController@showTutorial')->name('users.tutorial');
Route::get('register', 'App\Http\Controllers\UsersController@create')->name('users.create');
Route::post('register', 'App\Http\Controllers\UsersController@store')->name('users.store');

Route::get('login/{id}/{accessToken}', 'App\Http\Controllers\UsersController@login')->name('users.login');
Route::post('logout', 'App\Http\Controllers\UsersController@logout')->name('logout');
Route::get('dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

// Order routes
Route::get('orders', 'App\Http\Controllers\OrdersController@index')->name('orders.index');
Route::get('orders/cancel', 'App\Http\Controllers\OrdersController@cancel')->name('orders.cancel');
Route::get('orders/status/{status}', 'App\Http\Controllers\OrdersController@indexByStatus')->name('orders.indexByStatus');
Route::post('orders', 'App\Http\Controllers\OrdersController@store')->name('orders.store');
Route::post('orders/products', 'App\Http\Controllers\OrdersController@storeOrderProducts');
Route::get('orders/{order}', 'App\Http\Controllers\OrdersController@show')->name('orders.show');
Route::put('orders/{order}', 'App\Http\Controllers\OrdersController@update')->name('orders.update');
Route::delete('orders/{order}', 'App\Http\Controllers\OrdersController@destroy')->name('orders.destroy');
Route::get('orders/{order}/edit', 'App\Http\Controllers\OrdersController@edit')->name('orders.edit');
Route::post('orders/destroy_unsubmitted', 'App\Http\Controllers\OrdersController@destroyUnsubmitted')->name('orders.destroy_unsubmitted');

// Discounts routes
Route::get('discounts', 'App\Http\Controllers\DiscountsController@index')->name('discounts.index');
Route::post('discounts', 'App\Http\Controllers\DiscountsController@store')->name('discounts.store');
Route::post('discounts/all', 'App\Http\Controllers\DiscountsController@storeAll')->name('discounts.store_all');
Route::get('discounts/{user}/edit', 'App\Http\Controllers\DiscountsController@edit')->name('discounts.edit');

// Import routes
Route::get('import/upload/{type}', 'App\Http\Controllers\ImportController@showUploadForm');
Route::post('import/import/{type}', 'App\Http\Controllers\ImportController@importFromFile');

// Reorder routes
Route::get('reorder/{type}/{parent_id}', 'App\Http\Controllers\ReorderController@index');
Route::get('reorder/{type}', 'App\Http\Controllers\ReorderController@index');
Route::post('reorder/{type}/{parent_id}', 'App\Http\Controllers\ReorderController@reorder');
Route::post('reorder/{type}', 'App\Http\Controllers\ReorderController@reorderRoot');

// Files routes
Route::get('files/documents/{file}', 'App\Http\Controllers\FilesController@showDocument');
Route::get('files/images/{file}', 'App\Http\Controllers\FilesController@showImage');

// Backups routes
Route::get('backup/scheduled/{token}', 'App\Http\Controllers\BackupsController@scheduledBackup');

// Locale routes
Route::get('/language/{locale}', 'App\Http\Controllers\LocaleController@changeLocale');

// Privacy policy routes
Route::get('/privacy-policy', function () {
    return view('pages.privacy')->with('pageName', 'Privacy Policy');
});

// Misc routes
Route::get('home', function(){
    return redirect('/');
});
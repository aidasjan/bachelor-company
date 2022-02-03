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

Route::get('/', 'CategoriesController@index')->name('index');

// Categories routes
Route::post('categories', 'CategoriesController@store');
Route::get('categories/create', 'CategoriesController@create');
Route::get('categories/create/{parent_id}', 'CategoriesController@createChild');
Route::get('categories/{category_code}', 'CategoriesController@show');
Route::put('categories/{category}', 'CategoriesController@update');
Route::delete('categories/{category}', 'CategoriesController@destroy');
Route::get('categories/{category}/edit', 'CategoriesController@edit');
Route::get('categories/{category}/images/create', 'CategoriesController@uploadImage');
Route::post('categories/{category}/images', 'CategoriesController@storeImage');
Route::delete('categories/{category}/images', 'CategoriesController@destroyImage');

// Products routes
Route::post('products', 'ProductsController@store')->name('products.store');
Route::get('products/create/{category}', 'ProductsController@create')->name('products.create');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');
Route::put('products/{product}', 'ProductsController@update')->name('products.update');
Route::delete('products/{product}', 'ProductsController@destroy')->name('products.destroy');
Route::get('products/{product}/edit', 'ProductsController@edit')->name('products.edit');

Route::get('search/products', 'ProductsController@search')->name('products.search');

// Product Files routes
Route::post('product-files', 'ProductFilesController@store')->name('product_files.store');
Route::get('product-files/create/{product}', 'ProductFilesController@create')->name('product_files.create');
Route::put('product-files/{product_file}', 'ProductFilesController@update')->name('product_files.update');
Route::delete('product-files/{product_file}', 'ProductFilesController@destroy')->name('product_files.destroy');
Route::get('product-files/{product_file}/edit', 'ProductFilesController@edit')->name('product_files.edit');

// Related Products routes
Route::get('related-products/{product}/edit', 'RelatedProductsController@edit')->name('related_products.edit');
Route::post('related-products', 'RelatedProductsController@store')->name('related_products.store');

// Users routes
Route::get('users', 'UsersController@index')->name('users.index');
Route::get('password', 'UsersController@password')->name('users.password');
Route::post('password', 'UsersController@passwordChange')->name('users.password');
Route::post('users/{user}/reset_password', 'UsersController@resetPassword')->name('users.reset_password');
Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::put('users/{user}', 'UsersController@update')->name('users.update');
Route::delete('users/{order}', 'UsersController@destroy')->name('users.destroy');
Route::get('tutorial', 'UsersController@showTutorial')->name('users.tutorial');

// Registration Routes...
Route::get('register', 'UsersController@create')->name('users.create');
Route::post('register', 'UsersController@store')->name('users.store');

// Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

// Order routes
Route::get('orders', 'OrdersController@index')->name('orders.index');
Route::get('orders/cancel', 'OrdersController@cancel')->name('orders.cancel');
Route::get('orders/status/{status}', 'OrdersController@indexByStatus')->name('orders.indexByStatus');
Route::post('orders', 'OrdersController@store')->name('orders.store');
Route::post('orders/products', 'OrdersController@storeOrderProducts');
Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
Route::put('orders/{order}', 'OrdersController@update')->name('orders.update');
Route::delete('orders/{order}', 'OrdersController@destroy')->name('orders.destroy');
Route::get('orders/{order}/edit', 'OrdersController@edit')->name('orders.edit');
Route::post('orders/destroy_unsubmitted', 'OrdersController@destroyUnsubmitted')->name('orders.destroy_unsubmitted');

// Discounts routes
Route::get('discounts', 'DiscountsController@index')->name('discounts.index');
Route::post('discounts', 'DiscountsController@store')->name('discounts.store');
Route::post('discounts/all', 'DiscountsController@storeAll')->name('discounts.store_all');
Route::get('discounts/{user}/edit', 'DiscountsController@edit')->name('discounts.edit');

// Import routes
Route::get('import/upload/{type}', 'ImportController@showUploadForm');
Route::post('import/import/{type}', 'ImportController@importFromFile');

// Reorder routes
Route::get('reorder/{type}/{parent_id}', 'ReorderController@index');
Route::get('reorder/{type}', 'ReorderController@index');
Route::post('reorder/{type}/{parent_id}', 'ReorderController@reorder');
Route::post('reorder/{type}', 'ReorderController@reorderRoot');

// Files routes
Route::get('files/documents/{file}', 'FilesController@showDocument');
Route::get('files/images/{file}', 'FilesController@showImage');

// Backups routes
Route::get('backup/scheduled/{token}', 'BackupsController@scheduledBackup');


// Locale routes
Route::get('/language/{locale}', function ($locale) {
    if ($locale === 'en' || $locale === 'ru'){
        session(['locale' => $locale]);
        return redirect()->back();
    }
    else abort(404);
});

// Cookies routes
Route::get('/cookies-agree', function () {
    Cookie::queue('cookies_agree', '1', 60*24*365);
    return back();
});

// Privacy policy routes
Route::get('/privacy-policy', function () {
    return view('pages.privacy')->with('pageName', 'Privacy Policy');
});

// Misc routes
Route::get('home', function(){
    return redirect('/');
});
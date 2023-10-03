<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/logout', function(){
    Auth::logout();
    return redirect()->route('home');
 });

Auth::routes();


//HomeController
Route::get('/get-image/{filesystem}/{filename}', [App\Http\Controllers\HomeController::class, 'getImage'])->name('home.getImage');

//CategoryController
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{name}', [App\Http\Controllers\CategoryController::class, 'get'])->name('categories.get');

//ProductController
Route::get('/products/{name}', [App\Http\Controllers\ProductController::class, 'get'])->name('products.get');

//CartController
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/cart/delete/{id}', [App\Http\Controllers\CartController::class, 'delete'])->name('cart.delete');
Route::get('/cart/get-discount/{name}', [App\Http\Controllers\CartController::class, 'getDiscount'])->name('cart.getDiscount');

//OrderController
Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
Route::post('/order/create', [App\Http\Controllers\OrderController::class, 'create'])->name('order.create');

//AccountController
Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
Route::post('/account/update', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
Route::post('/account/update-password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('account.update_password');


/************* ADMIN **********************/
//AdminController
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
Route::get('/admin/get-subcategories/{id}', [App\Http\Controllers\AdminController::class, 'getSubcategories'])->name('admin.getSubcategories');

//UserController
Route::get('/admin/users/get', [App\Http\Controllers\Admin\UserController::class, 'get'])->name('admin.user.get');
Route::post('/admin/users/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
Route::get('/admin/users/verify/{id}', [App\Http\Controllers\Admin\UserController::class, 'verify'])->name('admin.user.verify');
Route::get('/admin/users/ban/{id}', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.user.ban');

//CategoryController
Route::post('/admin/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.category.create');
Route::get('/admin/categories/get', [App\Http\Controllers\Admin\CategoryController::class, 'get'])->name('admin.category.get');
Route::post('/admin/categories/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.category.update');
Route::get('/admin/categories/delete/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('admin.category.delete');

//SubcategoryController
Route::post('/admin/subcategories/create', [App\Http\Controllers\Admin\subcategoryController::class, 'create'])->name('admin.subcategory.create');
Route::get('/admin/subcategories/get', [App\Http\Controllers\Admin\subcategoryController::class, 'get'])->name('admin.subcategory.get');
Route::post('/admin/subcategories/update/{id}', [App\Http\Controllers\Admin\subcategoryController::class, 'update'])->name('admin.subcategory.update');
Route::get('/admin/subcategories/delete/{id}', [App\Http\Controllers\Admin\subcategoryController::class, 'delete'])->name('admin.subcategory.delete');

//ProductController
Route::post('/admin/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.product.create');
Route::get('/admin/products/get', [App\Http\Controllers\Admin\ProductController::class, 'get'])->name('admin.product.get');
Route::post('/admin/products/update/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.product.update');
Route::get('/admin/products/delete/{id}', [App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('admin.product.delete');

//DiscountController
Route::post('/admin/discounts/create', [App\Http\Controllers\Admin\DiscountController::class, 'create'])->name('admin.discount.create');
Route::get('/admin/discounts/get', [App\Http\Controllers\Admin\DiscountController::class, 'get'])->name('admin.discount.get');
Route::post('/admin/discounts/update/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'update'])->name('admin.discount.update');
Route::get('/admin/discounts/delete/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'delete'])->name('admin.discount.delete');

//OrderController
Route::get('/admin/orders/get', [App\Http\Controllers\Admin\OrderController::class, 'get'])->name('admin.order.get');
Route::post('/admin/orders/update/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('admin.order.update');
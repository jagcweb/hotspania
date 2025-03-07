<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
    if(!Auth::check()){
        return redirect()->route('login');
    } else {
        if (\Auth::user()->getRoleNames()[0] == 'admin') {
            return redirect()->route('admin.citychanges');
        } else {
            return redirect()->route('home');
        }
    }
});

Route::get('/logout', function(){
    Auth::logout();
    return redirect()->route('home');
 });

 Auth::routes(['register' => false]);

//HomeController
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/image-get/{filename}',  [App\Http\Controllers\HomeController::class, 'getImage'])->name('home.imageget');
Route::get('/home/gif-get/{filename}',  [App\Http\Controllers\HomeController::class, 'getGif'])->name('home.gifget');
Route::get('/home/load-more/{page}', [App\Http\Controllers\HomeController::class, 'loadMore'])->name('home.loadmore');

//AccountController
Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
Route::get('/account/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('account.edit');
Route::get('/account/edit/edit-data', [App\Http\Controllers\AccountController::class, 'editData'])->name('account.edit-data');
Route::post('/account/edit/update', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
Route::get('/account/{nickname}', [App\Http\Controllers\AccountController::class, 'get'])->name('account.get');
Route::get('/account/images/{image}/setfront', [App\Http\Controllers\AccountController::class, 'setFront'])->name('account.images.setfront');
Route::get('/account/images/{image}/visible', [App\Http\Controllers\AccountController::class, 'visible'])->name('account.images.visible');
Route::get('/account/images/{image}/invisible', [App\Http\Controllers\AccountController::class, 'invisible'])->name('account.images.invisible');
Route::get('/account/load-more/{page}/{userId}', [App\Http\Controllers\AccountController::class, 'loadMore'])
    ->middleware(['web'])
    ->name('account.loadMore');

Route::post('/account/upload-images', [App\Http\Controllers\AccountController::class, 'upload'])->name('account.images.upload');

//RegistersController
Route::middleware('guest')->group(function () {
    Route::get('/register/paso-{step}/{user?}', [App\Http\Controllers\RegisterController::class, 'create'])->name('user.register');
});

Route::post('/register-user/{step}/{id?}', [App\Http\Controllers\RegisterController::class, 'save'])->name('user.save');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

    // City Change Management
    Route::get('city', 'App\Http\Controllers\Admin\CityChangesController@index')->name('admin.citychanges');
    Route::post('city-apply', 'App\Http\Controllers\Admin\CityChangesController@apply')->name('admin.citychanges.apply');

    // User Management
    Route::get('users/create', 'App\Http\Controllers\Admin\UserController@create')->name('admin.users.create');
    Route::get('users/edit/{id}', 'App\Http\Controllers\Admin\UserController@edit')->name('admin.users.edit');
    Route::post('users/save', 'App\Http\Controllers\Admin\UserController@save')->name('admin.users.save');
    Route::post('users/update', 'App\Http\Controllers\Admin\UserController@update')->name('admin.users.update');
    Route::post('users/update/status', 'App\Http\Controllers\Admin\UserController@updateStatus')->name('admin.users.update_status');
    Route::post('users/change-password/{id}', 'App\Http\Controllers\Admin\UserController@updatePassword')->name('admin.users.update_password');
    Route::get('users/getPending', 'App\Http\Controllers\Admin\UserController@getPending')->name('admin.users.getPending');
    Route::get('users/getActive', 'App\Http\Controllers\Admin\UserController@getActive')->name('admin.users.getActive');
    Route::get('users/getRequests', 'App\Http\Controllers\Admin\UserController@getRequests')->name('admin.users.getRequests');
    Route::get('users/getLoginRecords', 'App\Http\Controllers\Admin\UserController@getLoginRecords')->name('admin.users.getLoginRecords');

    // Image Management
    Route::get('images-get/{id}/{name}/{filter}', 'App\Http\Controllers\Admin\ImageController@get')->name('admin.images.getFilter');
    Route::post('images', 'App\Http\Controllers\Admin\ImageController@upload')->name('admin.images.upload');
    Route::get('images/{image}/setfront', 'App\Http\Controllers\Admin\ImageController@setFront')->name('admin.images.setfront');
    Route::get('images/{image}/approve', 'App\Http\Controllers\Admin\ImageController@approve')->name('admin.images.approve');
    Route::get('images/{image}/unapprove', 'App\Http\Controllers\Admin\ImageController@unapprove')->name('admin.images.unapprove');
    Route::get('images/{user}/approveall', 'App\Http\Controllers\Admin\ImageController@approveAll')->name('admin.images.approveall');
    Route::get('images/{user}/unapproveall', 'App\Http\Controllers\Admin\ImageController@approveAll')->name('admin.images.unapproveall');
    Route::get('images/{image}/visible', 'App\Http\Controllers\Admin\ImageController@visible')->name('admin.images.visible');
    Route::get('images/{image}/invisible', 'App\Http\Controllers\Admin\ImageController@invisible')->name('admin.images.invisible');
    Route::get('images/{user}/visibleall', 'App\Http\Controllers\Admin\ImageController@visibleAll')->name('admin.images.visibleall');
    Route::get('images/{user}/invisibleall', 'App\Http\Controllers\Admin\ImageController@invisibleAll')->name('admin.images.invisibleall');
    Route::post('images/profile', 'App\Http\Controllers\Admin\ImageController@uploadProfile')->name('admin.images.uploadProfile');
    Route::get('images/delete-{image}', 'App\Http\Controllers\Admin\ImageController@delete')->name('admin.images.delete');
    Route::get('images/delete/all-{user}', 'App\Http\Controllers\Admin\ImageController@deleteAll')->name('admin.images.deleteall');
    Route::get('images/get-{filename}', 'App\Http\Controllers\Admin\ImageController@getImage')->name('admin.images.get');
    Route::get('images/gif-{filename}', 'App\Http\Controllers\Admin\ImageController@getGif')->name('admin.images.get_gif');

    // Utility Management
    Route::post('utilities/assign-package', 'App\Http\Controllers\Admin\UtilityController@assignPackage')->name('admin.utilities.assign_package');
    Route::post('utilities/city-save', 'App\Http\Controllers\Admin\UtilityController@saveCity')->name('admin.utilities.cities_save');
    Route::get('utilities/city-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteCity')->name('admin.utilities.cities_delete');
    Route::post('utilities/city-update', 'App\Http\Controllers\Admin\UtilityController@updateCity')->name('admin.utilities.cities_update');
    Route::get('utilities/zones', 'App\Http\Controllers\Admin\UtilityController@zones')->name('admin.utilities.zones');
    Route::post('utilities/zone-save', 'App\Http\Controllers\Admin\UtilityController@saveZone')->name('admin.utilities.zones_save');
    Route::get('utilities/zone-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteZone')->name('admin.utilities.zones_delete');
    Route::post('utilities/zone-update', 'App\Http\Controllers\Admin\UtilityController@updateZone')->name('admin.utilities.zones_update');
    Route::get('utilities/tags', 'App\Http\Controllers\Admin\UtilityController@tags')->name('admin.utilities.tags');
    Route::post('utilities/tag-save', 'App\Http\Controllers\Admin\UtilityController@saveTag')->name('admin.utilities.tags_save');
    Route::get('utilities/tag-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteTag')->name('admin.utilities.tags_delete');
    Route::post('utilities/tag-update', 'App\Http\Controllers\Admin\UtilityController@updateTag')->name('admin.utilities.tags_update');
    Route::get('utilities/packages', 'App\Http\Controllers\Admin\UtilityController@packages')->name('admin.utilities.packages');
    Route::post('utilities/package-save', 'App\Http\Controllers\Admin\UtilityController@savePackage')->name('admin.utilities.packages_save');
    Route::get('utilities/package-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deletePackage')->name('admin.utilities.packages_delete');
    Route::post('utilities/package-update', 'App\Http\Controllers\Admin\UtilityController@updatePackage')->name('admin.utilities.packages_update');
    Route::post('utilities/package-user-save', 'App\Http\Controllers\Admin\UtilityController@savePackageUser')->name('admin.utilities.packages_users_save');
    Route::get('utilities/package-user-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deletePackageUser')->name('admin.utilities.packages_users_delete');
    Route::get('utilities/news', 'App\Http\Controllers\Admin\UtilityController@news')->name('admin.utilities.news');
});


//HomeController
Route::get('/privacy-policies', [App\Http\Controllers\HomeController::class, 'privacyPolicies'])->name('home.privacy_policies');

//UserController
Route::get('/admin/users/get', [App\Http\Controllers\Admin\UserController::class, 'get'])->name('admin.user.get');
Route::post('/admin/users/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
Route::get('/admin/users/verify/{id}', [App\Http\Controllers\Admin\UserController::class, 'verify'])->name('admin.user.verify');
Route::get('/admin/users/ban/{id}', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.user.ban');
Route::get('/admin/users/get-pdf/{name_pdf}', [App\Http\Controllers\Admin\UserController::class, 'getPdf'])->name('admin.user.getPdf');

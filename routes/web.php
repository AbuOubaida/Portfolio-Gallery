<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \App\Http\Controllers;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoController;
//Route::get('/', function () {
//    return view('welcome');
//});

// For Gallery Controller
Route::get('/','GalleryController@index');
Route::resource('gallery','GalleryController');
Route::get('gallery/show/{id}','GalleryController@show');
Route::get('gallery/create',[GalleryController::class,'create']);
//Route::name('edit')->get('gallery/edit/{id}','GalleryController@edit');
Route::post('gallery/edit/{id}',[GalleryController::class,'update'])->name('gallery.edit');
Route::get('gallery/edit/{id}', [GalleryController::class,'edit'])->name('edit');
Route::get('gallery/destroy/{id}',[GalleryController::class,'destroy']);
######################################################################

// For Photo Controller
Route::resource('photo','PhotoController');
Route::get('profile/photo/create/{id}','PhotoController@create');
Route::get('photo/details/{id}','PhotoController@details');
Route::get('profile/photo/destroy/{id}/{gallery_id}','PhotoController@destroy');
Route::get('profile/photo/edit/{id}','PhotoController@edit');
Route::name('details')->get('details/{id}','PhotoController@details');
Route::post('update','PhotoController@update');
Route::post('photo/details/evolution/{id}',[PhotoController::class,'evolution'])->name('evolution');
Route::post('photo/details/comment/{id}',[PhotoController::class,'commentSet'])->name('comment');
Route::get('photo/details/delete-comment/{id}',[PhotoController::class,'deleteComment']);
######################################################################

Auth::routes();
//Route for logout
Route::get('logout',[LoginController::class,'logout']);
Route::get('home', [HomeController::class, 'index'])->name('home');

//Route for Profile
Route::resource('profile','ProfileController');
Route::get('profile',[ProfileController::class,'index']);
Route::get('profile/edit/{id}',[ProfileController::class,'edit']);
Route::post('profile/update',[ProfileController::class,'update'])->name('profile.update');
Route::post('profile/updatePassword',[ProfileController::class,'updatePassword'])->name('profile.updatePassword');
Route::get('profile/gallery/show/{id}',[ProfileController::class,'show'])->name('profileGalleryShow');
Route::get('profile/photo/details/{id}',[ProfileController::class,'PhotoDetails'])->name('profilePhotoShow');
Route::post('profile/photo/details/evolution/{id}',[PhotoController::class,'evolution'])->name('evolution');
Route::post('profile/photo/details/comment/{id}',[PhotoController::class,'commentSet'])->name('comment');
Route::get('profile/edit/change-password/{id}',[ProfileController::class,'changePassword']);
Route::get('profile/photo/details/delete-comment/{id}',[PhotoController::class,'deleteComment']);



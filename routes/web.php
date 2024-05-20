<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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
    return view('welcome');
});
Route::get('/auth/register', [AuthController::class, 'showFormRegister']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/login', [AuthController::class, 'showFormLogin']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/home', [HomeController::class, 'showHome'])->name('home')->middleware(['auth']);
Route::prefix('admin')->middleware('admin.permission')->group(function() {

    Route::get('/show_users', [AdminController::class,'index'])->name('admin.index');
    Route::get('/create_user', [AdminController::class,'create'])->name('admin.create');
    Route::get('/show_user', [AdminController::class,'index'])->name('admin.index');
    Route::get('/update_user', [AdminController::class,'index'])->name('admin.index');
    Route::get('/delete_user', [AdminController::class,'index'])->name('admin.index');
});
// Route::get('/home' , [HomeController::class, 'showHome'])->name('homeAdmin')->middleware('admin.permission');

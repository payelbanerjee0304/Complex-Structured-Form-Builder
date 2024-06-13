<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\MainController;
Route::get('/insert',[MainController::class,'insert']);
Route::post('/submit',[MainController::class,'submit']);
Route::get('/alldata',[MainController::class,'alldata']);
Route::get('/logout',[MainController::class,'logout']);
Route::get('/delete{del}',[MainController::class,'delete']);
Route::get('/edit{ep}',[MainController::class,'edit']);
Route::post('/editaction',[MainController::class,'editaction']);
Route::get('/login',[MainController::class,'login']);
Route::post('/loginaction',[MainController::class,'loginaction']);
Route::get('/displayclient{dp}',[MainController::class,'displayclient']);


Route::get('/create',[MainController::class,'create'])->name('admin.create');
Route::post('/questions', [MainController::class, 'store1']);
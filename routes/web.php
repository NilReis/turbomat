<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChapaController;
use App\Http\Controllers\ChapaItemController;
use App\Http\Controllers\ReservedItemController;


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
// Dentro do arquivo web.php
Route::get('/chapa-items/search', [ChapaItemController::class, 'search'])->name('chapaItems.search');
Route::resource('chapas/itens-reservados', ReservedItemController::class)->names('chapas.itens-reservados');
Route::resource('reserved-items', ReservedItemController::class);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {
        Route::resource('chapas', ChapaController::class);
        Route::resource('chapa-items', ChapaItemController::class);
    });




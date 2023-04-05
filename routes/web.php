<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodosController;

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

Route::controller(TodosController::class)->prefix('todos')->name('todos.')->group(function () {
    Route::get('index', 'index')->name('index');
    Route::post('add', 'add')->name('add');
    Route::match(['post', 'get'], 'edit/{id}', 'edit')->name('edit');
    Route::delete('delete/{id}', 'delete')->name('delete');
});

<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\ItemController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});




Route::resource('items', ItemController::class)->except(['create', 'edit', 'show']);
Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);
Route::resource('employees', EmployeeController::class)->except(['create', 'edit', 'show']);
Route::resource('stores', StoreController::class)->except(['create', 'edit', 'show']);
Route::resource('registers', RegisterController::class)->except(['create', 'edit', 'show']);

// Categories (Manual Routes for Composite Key)
Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('categories/update', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');

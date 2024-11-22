<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'showForm'])->name('product.form');
Route::post('/save-product', [ProductController::class, 'saveProduct'])->name('product.save');
Route::get('/edit-product/{index}', [ProductController::class, 'editProduct'])->name('product.edit');
Route::post('/update-product/{index}', [ProductController::class, 'updateProduct'])->name('product.update');


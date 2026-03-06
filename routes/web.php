<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::resource('items', ItemController::class)->except(['show']);
Route::get('history', [ItemController::class, 'history'])->name('items.history');
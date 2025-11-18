<?php

use Illuminate\Support\Facades\Route;
use Qisti\UploadMultipleUi\Http\Controllers\UploadMultipleUiController;

Route::group([
    'middleware' => config('uploadmultipleui.middleware', ['web']),
    'prefix' => config('uploadmultipleui.route_prefix', 'upload-multiple-ui'),
], function () {
    Route::get('/', [UploadMultipleUiController::class, 'index'])->name('uploadmultipleui.form');
    Route::post('/', [UploadMultipleUiController::class, 'store'])->name('uploadmultipleui.store');
});

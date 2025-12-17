<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::prefix('v1')->group(function () {
    Route::apiResource('customers', CustomerController::class);
});

<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () { // this route will be executed when no other route matches the incoming request.
    return response()->json([
        'status' => false,
        'message' => 'The resource was not found!',
    ], 404);
})->name('web.fallback.404');


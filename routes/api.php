<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::get('/users', function () {
    return response()->json(['users' => User::all()]);
});

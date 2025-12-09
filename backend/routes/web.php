<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'message' => 'Todo API is running',
        'version' => '1.0.0',
    ];
});

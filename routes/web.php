<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return response()->json(['message' => "Healthy success"], Response::HTTP_OK);
});

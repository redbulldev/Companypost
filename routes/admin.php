<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Companypost\Http\Controllers\Admin\PostController;


Route::group(['prefix' => 'admin', 'middleware' => ['web', 'CheckLogedOut']], function(){
    Route::Resource('/post', PostController::class);
});

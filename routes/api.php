<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/',function(){
    return response()->json([
        'success' => true,
        'message' => 'api under construction'
    ], 200);
});


Route::prefix('v1')->group(function () {
    //get current user
    Route::get('user', [AuthController::class,'user']);

    //Auth routes
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    //logout
    Route::post('logout', [AuthController::class,'logout']);
});
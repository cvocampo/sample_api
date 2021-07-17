<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [LoginController::class, 'login']);
Route::post('/invite/{email}', [InvitationController::class,'sendSixDigitCode']);
Route::post('/activate/{email}',[InvitationController::class,'activateAccount']);


Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::post('/logout',[LogoutController::class,'logout']);

    //Routes for authenticated admin accounts
    Route::group(['middleware' => ['role:admin']], function () {
        Route::post('/invite',[InvitationController::class, 'store']);
    });


    //Routes for authenticated users
    Route::group(['middleware' => ['role:user','activated']], function () {
        Route::post('/update',[ProfileController::class,'updateProfile']);
    });



});

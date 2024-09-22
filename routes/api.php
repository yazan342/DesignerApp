<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user-info', [AuthController::class, 'getUserInfo']);
    Route::post('/user-update', [AuthController::class, 'updateUser']);




    Route::get("/homepage", [HomeController::class, 'getHomePage']);



    Route::get('/designer/{id}', [AuthController::class, 'getDesigner']);
    Route::get('/categories', [DesignerController::class, 'getCategories']);
    Route::get('/colors', [DesignerController::class, 'getColors']);
    Route::get('/sizes', [DesignerController::class, 'getSizes']);


    Route::get('/design/{id}', [DesignerController::class, 'getDesign']);
    Route::post('/design-create', [DesignerController::class, 'createDesign']);
    Route::post('/design-update/{id}', [DesignerController::class, 'updateDesign']);
    Route::delete('/design-delete/{id}', [DesignerController::class, 'deleteDesign']);


    Route::post('/design-review', [ReviewController::class, 'addReview']);
    Route::post('/design-search', [HomeController::class, 'SearchDesigns']);


    Route::post('/order', [OrderController::class, 'order']);



    Route::middleware(AdminOnly::class)->group(function () {
        Route::get('/admin/pending-designs', [AdminController::class, 'getPendingProducts']);
        Route::get('/admin/accept-design/{id}', [AdminController::class, 'acceptDesign']);
        Route::get('/admin/reject-design/{id}', [AdminController::class, 'rejectDesign']);
    });



    Route::post('/user-changePassword', [AuthController::class, 'changePassword']);
    Route::delete('/user-delete', [AuthController::class, 'deleteAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

<?php

use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AvatarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\UserItemController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\ItemPurchaseController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\SkillTipController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;

Route::middleware('guest:sanctum')->group(function(){
    Route::post('/login',[AuthController::class,'login'])
    ->middleware('throttle:5,1');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/users',[UserController::class,'index']);
    Route::get('/skills', [SkillController::class,'index']);
    Route::get('/animals', [AnimalController::class,'index']);
    Route::get('/avatars', [AvatarController::class,'index']);
    Route::get('/colors',[ColorController::class,'index']);
    Route::get('/items', [ItemController::class,'index']);
    Route::get('/challenges',[ChallengeController::class,'index']);
    Route::get('/notices',[NoticeController::class,'index']);
    Route::get('/skill/{skill}/tips',[SkillTipController::class,'index']);
    Route::post('/likes', [LikeController::class, 'store']);
    Route::patch('/users/{user}',[UserController::class,'update']);
    Route::patch('/users/{user}/animal-seen', [UserController::class, 'updateAnimalSeen']);
    Route::get('/points',[PointController::class,'index']);
    Route::post('/user_item',[ItemPurchaseController::class,'store']);
    Route::patch('/user_item/{userItem}',[UserItemController::class,'update']);
    Route::post('/skill/{skill}/tips',[SkillTipController::class,'store']);
    Route::get('/user',function(Request $request){
        return response()->json([
            'user'=>new UserResource($request->user()),
        ]);
});

Route::middleware('auth:sanctum','admin')->group(function(){
        Route::post('challenges',[ ChallengeController::class,'store']);
        Route::post('notices',[NoticeController::class,'store']);
        Route::patch('/users/{user}/reset-password',[UserController::class,'resetPassword']);
        Route::post('/users',[UserController::class,'store']);
        Route::delete('/users/{user}',[UserController::class,'destroy']);
});
});
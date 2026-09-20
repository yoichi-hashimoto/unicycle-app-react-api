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

Route::apiResource('users', UserController::class);
Route::apiResource('skills', SkillController::class);
Route::apiResource('animals', AnimalController::class);
Route::apiResource('avatars', AvatarController::class);
Route::apiResource('colors',ColorController::class);
Route::apiResource('items', ItemController::class);
Route::get('challenges',[ChallengeController::class,'index']);
Route::get('points',[PointController::class,'index']);
Route::get('notices',[NoticeController::class,'index']);
Route::get('/skill/{skill}/tips',[SkillTipController::class,'index']);

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('likes', LikeController::class);
    Route::patch('/users/{user}',[UserController::class,'update']);
    Route::patch('/users/{user}/animal-seen', [UserController::class, 'updateAnimalSeen']);
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
        Route::delete('/users/{user}',[UserController::class,'destroy']);
});
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\user_controller;
use App\Http\Controllers\Api\expertController;
use App\Http\Controllers\Api\evaluation;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
Route::post("register",[user_controller::class,'register']);
Route::post("login",[user_controller::class,'login']);
Route::post("addCons",[expertController::class,"AddCon"]);
Route::post("addFavourite",[expertController::class,"addFavourite"]);
Route::post("DeleteFavourite",[expertController::class,"DeleteFavourite"]);
Route::post("addDates/{id}",[expertController::class,"AddTime"]);

Route::get("list_experts",[user_controller::class,'list_experts']);
Route::get("get_expert/{id}",[user_controller::class,'get_expert']);
Route::post("list_experts_name",[user_controller::class,'list_experts_name']);
Route::get("list_experts_cons/{id}",[expertController::class,'list_experts_cons']);
Route::get("list_experts_info_cons/{id}",[expertController::class,'list_experts_info_cons']);
Route::get('ShowCon',[expertController::class,'ShowCon']);
Route::get('get_evaluation/{id}',[evaluation::class,'get_evaluation']);
Route::post('AddexpertCon',[expertController::class,"AddexpertCon"]);
Route::get("get_date/{id}",[expertController::class,'get_date']);
Route::get("getFavourites",[expertController::class,'getFavourites']);
Route::get("get_one_Favourites/{id}",[expertController::class,'get_one_Favourites']);

Route::group(["middleware"=>['api']],function (){

    Route::post('AddexpertCon',[expertController::class,"AddexpertCon"]);
    Route::post("add_one_Dates",[expertController::class,"Add_one_Time"]);
    Route::post("booking",[evaluation::class,'booking']);
    Route::post("register_img/{id}",[user_controller::class,"register_img"]);
    Route::post("get_user_evaluation",[evaluation::class,'get_user_evaluation']);
    Route::post("set_evaluation",[evaluation::class,'set_evaluation']);
    Route::get("profile",[user_controller::class,'profile']);
    Route::get("logout",[user_controller::class,'logout']);
    Route::post("send_message",[user_controller::class,"send_message"]);
    Route::post("show_conversation",[user_controller::class,"show_conversation"]);
    Route::get("ShowValidTimes",[expertController::class,"ShowValidTimes"]);
    Route::get("ShowDates",[expertController::class,"ShowDates"]);

});


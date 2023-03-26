<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AttackTypesController;
use App\Http\Controllers\Api\V1\PlayerTypesController;
use App\Http\Controllers\Api\V1\ItemTypesController;
use App\Http\Controllers\Api\V1\PlayersController;
use App\Http\Controllers\Api\V1\AttacksController; 
use App\Http\Controllers\Api\V1\InventoriesController; 
use App\Http\Controllers\Api\V1\ItemsController;



###################      AUTH ROUTES             ######################
 
Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::post('/login',[AuthController::class, 'login'])->name('login');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth:api');;


###################      PLAYERS ROUTES         ######################

Route::resource('/player_types', PlayerTypesController::class);
Route::resource('/players', PlayersController::class);

###################     ATTACKS ROUTES           ######################

Route::resource('/attack_types', AttackTypesController::class);
Route::resource('/attacks', AttacksController::class);

##############      ITEMS ROUTES                ######################

Route::resource('/item_types', ItemTypesController::class);
Route::resource('/items', ItemsController::class);

##############      INVENTORIES ROUTES                ######################

Route::resource('/inventories', InventoriesController::class);
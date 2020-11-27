<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\StatisticsController;
use \App\Http\Controllers\Api\StoreController;
use \App\Http\Controllers\Api\CollegeArticleController;
use \App\Http\Controllers\Api\CollegeCateController;
use \App\Http\Controllers\Api\DeviceController;
use \App\Http\Controllers\Api\GoodsController;
use \App\Http\Controllers\Api\OrderController;
use \App\Http\Controllers\Api\AdminRoleController;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\PublicController;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/public/login', [PublicController::class,'login']);

Route::middleware(['checkLogin'])->group(function () {
    Route::post('/public/refreshToken', [PublicController::class,'refreshToken']);
    Route::post('/public/logout', [PublicController::class,'logout']);
    //统计分析
    Route::post('/statistics/homeData', [StatisticsController::class,'homeData']);
    Route::post('/statistics/storeData', [StatisticsController::class,'storeData']);
    //万佳安学院文章
    Route::post('/collegeArticle/collegeArticleList', [CollegeArticleController::class,'collegeArticleList']);
    Route::post('/collegeArticle/collegeArticleAdd', [CollegeArticleController::class,'collegeArticleAdd']);
    Route::post('/collegeArticle/collegeArticleEdit', [CollegeArticleController::class,'collegeArticleEdit']);
    Route::post('/collegeArticle/collegeArticleDel', [CollegeArticleController::class,'collegeArticleDel']);
    Route::post('/collegeArticle/collegeArticlePublish', [CollegeArticleController::class,'collegeArticlePublish']);
    //万佳安学院文章分类
    Route::post('/collegeCate/collegeCateList', [CollegeCateController::class,'collegeCateList']);
    Route::post('/collegeCate/collegeCateAdd', [CollegeCateController::class,'collegeCateAdd']);
    Route::post('/collegeCate/collegeCateEdit', [CollegeCateController::class,'collegeCateEdit']);
    Route::post('/collegeCate/collegeCateDel', [CollegeCateController::class,'collegeCateDel']);
    //门店
    Route::post('/store/storeList', [StoreController::class,'storeList']);
    Route::post('/store/storeAdd', [StoreController::class,'storeAdd']);
    Route::post('/store/storeEdit', [StoreController::class,'storeEdit']);
    Route::post('/store/storeDel', [StoreController::class,'storeDel']);
    //设备
    Route::post('/device/deviceList', [DeviceController::class,'deviceList']);
    Route::post('/device/deviceAdd', [DeviceController::class,'deviceAdd']);
    Route::post('/device/deviceEdit', [DeviceController::class,'deviceEdit']);
    Route::post('/device/deviceDel', [DeviceController::class,'deviceDel']);
    //商品
    Route::post('/goods/goodsList', [GoodsController::class,'goodsList']);
    Route::post('/goods/goodsAdd', [GoodsController::class,'goodsAdd']);
    Route::post('/goods/goodsEdit', [GoodsController::class,'goodsEdit']);
    Route::post('/goods/goodsDel', [GoodsController::class,'goodsDel']);
    Route::post('/goods/goodsShelf', [GoodsController::class,'goodsShelf']);
    //订单
    Route::post('/order/orderList', [OrderController::class,'orderList']);
    //角色
    Route::post('/adminRole/adminRoleList', [AdminRoleController::class,'adminRoleList']);
    Route::post('/adminRole/adminRoleAdd', [AdminRoleController::class,'adminRoleAdd']);
    Route::post('/adminRole/adminRoleEdit', [AdminRoleController::class,'adminRoleEdit']);
    Route::post('/adminRole/adminRoleDel', [AdminRoleController::class,'adminRoleDel']);
    //用户
    Route::post('/user/userList', [UserController::class,'userList']);
    Route::post('/user/userAdd', [UserController::class,'userAdd']);
    Route::post('/user/userEdit', [UserController::class,'userEdit']);
    Route::post('/user/userDel', [UserController::class,'userDel']);
});


<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::group("/admin",function(){

    Route::post("/register","admin/register");

    Route::post("/login","admin/login");

    Route::get("/page","admin/page");

});


Route::group("/user",function(){

    Route::post("/register","user/register");

    Route::post("/login","user/login");

    Route::get("/page","user/page");

    Route::get("/getuser","user/getByUserName");

    Route::post("/disabled/:id","user/disabled");       //禁用

    Route::post("/verify","user/verify");       //实名认证

    Route::post("/pass","user/pass");           //审核

    Route::delete("/delete/:id","user/deleteById");     //删除用户

    Route::get("/user/:id","user/collect");         //收藏

    Route::post("/transfer","user/transfer");

});

Route::group("/position",function(){

    Route::post("/open","position/open");   //参数 u_id,type,buy_price,amount,direction

    Route::post("/close","position/close");         //id, close_price

    Route::get("/page","position/page");

    Route::get("/get/:id","position/getByUid");

    Route::delete("/delete/:id","position/deleteById");

});

Route::group("/type",function(){

    Route::post("/add","type/add");     //添加

    Route::get("/page","type/page");            //获取

    Route::delete("/delete/:id","type/delete");         //删除

    //添加 获取 删除
});

Route::group("/sign",function(){

    Route::get("/add","sign/add");

    Route::get("/get/:date","sign/getByDate");

});
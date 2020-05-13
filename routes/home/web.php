<?php

Route::group(['prefix'=>'home','namespace'=>'Home'],function (){
    Route::any('login','Login@login');
    Route::get('logout','Login@logout');
    Route::any('register','Register@register');
    Route::get('registerVerify/{phone}','Register@registerVerify');
    Route::any('forget','ForgetPassword@forgetPassword');
    Route::get('forgetVerify/{phone}','ForgetPassword@forgetVerify');
});

Route::group(['prefix'=>'home','namespace'=>'Home','middleware'=>'guest'],function (){
    Route::get('index','Index@index');
    Route::get('qiandao','Index@qiandao');
    Route::post('rent','Index@rent');
    Route::get('member','Member@member');
    Route::any('reset','ResetPassword@resetPassword');
    Route::get('link','Member@link');
    Route::get('team','Member@team');
    Route::get('qrcode','Member@qrcode');
    Route::get('notice','Member@notice');
    Route::get('noticePreview/{id}','Member@noticePreview');
    Route::get('memberService','Member@memberService');
    Route::any('realNameAuth','Member@realNameAuth');
    Route::get('bill','Member@bill');
    Route::get('idCardCheck/{idCard}','Member@idCardCheck');
    Route::get('running','MyMiner@running');
    Route::post('collect','MyMiner@collect');
    Route::get('finished','MyMiner@finished');
    Route::get('getMoreMinerFinished/{offset}','MyMiner@getMoreMinerFinished');
    Route::get('buy','Trade@buy');
    Route::get('unprocessedOrder','Trade@unprocessedOrder');
    Route::get('record','Trade@record');
    Route::get('orderPreview/{id}','Trade@orderPreview');
    Route::post('finishPay','Trade@finishPay');
    Route::post('finishPayConfirm','Trade@finishPayConfirm');
    Route::get('tradeCenter','Trade@tradeCenter');
    Route::post('tradeBuy','Trade@tradeBuy');
    Route::post('tradeSales','Trade@tradeSales');
    Route::post('tradeCheck','Trade@tradeCheck');
    Route::get('paidan/{number}','Trade@paidan');
    Route::get('cancelOrder/{orderId}','Trade@cancelOrder');
});

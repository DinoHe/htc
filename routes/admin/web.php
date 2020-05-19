<?php

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::any('login','Login@login');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'admin.guest'],function (){
    Route::get('index','Index@index');
    Route::get('/','Index@index');
    Route::get('logout','Login@logout');
    Route::get('adminList','Admin@list');
    Route::get('adminAccountStop/{id}','Admin@accountStop');
    Route::get('adminAccountOpen/{id}','Admin@accountOpen');
    Route::any('adminAdd','Admin@add');
    Route::any('adminEdit','Admin@edit');
    Route::post('adminDel','Admin@del');
    Route::get('adminRole','Admin@role');
});

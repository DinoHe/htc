<?php

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::any('login','Login@login');
    Route::get('index','Index@index');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'guest'],function (){

});

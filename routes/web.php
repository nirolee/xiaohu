<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
function user_ins() {
    return new App\User();
}

Route::get('/', function () {
    return view('welcome');
});
Route::any('api/user',function (){

   return user_ins()->signUp();
});

Route::any('api/login',function (){
    $user = new App\User();
    return user_ins()->login();
});
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
use Illuminate\Support\Facades\Request;
function user_ins() {
    return new App\User();
}
function question_ins() {
    return new App\Question;
}

function answer_ins() {
    return new App\Answer();
}
Route::get('/', function () {
    return view('welcome');
});
Route::any('api/signup',function (){

   return user_ins()->signUp();
});

Route::any('api/login',function (){

    return user_ins()->login();
});

Route::any('api/logout',function (){

    return user_ins()->logout();
});

Route::any('test',function (){
    dd(user_ins()->is_logged_in());
});

Route::any('api/question/create',function (){

//    dd(Request::all());
//http://localhost:7653/api/question/create?a=1&b=2
    return question_ins()->add();
});
Route::any('api/question/change',function (){
    return question_ins()->change();
});
Route::any('api/question/search',function (){
    return question_ins()->search();
});
Route::any('api/question/remove',function (){
    return question_ins()->remove();
});
Route::any('api/answer/create',function (){
    return answer_ins()->add();
});

Route::any('api/answer/change',function (){
    return answer_ins()->change();
});
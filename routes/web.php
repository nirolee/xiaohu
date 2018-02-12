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
function paginate($page = 1,$limit = 15) {
    $limit = $limit ?: 15;
    $skip = ($page ? $page - 1  : 0)*$limit;
    return [$limit,$skip];
}

function err($msg=null) {
    return ['status'=>0,'msg'=>$msg];
}

function suc($data_to_merge=[]) {
    //把参数传到'data'字段里,字段为空不能用null,不是array的格式
   $data = ['status'=>1,'data'=>[]];
   if ($data_to_merge)
       $data['data'] = array_merge($data['data'],$data_to_merge);
   return $data;
}

function user_ins() {
    return new App\User();
}
function question_ins() {
    return new App\Question();
}
function answer_ins() {
    return new App\Answer();
}
function comment_ins() {
    return new App\Comment();
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
Route::any('api/answer/search',function (){
    return answer_ins()->search();
});

Route::any('api/answer/vote',function (){
    return answer_ins()->vote();
});

Route::any('api/comment/create',function (){
    return comment_ins()->add();
});
Route::any('api/comment/search',function (){
    return comment_ins()->search();
});
Route::any('api/comment/remove',function (){
    return comment_ins()->remove();
});

Route::any('api/timeline','CommonsController@timeLine');
Route::any('api/user/change_password',function (){
    return user_ins()->change_password();
});
Route::any('api/user/reset_password',function (){
    return user_ins()->reset_password();
});
Route::any('api/user/validate_reset_password',function (){
    return user_ins()->validate_reset_password();
});
Route::any('api/user/read',function () {
    return user_ins()->read();
});
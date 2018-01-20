<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function signUp() {
        $username = Request::get('username');
        $password = Request::get('password');
        if (!$username || !$password)
            return ['status'=>0,'msg'=>'用户名和密码都不能为空'];
//        return 'valid';
        //检查用户名密码是否存在
        $user_exists = $this
            ->where('username',$username)
            ->exists();
        if ($user_exists)
            return ['status'=>0,'msg'=>'用户名已存在'];
        //加密密码
        //$hashed_password = Hash::make($password);
        $hashed_password = bcrypt($password);
//        dd($hashed_password);  整个项目停在这里

        $user = $this;
        $user->password = $hashed_password;
        $user->username  = $username;
        if ($user->save())
            return ['status'=>1, 'id'=> $user->id];
        else
            return ['status'=>0,'msg'=>'db insert failed'];
     //   dd(Request::all());
     //   dd(Request::has('name'));
//        dd(Request::get('name'));
//        return 'sign up!';
    }
    public function login(){

    }

}

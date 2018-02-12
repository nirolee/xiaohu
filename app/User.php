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

        $username = Request::get('username');
        $password = Request::get('password');
        if (!$username || !$password)
            return ['status'=>0,'msg'=>'用户名和密码都不能为空'];

        $user = $this->where('username',$username)->first();
        session()->put('password',$password);
        if (!$user)
            return ['status'=>0,'msg'=>'用户名不存在'];

        $hashed_password = $user->password;
        if (!Hash::check($password,$hashed_password))
            return ['status'=>0,'msg'=>'密码有误'];
        //错误检测都通过之后 添加session
        session()->put('username',$user->username);
        session()->put('user_id',$user->id);
//        dd(session()->all());
        return ['status'=>1, 'id'=>$user->id];

    }
    public function logout()
    {
        session()->forget('username');
        session()->forget('user_id');
        session()->forget('password');
//        return redirect('/'); 登出跳转到首页
        return ['status'=> 1];
        //session()->set('person.friend.name','paprika');嵌套赋值
        //session()->pull('username');剪切掉
//        session()->put('username',null);
//        session()->put('user_id',null);
        //清除session
//        session()->flush();
//        dd(session()->all());
    }
    public function is_logged_in()
    {
//        $session = session()->get('user_id');
//        return $session;
        return session('user_id') ?: false;
    }
    public function change_password() {

          if (!$this->is_logged_in())
              return ['status'=>0,'msg'=>'login required'];
          if (!Request::get('new') || !Request::get('old'))
              return ['status'=>0, 'msg'=>'new and old password required'];
          $user = $this->find(session('user_id'));
          if (!Hash::check(Request::get('old'),$user->password))
              return ['status'=>0,'msg'=>'old password not correct'];
          $user->password = bcrypt(Request::get('new'));
          return $user->save() ?
              ['status'=>1]:
              ['status'=>0,'msg'=>'db insert failed'];

    }
    public function answers() {
        return $this->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }

}

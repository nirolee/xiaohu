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
//        $user->password = bcrypt(111111);
//        $user->save();
//        dd($user);
        if (!$user)
            return ['status'=>0,'msg'=>'用户名不存在'];

        $hashed_password = $user->password;
        if (!Hash::check($password,$hashed_password))
            return ['status'=>0,'msg'=>'密码有误'];
        //错误检测都通过之后 添加session
        session()->put('username',$user->username);
        session()->put('user_id',$user->id);
//        dd(session()->all());
        return suc(['id' => $user->id]);

    }
    public function logout()
    {
        session()->forget('username');
        session()->forget('user_id');
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
              return err('login required');
          if (!Request::get('prev') || !Request::get('next'))
              return err('new and old password required');
          $user = $this->find(session('user_id'));
//          $user->password = bcrypt(111111);
//          $user->save();
//          dd($user);
          if (!Hash::check(Request::get('prev'),$user->password))
              return err('old password not correct');
          $user->password = bcrypt(Request::get('next'));
          return $user->save() ?
              suc():
              err('db insert failed');

    }
    public function reset_password() {
        if (!Request::get('phone'))
            return err('phone required');
        $user = $this->where('phone',Request::get('phone'))->first();
        if (!$user)
            return err('invalid phone number');
        //生成验证码
        $captcha = $this->generate_captcha();

        $user->phone_captcha = $captcha;
        if ($user->save()){
            //如果验证码保存成功,发送短信
            $this->send_sms();
            return suc();
        }
        return err('db update failed');
    }

    public function generate_captcha() {
        return rand(1000,9999);
    }
    public function send_sms() {
        return true;
    }

    public function validate_reset_password() {
        if (!Request::get('phone') || !Request::get('phone_captcha') || !Request::get('next'))
            return err('phone new_password and phone_captcha required');
        $user = $this->where([
            'phone'=>Request::get('phone'),
            'phone_captcha'=>Request::get('phone_captcha')
        ])->first();
        if (!$user)
            return err('invalid phone or captcha');
        $user->password = bcrypt(Request::get('next'));
        return $user->save() ?
            suc() : err('db update failed');

    }
    public function answers() {
        return $this->belongsToMany('App\Answer')
            ->withPivot('vote')
            ->withTimestamps();
    }

}

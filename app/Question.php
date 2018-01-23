<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Question extends Model
{
    public function add(){
        if(!user_ins()->is_logged_in())
            return ['status'=> 0,'msg'=> 'login required'];
        if (!Request::get('title'))
            return ['status'=> 0,'mdg'=> 'required title'];
        $this->title = Request::get('title');
        $this->user_id = session('user_id');
//        dd($this->title);
        if (Request::get('desc'))
            $this->desc = Request::get('desc');
        return $this->save() ? ['status'=> 1, 'id'=>$this->id]:
                               ['status'=> 0, 'msg'=> 'db insert failed'];

    }
    public function change() {
        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg'=> 'login required'];
        if (!Request::get('id'))
            return ['status'=> 0, 'msg'=>'id is required'];
        $question = $this->find(Request::get('id'));
        if ($question->user_id != session('user_id'))
            return ['status'=> 0, 'msg'=> 'permission denied'];
        if (Request::get('title'))
            $question->title = Request::get('title');
        if (Request::get('desc'))
            $question->desc = Request::get('desc');
        return $question->save() ? ['status'=> 1]:
                                   ['status'=> 0,'msg'=> 'db update failed'];
    }
}

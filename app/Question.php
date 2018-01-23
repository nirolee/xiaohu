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
}

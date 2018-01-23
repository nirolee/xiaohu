<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Answer extends Model
{
    public function add() {
        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg'=>'login required'];
        if (!Request::get('question_id') || !Request::get('content'))
            return ['status'=> 0, 'msg'=> 'question_id and content required'];
        $question = question_ins()->find(Request::get('question_id'));
        if (!$question) return ['status'=> 0, 'msg'=> 'question not exists'];

        $answered = $this->where(['question_id'=>Request::get('question_id'),'user_id'=>session('user_id')])
                         ->count();
        if ($answered)
            return ['status'=> 0, 'msg'=> 'duplicate answers'];
        $this->content = Request::get('content');
        $this->question_id = Request::get('question_id');
        $this->user_id = session('user_id');

        return $this->save() ?
               ['status'=> 1,'id'=> $this->id]:
               ['status'=> 0, 'msg'=>'db insert failed'];
    }
}

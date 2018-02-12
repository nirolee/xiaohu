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
    public function change() {

        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg'=> 'login required'];
        if (!Request::get('id') || !Request::get('content'))
            return ['status'=> 0, 'msg'=> 'id and content is required'];
        $answer = $this->find(Request::get('id'));
        if (!$answer) return ['status'=> 0, 'msg'=> 'answer not exists'];
        if ($answer->user_id != session('user_id'))
            return ['status'=> 0, 'msg'=> 'permission denied'];
        $answer->content = Request::get('content');
        return $answer->save() ?
            ['status'=> 1]:
            ['status'=>0, 'msg'=> 'db update failed'];
    }
    public function search() {
        //有没有选择查看具体哪个问题,没有返回所有的,但是limit有限制
            if (!Request::get('id') && !Request::get('question_id'))
               return ['status'=> 0,'msg'=> 'id or question_id required'];
            if (Request::get('id'))
            {
                $answer = $this->find(Request::get('id'));
                if (!$answer)
                    return ['status'=> 0, 'msg'=> 'answer not exist'];
                return ['status'=> 1, 'data'=> $answer];
            }
            if (!question_ins()->find(Request::get('question_id')))
                return ['status'=> 0,'msg'=> 'question not exists'];
            $answers = $this->where('question_id',Request::get('question_id'))
                            ->get()
                            ->keyBy('id');
            return ['status'=> 1, 'data'=> $answers];

    }

    public function vote() {
        if (!user_ins()->is_logged_in())
            return ['status'=>0,'msg'=>'login required'];
        if (!Request::get('id') || !Request::get('vote'))
            return ['status'=>0,'msg'=>'id and vote are required'];
        $answer = $this->find(Request::get('id'));
        if (!$answer) return ['status'=>0,'msg'=>'answer not exists'];
        //1.赞同  2.反对
        $vote = Request::get('vote') <= 1 ? 1 : 2;
        //检查用户在此问题下是否投过票,是则删除
        $answer->users()
           ->newPivotStatement()
           ->where('user_id',session('user_id'))
           ->where('answer_id',Request::get('id'))
           ->delete();
        //在链接表中增加数据
        $answer->users()->attach(session('user_id'),['vote'=> $vote]);
        return ['status'=>1];
    }
    public function users() {
        return $this->belongsToMany('App\User')
                    ->withPivot('vote')
                    ->withTimestamps();
    }
}

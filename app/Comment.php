<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Comment extends Model
{
    public  function add() {
        if (!user_ins()->is_logged_in())
            return ['status'=> 0, 'msg'=> 'login required'];
        if (!Request::get('content'))
            return ['status'=> 0,'msg'=> 'empty content'];
        if (
            (!Request::get('question_id') && !Request::get('answer_id')) || //none
            (Request::get('question_id') && Request::get('answer_id'))   //all
        )
            return ['status'=> 0, 'msg'=> 'question_id or answer_id required'];
        if (Request::get('question_id'))
        {
            $question = question_ins()->find(Request::get('question_id'));
            if (!$question) return ['status'=> 0, 'msg'=> 'question not exists'];
            $this->question_id = Request::get('question_id');
        }else {
            $answer = answer_ins()->find(Request::get('answer_id'));
            if (!$answer) return ['status'=> 0, 'msg'=> 'answer not exists'];
            $this->answer_id = Request::get('answer_id');
        }
        if (Request::get('reply_to'))
        {    //给评论评论
            $target_comment = $this->find(Request::get('reply_to'));
            if (!$target_comment) return ['status'=> 0, 'msg'=> 'target comment not exists'];
            if ($target_comment->user_id == session('user_id'))
                return ['status'=> 0 ,'msg'=> 'reply_to same user_id'];
            $this->reply_to = Request::get('reply_to');
        }
        $this->content = Request::get('content');
        $this->user_id = session('user_id');
         //存入数据库
        return $this->save() ?
            ['status'=> 1,'id'=> $this->id]:
            ['status'=> 0, 'msg'=> 'db insert failed'];
    }

    public function search()
    {
          if (!Request::get('question_id') && !Request::get('answer_id'))
              return ['status'=>0,'msg'=> 'question_id or answer_id required'];
          if (Request::get('question_id'))
          {
              $question = question_ins()->find(Request::get('question_id'));
              if (!$question) return ['status'=> 0, 'msg'=> 'question not exists'];
              $data = $this->where('question_id',Request::get('question_id'))->get();
          } else {
              $answer = answer_ins()->find(Request::get('answer_id'));
              if (!$answer) return ['status'=> 0, 'msg'=> 'answer not exists'];
              $data = $this->where('answer_id',Request::get('answer_id'))->get();
          }
          return ['status'=> 1, 'data'=> $data->keyBy('id')];
    }

    public function remove() {
       if (!user_ins()->is_logged_in())
           return ['status'=> 0, 'msg'=> 'login required'];
       if (!Request::get('id'))
           return ['status'=> 0, 'msg'=> 'id required'];
       $comment = $this->find(Request::get('id'));
       if (!$comment) return ['status'=> 0, 'msg'=> 'comment not exists'];
       if ($comment->user_id != session('user_id'))
           return ['status'=> 0, 'msg'=> 'permission denied'];
       //删掉评论之前要删掉与这个评论有关的其他回复,避免出现野指针
       $this->where('reply_to',Request::get('id'))->delete();
       return $comment->delete() ? ['status'=>1]:
                          ['status'=> 0, 'msg'=>'db delete failed'];
    }
}

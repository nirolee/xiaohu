<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class CommonsController extends Controller
{
    //时间线api
    public function timeLine() {

        list($limit,$skip) = paginate(Request::get('page'),Request::get('limit'));
        $questions = question_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();
        $answers = answer_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();
//        dd($questions->toArray());
//        dd($answers->toArray());
        //混合问题和回答为一整个时间线上的item,sortBy function(item)相当于map
        $data = $questions->merge($answers);
        $data = $data->sortByDesc(function ($item){
            return $item->created_at;
        });
        //只取value忽略id(否则ID冲突)
        $data = $data->values()->all();
        return $data;
    }
}

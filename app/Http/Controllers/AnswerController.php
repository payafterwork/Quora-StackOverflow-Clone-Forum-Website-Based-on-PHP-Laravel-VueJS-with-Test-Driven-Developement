<?php

namespace App\Http\Controllers;
use App\Answer;
use App\Question;
use Illuminate\Auth\Middleware\Auth;
use Illuminate\Http\Request;


class AnswerController extends Controller
{
    function __construct()
    {
      $this->middleware('auth')->only(['store']);
    }

    public function store(Request $request,$subjectid, Question $question)
    {  

      $this->validate($request,[
        'ans' => 'required'
       ]);

      $question->addAnswer([
          'ans'=>request('ans'),
          'user_id'=>auth()->id()
      ]);
       return back();
    }


}

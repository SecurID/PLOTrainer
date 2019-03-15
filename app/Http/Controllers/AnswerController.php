<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Situation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Save Answer
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saveAnswer(Request $request)
    {

        $answer = new Answer;

        $situation = Situation::where('name', $request->situation_id)->first();

        $answer->user_id = $id = $request->user_id;
        $answer->correct = $request->correct;
        $answer->situation_id = $situation->id;

        $answer->save();

        return response()->json([
            'success' => true
        ]);
    }
}

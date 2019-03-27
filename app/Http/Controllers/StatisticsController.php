<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Situation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Show the statistics Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showStatistics()
    {
        /*SELECT answers.correct, situations.name, answers.created_at FROM answers JOIN situations ON
        situation_id = situations.id WHERE answers.user_id = 1*/

        $situationsAll = Situation::all()->where('active', '=', 1);
        $situations = [];
        $i = 0;

        foreach($situationsAll as $situationAll){
            /*$situations[] = DB::table('answers')->join('situations', 'answers.situation_id', '=', 'situations.id')
                ->select('answers.correct', 'situations.name', 'answers.created_at')
                ->where('answers.user_id', '=', Auth::user()->id)
                ->where('situations.id', '=', $situationAll->id)
                ->get();*/
            $situations[$i]['name'] = $situationAll->name;
            $situations[$i]['sumAnswers'] = DB::table('answers')
                ->where('situation_id', '=', $situationAll->id)
                ->where('user_id', '=', Auth::user()->id)
                ->count();
            if($situations[$i]['sumAnswers'] > 0) {
                $lastAnswer = DB::table('answers')
                    ->where('situation_id', '=', $situationAll->id)
                    ->where('user_id', '=', Auth::user()->id)
                    ->oldest()
                    ->first();
                $situations[$i]['lastAnswer'] = $lastAnswer->created_at;
            }else{
                $situations[$i]['lastAnswer'] = 'No Answer';
            }
            $answers = DB::table('answers')
                ->where('situation_id', '=', $situationAll->id)
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            $correctCount = 0;
            foreach($answers as $answer){
                if($answer->correct == "true"){
                    $correctCount++;
                }
            }

            if($situations[$i]['sumAnswers'] > 0) {
                $situations[$i]['correctAnswers'] = round($correctCount / $situations[$i]['sumAnswers'] * 100, 0);
            }else{
                $situations[$i]['correctAnswers'] = 0;
            }


            $i++;

        }

        return view('statistics', ['situations' => $situations]);
    }
    /**
     * Show the statistics Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showAllStatistics()
    {
        /*SELECT answers.correct, situations.name, answers.created_at FROM answers JOIN situations ON
        situation_id = situations.id WHERE answers.user_id = 1*/

        $situationsAll = Situation::all()->where('active', '=', 1);
        $situations = [];
        $i = 0;

        foreach ($situationsAll as $situationAll) {
            /*$situations[] = DB::table('answers')->join('situations', 'answers.situation_id', '=', 'situations.id')
                ->select('answers.correct', 'situations.name', 'answers.created_at')
                ->where('answers.user_id', '=', Auth::user()->id)
                ->where('situations.id', '=', $situationAll->id)
                ->get();*/
            $situations[$i]['name'] = $situationAll->name;
            $situations[$i]['sumAnswers'] = DB::table('answers')
                ->where('situation_id', '=', $situationAll->id)
                ->count();
            if ($situations[$i]['sumAnswers'] > 0) {
                $lastAnswer = DB::table('answers')
                    ->where('situation_id', '=', $situationAll->id)
                    ->oldest()
                    ->first();
                $situations[$i]['lastAnswer'] = $lastAnswer->created_at;
            } else {
                $situations[$i]['lastAnswer'] = 'No Answer';
            }
            $answers = DB::table('answers')
                ->where('situation_id', '=', $situationAll->id)
                ->get();
            $correctCount = 0;
            foreach ($answers as $answer) {
                if ($answer->correct == "true") {
                    $correctCount++;
                }
            }

            if ($situations[$i]['sumAnswers'] > 0) {
                $situations[$i]['correctAnswers'] = round($correctCount / $situations[$i]['sumAnswers'] * 100, 0);
            } else {
                $situations[$i]['correctAnswers'] = 0;
            }


            $i++;

        }

        return view('statistics', ['situations' => $situations]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Hand;
use App\Situation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HandController extends Controller
{
    /**
     * Return a random Hand
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHand()
    {
        $situationName = null;
        $foldPercentage = 0;
        $callPercentage = 0;
        $raisePercentage = 0;
        $handName = null;
        $flag = true;
        $user = Auth::user();

        if($user->admin == 0){
            $situation = Situation::inRandomOrder()->where([['active', 1],['onlyAdmin', 0]])->first();
        }else{
            $situation = Situation::inRandomOrder()->where('active', 1)->first();
        }

        do {
            $randomHandNumber = rand(0, 270725);

            $hand = Hand::find($randomHandNumber);

            $situations = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage')
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->get();

            $situationName = $situation->name;
            $handName = $hand->hand;
            $handId = $hand->id;

            foreach($situations as $situation){
                if($situation->Action == "Raise"){
                    $raisePercentage = $situation->Percentage;
                }elseif($situation->Action == "Call"){
                    $callPercentage = $situation->Percentage;
                }elseif($situation->Action == "Fold"){
                    $foldPercentage = $situation->Percentage;
                }
                $flag = false;
            }

        } while($flag);

        $handSplitted = str_split($handName,2);

        return response()->json([
            'handId' => $handId,
            'situationName' => $situationName,
            'cardOne' => $handSplitted[0],
            'cardTwo' => $handSplitted[1],
            'cardThree' => $handSplitted[2],
            'cardFour' => $handSplitted[3],
            'raisePercentage' => $raisePercentage,
            'callPercentage' => $callPercentage,
            'foldPercentage' => $foldPercentage
        ]);
    }

    /**
     * Return a random Hand for specific situations
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHandSituations(Request $request)
    {
        $situationId = $request->all();
        foreach($situationId as $situation){
            $arraySituations[] = $situation;
        }

        $situationName = null;
        $foldPercentage = 0;
        $callPercentage = 0;
        $raisePercentage = 0;
        $handName = null;
        $flag = true;
        $user = Auth::user();

        $situation = Situation::find($arraySituations)->shuffle();
        $situation = $situation[0];

        do {
            $randomHandNumber = rand(0, 270725);

            $hand = Hand::find($randomHandNumber);

            $situations = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage')
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->get();

            $situationName = $situation->name;
            $handName = $hand->hand;
            $handId = $hand->id;

            foreach($situations as $situation){
                if($situation->Action == "Raise"){
                    $raisePercentage = $situation->Percentage;
                }elseif($situation->Action == "Call"){
                    $callPercentage = $situation->Percentage;
                }elseif($situation->Action == "Fold"){
                    $foldPercentage = $situation->Percentage;
                }
                $flag = false;
            }

        } while($flag);

        $handSplitted = str_split($handName,2);

        return response()->json([
            'handId' => $handId,
            'situationName' => $situationName,
            'cardOne' => $handSplitted[0],
            'cardTwo' => $handSplitted[1],
            'cardThree' => $handSplitted[2],
            'cardFour' => $handSplitted[3],
            'raisePercentage' => $raisePercentage,
            'callPercentage' => $callPercentage,
            'foldPercentage' => $foldPercentage
        ]);
    }

    /**
     * Return a random Hand for specific percentages
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHandOptions(Request $request)
    {
        $situationId = $request->all();
        foreach($situationId as $situation){
            $arraySituations[] = $situation;
        }

        $situationName = null;
        $foldPercentage = 0;
        $callPercentage = 0;
        $raisePercentage = 0;
        $handName = null;
        $flag = true;
        $user = Auth::user();

        $situation = Situation::find($arraySituations)->shuffle();
        $situation = $situation[0];

        do {
            $randomHandNumber = rand(0, 270725);

            $hand = Hand::find($randomHandNumber);

            $situations = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage')
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->get();

            $situationName = $situation->name;
            $handName = $hand->hand;

            foreach($situations as $situation){
                if($situation->Action == "Raise"){
                    $raisePercentage = $situation->Percentage;
                }elseif($situation->Action == "Call"){
                    $callPercentage = $situation->Percentage;
                }elseif($situation->Action == "Fold"){
                    $foldPercentage = $situation->Percentage;
                }
                $flag = false;
            }

        } while($flag);

        $handSplitted = str_split($handName,2);

        return response()->json([
            'situationName' => $situationName,
            'cardOne' => $handSplitted[0],
            'cardTwo' => $handSplitted[1],
            'cardThree' => $handSplitted[2],
            'cardFour' => $handSplitted[3],
            'raisePercentage' => $raisePercentage,
            'callPercentage' => $callPercentage,
            'foldPercentage' => $foldPercentage
        ]);
    }

    /**
     * Return a random Hand for specific percentages
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHandIncorrect(Request $request)
    {
        $situationName = null;
        $foldPercentage = 0;
        $callPercentage = 0;
        $raisePercentage = 0;
        $handName = null;
        $flag = true;
        $user = Auth::user();

        $answer = Answer::where('user_id', '=', $user->id)->where('correct', '=', 'false')->inRandomOrder()->first();

        if($answer == null){
            return response()->json(['noMoreHands' => true]);
        }

        $situation = Situation::find($answer->situation_id);

        do {
            $randomHandNumber = $answer->handId;

            $hand = Hand::find($randomHandNumber);

            $situations = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage')
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->get();

            $situationName = $situation->name;
            $handName = $hand->hand;

            foreach($situations as $situation){
                if($situation->Action == "Raise"){
                    $raisePercentage = $situation->Percentage;
                }elseif($situation->Action == "Call"){
                    $callPercentage = $situation->Percentage;
                }elseif($situation->Action == "Fold"){
                    $foldPercentage = $situation->Percentage;
                }
                $flag = false;
            }

        } while($flag);

        $handSplitted = str_split($handName,2);

        return response()->json([
            'answerId' => $answer->id,
            'situationName' => $situationName,
            'cardOne' => $handSplitted[0],
            'cardTwo' => $handSplitted[1],
            'cardThree' => $handSplitted[2],
            'cardFour' => $handSplitted[3],
            'raisePercentage' => $raisePercentage,
            'callPercentage' => $callPercentage,
            'foldPercentage' => $foldPercentage
        ]);
    }
}

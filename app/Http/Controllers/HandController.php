<?php

namespace App\Http\Controllers;

use App\Action;
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
        $situationPosition = null;

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
            $situationPosition = $situation->position;

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
            'situationPosition' => $situationPosition,
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
        $situationPosition = null;

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
            $situationPosition = $situation->position;

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
            'situationPosition' => $situationPosition,
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
        $minPercentage = $request->minPercentage;
        $action = Action::where('name', '=', $request->action)->first();

        $situationName = null;
        $foldPercentage = 0;
        $callPercentage = 0;
        $raisePercentage = 0;
        $handName = null;
        $flag = true;
        $user = Auth::user();
        $situationPosition = null;

        $situations = DB::table('hands_to_situations_to_actions')
            ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
            ->where('hands_to_situations_to_actions.percentage', '>=', $minPercentage)
            ->where('action_id', '=', $action->id)
            ->join('actions', 'action_id', '=', 'actions.id')
            ->join('hands', 'hand_id', '=', 'hands.id')
            ->join('situations', 'situation_id', '=', 'situations.id')
            ->inRandomOrder()
            ->first();

        $situation = Situation::find($situations->situation_id);
        $hand = Hand::find($situations->hand_id);

        $situationName = $situation->name;
        $handName = $hand->hand;
        $situationPosition = $situation->position;

        if($situations->Action == "Raise"){
            $raisePercentage = $situations->Percentage;
            $callAction = Action::where('name', '=', 'Call')->first();
            $callEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $callAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($callEntry)){
                $callPercentage = $callEntry->Percentage;
            }else{
                $callPercentage = 0;
            }
            $foldAction = Action::where('name', '=', 'Fold')->first();
            $foldEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $foldAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($foldEntry)){
                $foldPercentage = $foldEntry->Percentage;
            }else{
                $foldPercentage = 0;
            }
        }elseif($situations->Action == "Call"){
            $callPercentage = $situations->Percentage;
            $foldAction = Action::where('name', '=', 'Fold')->first();
            $foldEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $foldAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($foldEntry)){
                $foldPercentage = $foldEntry->Percentage;
            }else{
                $foldPercentage = 0;
            }
            $raiseAction = Action::where('name', '=', 'Raise')->first();
            $raiseEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $raiseAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($raiseEntry)){
                $raisePercentage = $raiseEntry->Percentage;
            }else{
                $raisePercentage = 0;
            }
        }elseif($situations->Action == "Fold"){
            $foldPercentage = $situations->Percentage;
            $callAction = Action::where('name', '=', 'Call')->first();
            $callEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $callAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($callEntry)){
                $callPercentage = $callEntry->Percentage;
            }else{
                $callPercentage = 0;
            }
            $raiseAction = Action::where('name', '=', 'Raise')->first();
            $raiseEntry = DB::table('hands_to_situations_to_actions')
                ->select('hands.hand AS Hand', 'situations.name AS Situation', 'actions.name AS Action', 'percentage AS Percentage', 'hands.id AS hand_id', 'situations.id AS situation_id')
                ->where('action_id', '=', $raiseAction->id)
                ->where('hand_id', '=', $hand->id)
                ->where('situation_id', '=', $situation->id)
                ->join('actions', 'action_id', '=', 'actions.id')
                ->join('hands', 'hand_id', '=', 'hands.id')
                ->join('situations', 'situation_id', '=', 'situations.id')
                ->orderBy('hands.hand')
                ->orderBy('situations.name')
                ->orderBy('actions.name')
                ->first();
            if(isset($raiseEntry)){
                $raisePercentage = $raiseEntry->Percentage;
            }else{
                $raisePercentage = 0;
            }
        }

        $handSplitted = str_split($handName,2);

        return response()->json([
            'situationName' => $situationName,
            'situationPosition' => $situationPosition,
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
        $situationPosition = null;

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
            $situationPosition = $situation->position;

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
            'situationPosition' => $situationPosition,
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

<?php

namespace App\Http\Controllers;

use App\Action;
use App\Answer;
use App\Hand;
use App\Situation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     *
     */
    public function processSpecificFile()
    {
        $array = null;
        $content = null;
        $found = null;

        $path = 'ranges/RaiseFile5c938aa74fb1f.txt';
        $action = $action = Action::where('name', 'Raise')->first();
        $situation = Situation::first();
        $insertarray = [];

        Log::info('File Processing started: '.$path);
        try {
            $hands = Hand::all();

            $content = json_decode(Storage::disk('local')->get($path));

            foreach ($content as $key => $line) {
                $array[$key] = explode('@', $line);
                foreach ($hands as $hand) {
                    if ($hand->hand == $array[$key][0]) {
                        $found = $hand;
                        break;
                    }
                }
                $insertarray[] = ['hand_id' => $found->id, 'action_id' => $action->id, 'situation_id' => $situation->id, 'percentage' => $array[$key][1], 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()];
            }

            DB::table('hands_to_situations_to_actions')->insert($insertarray);
        }catch (\Exception $e){
            Log::info('Error: '.$e);
        }

        Log::info('File Processing finished: '.$path);

    }
}

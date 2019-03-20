<?php

namespace App\Http\Controllers;

use App\Action;
use App\Hand;
use App\Jobs\ProcessRangeFiles;
use App\Situation;
use Faker\Provider\File;
use Hamcrest\NullDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SituationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the create Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showCreateSituation()
    {
        return view('createSituation');
    }

    /**
     * Upload the create Files
     */
    public function uploadFile(Request $request)
    {
        // process SituationName
        $name = $request->input('name');
        $situation = Situation::firstOrCreate(['name' => $name, 'active' => 1]);

        //process RaiseRange
        $action = Action::where('name', 'Raise')->first();
        $path = $request->file('rangeRaise')->store('ranges');

        //Split Files
        $content = Storage::disk('local')->get($path);
        $array = explode(",", $content);
        $arrayFinal = array_chunk($array, 1000);

        foreach($arrayFinal as $arrayJob){
            $filename = 'ranges/RaiseFile'.uniqid().'.txt';
            Storage::disk('local')->put($filename, json_encode($arrayJob));
            ProcessRangeFiles::dispatch($action, $situation, $filename);
        }

        //process CallRange
        $action = Action::where('name', 'Call')->first();
        $path = $request->file('rangeCall')->store('ranges');

        //Split Files
        $content = Storage::disk('local')->get($path);
        $array = explode(",", $content);
        $arrayFinal = array_chunk($array, 1000);

        foreach($arrayFinal as $arrayJob){
            $filename = 'ranges/CallFile'.uniqid().'.txt';
            Storage::disk('local')->put($filename, json_encode($arrayJob));
            ProcessRangeFiles::dispatch($action, $situation, $filename);
        }

        //process FoldRange
        $action = Action::where('name', 'Fold')->first();
        $path = $request->file('rangeFold')->store('ranges');

        //Split Files
        $content = Storage::disk('local')->get($path);
        $array = explode(",", $content);
        $arrayFinal = array_chunk($array, 1000);

        foreach($arrayFinal as $arrayJob){
            $filename = 'ranges/FoldFile'.uniqid().'.txt';
            Storage::disk('local')->put($filename, json_encode($arrayJob));
            ProcessRangeFiles::dispatch($action, $situation, $filename);
        }

        return view('createSituation', ['success' => 'Files will be processed. Duration: 30 Minutes']);
    }

    /**
     * Show the edit Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showEditSituation()
    {
        $situations = Situation::where('active', 1)->get();

        return view('editSituation', ['situations' => $situations]);
    }

    /**
     * Show the select Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function selectSituation()
    {
        $situations = Situation::where('active', 1)->get();

        return view('selectSituation', ['situations' => $situations ]);
    }

    /**
     * Show the select Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function randomSituation()
    {
        $user = Auth::user();

        return view('randomSituation', ['user_id' => $user->id]);
    }

    /**
     * Deletes a situation
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteSituation($idSituation)
    {
        $situation = Situation::find($idSituation);
        $situation->active = 0;
        $situation->save();

        $situations = Situation::where('active', 1)->get();

        return view('editSituation', ['situations' => $situations]);
    }
}

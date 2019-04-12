<?php

namespace App\Http\Controllers;

use App\Action;
use App\Hand;
use App\Jobs\ChangeSituationStatus;
use App\Jobs\ProcessRangeFiles;
use App\Mail\JobsFinished;
use App\Situation;
use Faker\Provider\File;
use Hamcrest\NullDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        $situation = Situation::where(['name' => $name])->first(); // model or null
        if ($situation) {
            return view('createSituation', ['failed' => 'Situation already exists!']);
        }

        $situation = new Situation;
        $situation->name = $name;
        $situation->active = 0;
        $situation->save();

        if(null !== $request->file('rangeRaise')) {
            //process RaiseRange
            $action = Action::where('name', 'Raise')->first();
            $path = $request->file('rangeRaise')->store('ranges');

            //Split Files
            $content = Storage::disk('local')->get($path);
            $array = explode(",", $content);
            $arrayFinal = array_chunk($array, 100);

            foreach ($arrayFinal as $arrayJob) {
                $filename = 'ranges/RaiseFile' . uniqid() . '.txt';
                Storage::disk('local')->put($filename, json_encode($arrayJob));
                ProcessRangeFiles::dispatch($action, $situation, $filename);
            }
        }

        if(null !== $request->file('rangeCall')) {
            //process CallRange
            $action = Action::where('name', 'Call')->first();
            $path = $request->file('rangeCall')->store('ranges');

            //Split Files
            $content = Storage::disk('local')->get($path);
            $array = explode(",", $content);
            $arrayFinal = array_chunk($array, 100);

            foreach ($arrayFinal as $arrayJob) {
                $filename = 'ranges/CallFile' . uniqid() . '.txt';
                Storage::disk('local')->put($filename, json_encode($arrayJob));
                ProcessRangeFiles::dispatch($action, $situation, $filename);
            }
        }

        if(null !== $request->file('rangeFold')) {
            //process FoldRange
            $action = Action::where('name', 'Fold')->first();
            $path = $request->file('rangeFold')->store('ranges');

            //Split Files
            $content = Storage::disk('local')->get($path);
            $array = explode(",", $content);
            $arrayFinal = array_chunk($array, 100);

            foreach ($arrayFinal as $arrayJob) {
                $filename = 'ranges/FoldFile' . uniqid() . '.txt';
                Storage::disk('local')->put($filename, json_encode($arrayJob));
                ProcessRangeFiles::dispatch($action, $situation, $filename);
            }
        }

        ChangeSituationStatus::dispatch($situation, 1);

        Mail::to("jacknineofclubs@googlemail.com")
            ->queue(new JobsFinished($situation));

        return view('createSituation', ['success' => 'Files will be processed.']);
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
        $user = Auth::user();
        if($user->admin == 0) {
            $situations = Situation::where([['active', 1],['onlyAdmin', 0]])->get();
        }else{
            $situations = Situation::where('active', 1)->get();
        }

        return view('selectSituation', ['situations' => $situations ]);
    }

    /**
     * Show the select Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function selectSituationTraining(Request $request)
    {
        $situations = $request->input('situation');
        $user = Auth::user();

        return view('selectSituationTraining', ['situations' => $situations, 'user_id' => $user->id]);
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
     * Show the select Admin Situation Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function randomSituationAdmin()
    {
        $user = Auth::user();

        return view('randomSituationAdmin', ['user_id' => $user->id]);
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

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Renames a situation
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function renameSituation(Request $request, $idSituation)
    {
        $situation = Situation::find($idSituation);
        $situation->name = $request->input('newName');
        $situation->save();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Renames a situation
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function onlyAdminSituation(Request $request, $idSituation)
    {
        $situation = Situation::find($idSituation);
        if($request->input('value') == "true") {
            $situation->onlyAdmin = 1;
        }else{
            $situation->onlyAdmin = 0;
        }

        $situation->save();

        return response()->json([
            'success' => true
        ]);
    }


}

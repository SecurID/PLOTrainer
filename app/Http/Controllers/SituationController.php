<?php

namespace App\Http\Controllers;

use App\Action;
use App\Hand;
use App\Jobs\ProcessRangeFiles;
use App\Situation;
use Faker\Provider\File;
use Hamcrest\NullDescription;
use Illuminate\Http\Request;
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
        $situation = Situation::firstOrCreate(['name' => $name]);

        //process RaiseRange
        $action = Action::where('name', 'Raise')->first();
        $path = $request->file('rangeRaise')->store('ranges');

        ProcessRangeFiles::dispatch($action, $situation, $path);

        //process CallRange
        $action = Action::where('name', 'Call')->first();
        $path = $request->file('rangeCall')->store('ranges');

        ProcessRangeFiles::dispatch($action, $situation, $path);

        //process CallRange
        $action = Action::where('name', 'Fold')->first();
        $path = $request->file('rangeFold')->store('ranges');

        ProcessRangeFiles::dispatch($action, $situation, $path);

        return view('createSituation', ['success' => 'Files will be processed']);
    }

}

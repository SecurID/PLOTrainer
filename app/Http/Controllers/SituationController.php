<?php

namespace App\Http\Controllers;

use App\Hand;
use App\Situation;
use Faker\Provider\File;
use Illuminate\Http\Request;
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
        $name = $request->input('name');
        $situation = Situation::firstOrCreate(['name' => $name]);
        $path = $request->file('range')->store('ranges');
        $content = Storage::disk('local')->get($path);
        $header = null;
        $array = null;
        foreach (explode(",", $content) as $key=>$line){
            $array[$key] = explode('@', $line);
            $hand = Hand::firstOrCreate(['hand' => $array[$key][0]]);
        }

        return $array;
    }

}

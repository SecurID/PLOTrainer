<?php

namespace App\Jobs;

use App\Action;
use App\Hand;
use App\Situation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessRangeFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1800;

    protected $action;
    protected $situation;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Action $action, Situation $situation, $path)
    {
        $this->action = $action;
        $this->situation = $situation;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $array = null;
        $content = null;
        $found = null;

        $path = $this->path;
        $action = $this->action;
        $situation = $this->situation;
        $insertarray = [];

        Log::info('File Processing started: '.$path);

        $hands = Hand::all();

        $content = json_decode(Storage::disk('local')->get($path));

        foreach ($content as $key=>$line){
            $array[$key] = explode('@', $line);
            foreach($hands as $hand){
                if($hand->hand == $array[$key][0]){
                    $found = $hand;
                    break;
                }
            }
            $insertarray[] = ['hand_id' => $found->id, 'action_id' => $action->id, 'situation_id' => $situation->id, 'percentage' => $array[$key][1], 'created_at' => Carbon::now()->toDateTimeString(), 'updated_at' => Carbon::now()->toDateTimeString()];
        }

        DB::table('hands_to_situations_to_actions')->insert($insertarray);

        Log::info('File Processing finished: '.$path);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        $path = $this->path;
        Log::info('File Processing failed: '.$path);
        Log::info($exception);
    }
}

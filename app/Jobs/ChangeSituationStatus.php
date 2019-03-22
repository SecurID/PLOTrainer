<?php

namespace App\Jobs;

use App\Situation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeSituationStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 1800;

    protected $situation;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Situation $situation, $status)
    {
        $this->situation = $situation;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $situation = $this->situation;
        $status = $this->status;

        $situation->active = $status;

        $situation->save();
    }
}

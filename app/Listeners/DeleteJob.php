<?php

namespace App\Listeners;

use App\Events\ProcesingJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteJob implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ProcesingJob  $event
     * @return void
     */
    public function handle(ProcesingJob $event)
    {
        \Log::info('================= DELETE JOB =================');
        \Log::info(json_encode($this));
        if (true) {

        }
        $ids = $event->ids;
        dd($ids);
    }
}

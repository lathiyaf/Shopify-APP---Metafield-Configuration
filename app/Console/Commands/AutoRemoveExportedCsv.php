<?php

namespace App\Console\Commands;

use App\Model\ExportCSV;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AutoRemoveExportedCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoremove:exportedcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Kolkata');
        $current_timestamp = Carbon::now()->format('Y-m-d');
        $dateBeforeOneMonth = date('Y-m-d', strtotime('-30 days'));
        $entities = ExportCSV::whereDate('created_at', '<=', $dateBeforeOneMonth )->get();

        if( $entities ){
            foreach ($entities as $key=>$val){
                $file_path = Storage::disk('public')->path($val->file);
                unlink($file_path);
                $val->delete();
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\ExportCSVJob;
use Illuminate\Console\Command;

class dispatchExportJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:exportJob {--queue=true} {resourceType} {export_metafield} {data} {id} {shop_id}';

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
        \Log::info('------------------------dispatch export Job command-----------------------');
        \Log::info($this->argument('export_metafield'));
        \Log::info($this->argument('data'));
        ExportCSVJob::dispatch($this->argument('resourceType'), $this->argument('export_metafield'), $this->argument('data'), $this->argument('id'), $this->argument('shop_id'));
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\ImportCSVJob;
use Illuminate\Console\Command;

class dispatchImportJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:importJob {--queue=true} {resourceType} {is_replace} {file_path} {shop_id}';

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
        \Log::info('------------------------dispatch import Job command-----------------------');
        ImportCSVJob::dispatch($this->argument('resourceType'), $this->argument('is_replace'), $this->argument('file_path'), $this->argument('shop_id'));
    }
}

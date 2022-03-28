<?php

namespace App\Console\Commands;

use App\Jobs\SyncMetafieldsJob;
use App\Model\Shop;
use Illuminate\Console\Command;

class dispatchSyncJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:syncJob {--queue=true} {shop_id} {resourceType} {id}';

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
        \Log::info('------------- dispatch sync Job command ---------------');
        $shop = Shop::where('id', $this->argument('shop_id'))->first();
        \Log::info(json_encode($shop));
        SyncMetafieldsJob::dispatchNow($this->argument('resourceType'), $this->argument('id'), $shop);
    }
}

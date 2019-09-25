<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HashtagController as HC;

class UpdateHashtag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:hashtag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new set of hashtag trends.';

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
        $hc = new HC();
        $res = $hc->checkNewHash();
        $this->info('done.');
    }
}

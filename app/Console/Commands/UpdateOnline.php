<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserInfo as UI;
use App\Events\UpdateOnline as UO;

class UpdateOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Online Users';

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
        $fp = fopen(storage_path().'/app/online.json', 'w');
        fwrite($fp, json_encode(UI::where(['status' => '1'])->orWhere(['status' => 2])->orderBy('last_activity','desc')->with('user.roles')->get(),JSON_PRETTY_PRINT));
        fclose($fp);
        $ol = json_decode(file_get_contents(storage_path().'/app/online.json'),true);
        if(count($ol)){
            broadcast(new UO(1,$ol));
            $this->info('Updates done. There are users Onlines');
        }else{
            broadcast(new UO(0,$ol));
            $this->info('Updates done. There are no user online.');
        }
    }
}

<?php

namespace App\Console\Commands;

use DB;
use Settings;
use Log;
use Illuminate\Console\Command;
use App\Models\Users\User;

class ChangeFeatureMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-feature-member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes current featured member.';

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
        $id = User::random()->id;
        $setting = Settings::get('featured_member');
        while($id == $setting) {
            $id = User::random()->id;
        }

        DB::table('site_settings')->where('key', 'featured_member')->update(['value' => $id]);
    }
}
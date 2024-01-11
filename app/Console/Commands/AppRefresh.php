<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Supports\InstallSupport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class AppRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:app-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->environment('local', 'demo')) {
            // drop all tables
            Schema::dropAllTables();
            sleep(1);
            InstallSupport::createDatabaseTables();
            sleep(1);
            $user = User::createUser([
                'first_name' => 'Liam',
                'last_name' => 'P',
                'email' => 'demo@example.com',
                'password' => '123456',
                'is_super_admin' => 1,
            ]);

            InstallSupport::createPrimaryData($user);

            update_settings(1,[
                'l_key' => '2a7edda9e6c656fac238cc0322849929',
            ],true);


            update_settings(1,[
                'openai_api_key' => 'demo',
            ],true);

            $this->info('App refreshed');

        }
    }
}

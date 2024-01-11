<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwitchProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'switch:project {item}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function setUid($uid)
    {
        $config = file_get_contents(base_path('config/app.php'));
        $config = preg_replace("/'uid' => '(.*)',/", "'uid' => '$uid',", $config);
        file_put_contents(base_path('config/app.php'), $config);
    }

    protected function setVersion($version)
    {
        $config = file_get_contents(base_path('config/app.php'));
        $config = preg_replace("/'version' => '(.*)',/", "'version' => '$version',", $config);
        file_put_contents(base_path('config/app.php'), $config);
    }

    protected function setItemId($item_id)
    {
        $config = file_get_contents(base_path('config/app.php'));
        $config = preg_replace("/'item_id' => '(.*)',/", "'item_id' => '$item_id',", $config);
        file_put_contents(base_path('config/app.php'), $config);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $item = $this->argument('item');

        $this->info("Switching to project: $item");

        switch ($item)
        {
            case 'webify':

                $app_config = require base_path('src/office/config.php');

                $this->info('Switching to Webify');
                $this->info('Copying tailwind.config.js');
                copy(base_path('src/webify/tailwind.config.js'), base_path('tailwind.config.js'));
                copy(base_path('src/webify/vite.config.js'), base_path('vite.config.js'));

                $this->info('Setting uid to webify');
                $this->setUid('webify');

                $this->info('Setting version to '.$app_config['version']);
                $this->setVersion($app_config['version']);

                $this->info('Setting item_id to '.$app_config['item_id']);
                $this->setItemId($app_config['item_id']);

                break;

            case 'office-saas':

                $office_config = require base_path('src/office/config.php');

                $this->info('Switching to Office');
                $this->info('Copying tailwind.config.js');
                copy(base_path('src/office/tailwind.config.js'), base_path('tailwind.config.js'));
                copy(base_path('src/office/vite.config.js'), base_path('vite.config.js'));

                $this->info('Setting uid to office');
                $this->setUid('office');

                $this->info('Setting version to '.$office_config['version']);
                $this->setVersion($office_config['version']);

                $this->info('Setting item_id to '.$office_config['item_id']);
                $this->setItemId($office_config['item_id']);

                break;

                case 'course':

                    $this->info('Switching to Course');
                    $this->info('Copying tailwind.config.js');
                    copy(base_path('src/course/tailwind.config.js'), base_path('tailwind.config.js'));
                    copy(base_path('src/course/vite.config.js'), base_path('vite.config.js'));

                    $this->info('Setting uid to course');
                    $this->setUid('course');

                    break;

            case 'mydesk':

                break;

            default:
                $this->error('Project not found');
                break;
        }

        return Command::SUCCESS;
    }
}

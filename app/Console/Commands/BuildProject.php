<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BuildProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:project {project}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $project;
    protected $build_dir;
    protected $file_system;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $buildBasePath = base_path('_build');

        $this->project = $this->argument('project');
        $item = $this->project;

        $this->build_dir = $buildBasePath.'/'.$item;
        $this->file_system = new Filesystem();

        if(!$this->project)
        {
            $this->error('Item not found');
            return Command::FAILURE;
        }

        $this->info('Building item: ' . $item);

        $fileSystem = $this->file_system;
        $buildDir = $this->build_dir;

        if(!$fileSystem->exists($buildDir))
        {
            $fileSystem->makeDirectory($buildDir);

            $this->info('Created build directory: '.$buildDir);
        }

        // Clean build directory
        $fileSystem->cleanDirectory($buildDir);

        $this->info('Cleaned build directory: '.$buildDir);

        // Copy common files and folders
        $this->info('Copying common files and folders');

        $commonFilesAndFolders = $this->commonFilesAndFolders();

        $this->copyFilesAndFolders($commonFilesAndFolders);

        switch ($item)
        {
            case 'office':

                $this->info('Building Office');

                break;

            case 'office-saas':

                $this->info('Building Office SaaS');

                $this->copyFilesAndFolders([
                    'app/Http/Controllers/OfficeController.php',
                    'app/Http/Controllers/SuperAdminController.php',
                    'lights/office/*',
                    'resources/views/common/*',
                    'resources/views/office/*',
                    'resources/views/super-admin/*',
                    'resources/views/website/*',
                    'uploads/media/sample-image.jpg',
                    'uploads/media/sample-image-saas.jpg',
                ]);

                $this->info('Office SaaS build complete');


                break;

            case 'mydesk':

                $this->info('Building MyDesk');

                $this->copyFilesAndFolders([
                    'app/Http/Controllers/OfficeController.php',
                    'app/Http/Controllers/SuperAdminController.php',
                    'lights/office/*',
                    'resources/views/common/*',
                    'resources/views/flashcards/*',
                    'resources/views/office/*',
                    'resources/views/super-admin/*',
                    'resources/views/website/*',
                    'uploads/media/sample-image.jpg',
                    'uploads/media/sample-image-saas.jpg',
                    'uploads/media/sample-image-mydesk.jpg',
                    'uploads/system/mydesk-blue.png',
                ]);


                break;

            default:
                $this->error('Item not found');
                return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function copyFilesAndFolders($files_and_folders)
    {
        $fileSystem = $this->file_system;

        $base_path = base_path();

        foreach ($files_and_folders as $file_or_folder)
        {
            $full_path = $base_path.'/'.$file_or_folder;

            //If ends with /*, copy all files and folders
            if(substr($file_or_folder, -2) == '/*')
            {
                $src_folder = substr($file_or_folder, 0, -2);
                $target_folder = substr($file_or_folder, 0, -2);

                $fileSystem->copyDirectory($base_path.'/'.$src_folder, $this->build_dir.'/'.$target_folder);

            }
            else
            {
                if(!$fileSystem->exists($full_path))
                {
                    $this->error('File or folder not found: '.$full_path);
                    continue;
                }
                //Check if folder exists
                $folder = dirname($file_or_folder);

                if(!$fileSystem->exists($this->build_dir.'/'.$folder))
                {
                    $this->info('Creating folder: '.$folder);
                    $fileSystem->makeDirectory($this->build_dir.'/'.$folder, 0755, true);
                }

                $copy = $fileSystem->copy($full_path, $this->build_dir.'/'.$file_or_folder);

                ray($copy);

            }
        }

    }

    private function commonFilesAndFolders()
    {
        return [
            'app/Console/Kernel.php',
            'app/Exceptions/Handler.php',
            'app/Http/Controllers/BaseController.php',
            'app/Http/Controllers/Controller.php',
            'app/Http/Controllers/InstallController.php',
            'app/Http/Controllers/SystemController.php',
            'app/Http/Middleware/*',
            'app/Http/Kernel.php',
            'app/Mail/UserPasswordReset.php',
            'app/Models/*',
            'app/Providers/*',
            'app/Supports/*',
            'assets/build/*',
            'assets/css/*',
            'assets/images/*',
            'assets/js/*',
            'assets/lib/*',
            'assets/android-chrome-192x192.png',
            'assets/android-chrome-512x512.png',
            'assets/apple-touch-icon.png',
            'assets/browserconfig.xml',
            'assets/favicon-16x16.png',
            'assets/favicon-32x32.png',
            'assets/favicon.ico',
            'assets/mstile-150x150.png',
            'assets/safari-pinned-tab.svg',
            'assets/site.webmanifest',
            'bootstrap/*',
            'config/*',
            'database/*',
            'lang/*',
            'lights/index.php',
            'resources/js/*',
            'resources/src/*',
            'resources/views/layouts/*',
            'resources/views/errors/*',
            'routes/*',
            'storage/app/public/*',
            'storage/app/index.php',
            'storage/framework/*',
            'storage/logs/index.php',
            'tests/*',
            'uploads/index.php',
            'uploads/default.png',
            'uploads/logo/index.php',
            'uploads/media/index.php',
            'uploads/shares/index.php',
            'uploads/system/*',
            'vendor/*',
            'artisan',
            'composer.json',
            'composer.lock',
            'index.php',
            'package.json',
            'phpunit.xml',
            'README.md',
            'tailwind.config.js',
            'vite.config.js',
        ];
    }
}

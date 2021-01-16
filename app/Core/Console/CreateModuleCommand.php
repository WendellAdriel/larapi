<?php

namespace LarAPI\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateModuleCommand extends Command
{
    protected $signature = 'make:module
                            {module : The module name}';
    protected $description = 'Creates a new module for your application (disabled by default)';

    public function handle()
    {
        $moduleName = Str::studly($this->argument('module'));
        $modulePath = app_path("Modules/$moduleName");
        \mkdir($modulePath, 0755, true);

        foreach ($this->moduleStructure() as $directory) {
            $path = "$modulePath/$directory";
            \mkdir($path, 0755, true);
            $this->addGitKeepFile($path);
        }

        $this->addModuleRouting($modulePath);

        foreach ($this->commonDirectories() as $directory) {
            $path = app_path("$directory/$moduleName");
            \mkdir($path, 0755, true);
            $this->addGitKeepFile($path);
        }

        $this->info("Module '$moduleName' created successfully!");
        $this->warn('You need to enable this module when you want to by adding it to config/modules.php file');
    }

    /**
     * @return array
     */
    private function moduleStructure(): array
    {
        return [
            'Commands', 'Controllers', 'Events', 'Listeners', 'Requests',
            'Responses', 'Services', 'Support', 'Traits'
        ];
    }

    /**
     * @param string $modulePath
     * @return void
     */
    private function addModuleRouting(string $modulePath): void
    {
        $path = "$modulePath/Routing";
        \mkdir($path, 0755, true);

        $commonRoutingPath = app_path('Modules/Common/Routing');
        $routingFile       = 'v1.php';

        \copy("$commonRoutingPath/$routingFile", "$path/$routingFile");
    }

    /**
     * @return array
     */
    private function commonDirectories(): array
    {
        return ['Models', 'Repositories'];
    }

    /**
     * @param string $path
     * @return void
     */
    private function addGitKeepFile(string $path): void
    {
        $gitKeep = \fopen("$path/.gitkeep", 'w');
        \fwrite($gitKeep, '');
        \fclose($gitKeep);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $repositoryBasePath = app_path('Repositories/Eloquent');
        $interfaceBasePath = app_path('Repositories/Contracts');

        $repositoryFiles = $this->getPhpFiles($repositoryBasePath);

        foreach ($repositoryFiles as $file) {
            $relativePath = str_replace([$repositoryBasePath . DIRECTORY_SEPARATOR, '.php'], '', $file);
            $className = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            $repositoryClass = "App\\Repositories\\Eloquent\\{$className}";
            $interfaceClass = "App\\Repositories\\Contracts\\{$className}Interface";

            if (interface_exists($interfaceClass)) {
                $this->app->bind($interfaceClass, $repositoryClass);
            }
        }
    }

    private function getPhpFiles($directory)
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $phpFiles = [];

        foreach ($rii as $file) {
            if (!$file->isDir() && $file->getExtension() === 'php') {
                $phpFiles[] = $file->getPathname();
            }
        }

        return $phpFiles;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
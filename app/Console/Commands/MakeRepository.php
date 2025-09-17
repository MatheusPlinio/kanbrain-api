<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a interface and repository for the domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $nameParts = explode('/', $name);
        $className = array_pop($nameParts);
        $subPath = implode('/', $nameParts);
        $namespacePath = implode('\\', $nameParts);

        $interfaceDir = app_path("Repositories/Contracts" . ($subPath ? "/$subPath" : ''));
        $eloquentDir = app_path("Repositories/Eloquent" . ($subPath ? "/$subPath" : ''));

        $interfaceName = "{$className}RepositoryInterface";
        $repositoryName = "{$className}Repository";

        File::ensureDirectoryExists($interfaceDir);
        File::ensureDirectoryExists($eloquentDir);

        $interfacePath = "$interfaceDir/{$interfaceName}.php";
        $repositoryPath = "$eloquentDir/{$repositoryName}.php";

        if (!File::exists($interfacePath)) {
            File::put($interfacePath, $this->getInterfaceTemplate($interfaceName, $namespacePath));
            $this->info("Interface criada: {$interfacePath}");
        } else {
            $this->warn("A interface {$interfaceName} já existe.");
        }

        if (!File::exists($repositoryPath)) {
            File::put($repositoryPath, $this->getRepositoryTemplate($repositoryName, $interfaceName, $namespacePath));
            $this->info("Repository criado: {$repositoryPath}");
        } else {
            $this->warn("O repositório {$repositoryName} já existe.");
        }

        return 0;
    }

    protected function getInterfaceTemplate(string $interfaceName, string $namespacePath): string
    {
        $namespace = 'App\\Repositories\\Contracts' . ($namespacePath ? "\\$namespacePath" : '');

        return <<<PHP
<?php

namespace {$namespace};

interface {$interfaceName}
{
    //
}
PHP;
    }

    protected function getRepositoryTemplate(string $repositoryName, string $interfaceName, string $namespacePath): string
    {
        $interfaceNamespace = 'App\\Repositories\\Contracts' . ($namespacePath ? "\\$namespacePath" : '');
        $repositoryNamespace = 'App\\Repositories\\Eloquent' . ($namespacePath ? "\\$namespacePath" : '');

        return <<<PHP
<?php

namespace {$repositoryNamespace};

use {$interfaceNamespace}\\{$interfaceName};

class {$repositoryName} implements {$interfaceName}
{
    //
}
PHP;
    }
}
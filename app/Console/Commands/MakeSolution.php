<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeSolution extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:solution';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'AbstractSolution';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new AoC solution class';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return is_dir(app_path('Solutions')) ? $rootNamespace.'\\Solutions' : $rootNamespace;
    }

    protected function getStub(): string
    {
        return $this->laravel->basePath(trim('/stubs/solution.stub', '/'));
    }
}

<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;

class CrudGeneratorCommand extends Command
{
    protected $signature = 'crud:generate
        {model : Model name (singular). Example: User}
        {--y|yes : Yes to all interactive questions.}
        {fields* : Fields with its type. Example name:string active:boolean}';

    protected $description = 'CRUD generator for eloquent models.';

    protected $recipes = [
        \App\Generators\BladeFilesRecipe::class,
        \App\Generators\ControllerRecipe::class,
        \App\Generators\MigrationRecipe::class,
        \App\Generators\ModelRecipe::class,
        \App\Generators\RequestRecipe::class,
        \App\Generators\ResourceRecipe::class,
        \App\Generators\RoutesRecipe::class,
        \App\Generators\VueFilesRecipe::class,
    ];

    public function handle()
    {
        $model  = studly_case(str_singular($this->argument('model')));
        $fields = $this->argument('fields');

        collect($this->recipes)->each(function ($recipeClass) use ($model, $fields) {
            $recipeClassName = collect(explode('\\', $recipeClass))->last();
            $recipeName = str_replace('-', ' ', kebab_case(str_replace('Recipe', '', $recipeClassName)));
            $recipe = new $recipeClass($model, $fields, $this);
            $this->comment("Making $recipeName...");
            $result = $recipe->make();

            if ($result instanceof Collection) {
                foreach ($result as $filename) {
                    $this->info($filename);
                }

                return;
            }
            $this->info($result);
        });

        $this->info("\nCRUD for $model created successfully.");
    }
}

<?php

namespace App\Generators;

use App\Generators\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Recipe
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $fields;

    /**
     * @var \Illuminate\Console\Command
     */
    protected $command;

    public function __construct($model, $fields, Command $command)
    {
        $this->model   = $model;
        $this->fields  = collect($fields);
        $this->command = $command;
    }

    public function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    public function createDir($pathFromRoot)
    {
        if (!file_exists($path = base_path($pathFromRoot))) {
            mkdir($path, 0777, true);
        }
    }

    public function getVariableFormSingular($model)
    {
        return lcfirst($model);
    }

    public function getVariableFormPlural($model)
    {
        return lcfirst($model);
    }

    public function getUrlForm($model)
    {
        return str_plural(kebab_case($model));
    }

    public function getTitleForm($model)
    {
        return title_case(str_replace('-', ' ', kebab_case($model)));
    }

    public function addRoute($routeTemplate)
    {
        return $this->appendToFile($this->routesFile(), $routeTemplate);
    }

    public function appendToFile($filepath, $content)
    {
        return File::append($filepath, $content);
    }

    public function routesFile()
    {
        return base_path('routes/web.php');
    }

    public function getFieldNames() : Collection
    {
        return $this->getFields()->map->name;
    }

    public function getFields() : Collection
    {
        return $this->fields->mapInto(Field::class);
    }

    public function confirm(string $question, bool $default = false) : bool
    {
        return $this->command->confirm($question, $default);
    }

    public function createFileFromStub(string $stubName, $search, $replace, $filename) : string
    {
        $template = str_replace($search, $replace, $this->getStub($stubName));

        if ($this->isSameFile(base_path($filename), $template)) {
            return "identical $filename";
        }

        if (!file_exists(base_path($filename))) {
            file_put_contents(base_path($filename), $template);

            return "created $filename";
        }

        if ($this->command->option('yes') ||
            $this->confirm("The file $filename already exists, do you want to replace it?")) {
            file_put_contents(base_path($filename), $template);

            return "replaced $filename";
        }

        return "skipped $filename";
    }

    public function isSameFile($filename, $template)
    {
        if (!file_exists($filename)) {
            return false;
        }

        $fileContents = file_get_contents($filename);

        return $fileContents == $template;
    }

    public function indent(int $indented, string $string) : string
    {
        return "\n" . str_repeat(' ', $indented) . $string;
    }
}

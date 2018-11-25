<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;

class BladeFilesRecipe extends Recipe
{
    public function make()
    {
        $created = collect();
        $created->push($this->addBladeFile('BladeIndex', 'index'));
        $created->push($this->addBladeFile('BladeCreate', 'create', 'form'));
        $created->push($this->addBladeFile('BladeEdit', 'edit', 'form'));

        return $created;
    }

    public function addBladeFile($slug, $blade, $component = null) : string
    {
        $modelUrlForm = $this->getUrlForm($this->model);
        $component    = $component ?? $blade;
        $component    = "$modelUrlForm-$component";
        $filename     = "resources/views/$modelUrlForm/$blade.blade.php";
        $this->createDir("resources/views/$modelUrlForm");

        return $this->createFileFromStub(
            $slug,
            ['dummy-component', 'DummyClassTitle', 'dummyVariable'],
            [$component, $this->getTitleForm($this->model), $this->getVariableFormSingular($this->model)],
            $filename
        );
    }
}

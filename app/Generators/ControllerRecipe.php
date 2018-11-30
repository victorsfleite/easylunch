<?php

namespace App\Generators;

class ControllerRecipe extends Recipe
{
    public function make()
    {
        $filename = "app/Http/Controllers/{$this->model}Controller.php";

        return $this->createFileFromStub(
            'Controller',
            [
                'DummyClass',
                'dummyVariable',
                'dummy-url',
            ],
            [
                $this->model,
                $this->getVariableFormSingular($this->model),
                $this->getUrlForm($this->model),
            ],
            $filename
        );
    }
}

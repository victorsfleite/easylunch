<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;

class VueFilesRecipe extends Recipe
{
    protected $componentsPath  = 'resources/js/components';
    protected $componentsIndex = 'resources/js/components/index.js';

    public function make()
    {
        $created = collect();

        $created->push($this->addVueComponent('index', 'VueIndex'));
        $created->push($this->addVueComponent('form', 'VueForm'));

        return $created;
    }

    public function addVueComponent($filename, $stub)
    {
        $resourceName   = $this->getUrlForm($this->model);
        $componentsPath = $this->componentsPath;
        $componentName  = "$resourceName-$filename";
        $componentDir   = "$componentsPath/$resourceName";
        $file           = "$componentDir/$filename.vue";

        $this->createDir($componentDir);

        $this->appendToFile(
            base_path($this->componentsIndex),
            "Vue.component('$componentName', require('./$resourceName/$filename'));\n"
        );

        return $this->createFileFromStub(
            $stub,
            [
                'DummyClassTitle',
                'dummy-url',
                'dummy_url_param',
                'DummyResourceColumns',
                'DummyInputFields',
            ],
            [
                $this->getTitleForm($this->model),
                $this->getUrlForm($this->model),
                snake_case(str_singular($this->getUrlForm($this->model))),
                $this->getResourceColumns(),
                $this->getInputFields(),
            ],
            $file
        );
    }

    public function getResourceColumns()
    {
        $columns = $this->getFieldNames()->implode("', '");

        return "'$columns'";
    }

    public function getInputFields()
    {
        return $this->getFields()->map(function (Field $field) {
            $component = $field->createVueFormComponent();

            return "\n" . str_repeat(' ', 12) . "$component";
            // return "\n{$field->formComponent}(:form=\"form\", field=\"{$field->name}\", " .
            //     "label=\"{$field->titleName()}\", v-model=\"form.{$field->name}\")";
        })->implode('');
    }
}

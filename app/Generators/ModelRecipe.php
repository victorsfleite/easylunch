<?php

namespace App\Generators;

use App\Generators\Traits\Stubs;

class ModelRecipe extends Recipe
{
    public function make()
    {
        $this->createDir('app/Models');
        $this->createDir('app/Traits/Models');
        $created = collect();

        $created->push($this->createFileFromStub(
            'TraitSearchable',
            null,
            null,
            'app/Traits/Models/Searchable.php'
        ));

        $created->push($this->createFileFromStub(
            'Model',
            ['DummyClass', 'DummyFillables', 'DummySearchableFields'],
            [$this->model, $this->getFillable(), $this->getSearchableFields()],
            "app/Models/{$this->model}.php"
        ));

        return $created;
    }

    private function getFillable()
    {
        return $this->fields->map(function ($field) {
            list($field) = explode(':', $field);

            return "\n" . str_repeat(' ', 8) . "'$field',";
        })->implode('');
    }

    public function getSearchableFields()
    {
        return $this->getFields()->filter->isSearchable()->map->name->map(function ($field) {
            return "\n" . str_repeat(' ', 8) . "'$field',";
        })->implode('');
    }
}

<?php

namespace App\Generators;

class RequestRecipe extends Recipe
{
    public function make()
    {
        $created = collect();

        $this->createDir('app/Http/Requests');

        $created->push($this->createFileFromStub(
            'Request',
            ['DummyClass', 'DummyRequestRules'],
            [$this->model, $this->getRequestRules($this->fields)],
            "app/Http/Requests/{$this->model}Request.php"
        ));

        $created->push($this->createFileFromStub(
            'BulkDestroyRequest',
            'DummyClass',
            $this->model,
            "app/Http/Requests/{$this->model}BulkDestroyRequest.php"
        ));

        return $created;
    }

    private function getRequestRules($fields)
    {
        return collect($fields)->map(function ($field) {
            list($field) = explode(':', $field);

            return $this->indent(12, "'$field' => 'required',");
        })->implode('');
    }
}

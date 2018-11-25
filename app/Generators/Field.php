<?php

namespace App\Generators;

class Field
{
    /** @var string */
    public $name;
    /** @var string */
    public $type;
    /** @var \Illuminate\Support\Collection */
    public $options;

    public function __construct(string $field)
    {
        list($this->name, $this->type, $options) = explode(':', $field) + [null, 'string', null];

        $this->options = collect($options ?? []);
    }

    public function isSearchable() : bool
    {
        return collect(['string', 'text'])->contains($this->type);
    }

    public function label()
    {
        return title_case(str_replace('_', ' ', $this->name));
    }

    public function getVueFormComponent()
    {
        switch ($this->type) {
            case 'text':
                return 'input-textarea';
            case 'date':
            case 'dateTime':
                return 'input-text';
            default:
                return 'input-text';
        }
    }

    public function inputType()
    {
        switch ($this->type) {
            case 'date':
            case 'dateTime':
                return 'date';
            case 'unsignedInteger':
            case 'tinyInteger':
            case 'bigInteger':
            case 'integer':
            case 'float':
            case 'decimal':
                return 'number';
            default:
                return 'text';
        }
    }

    public function createVueFormComponent()
    {
        $inputType      = $this->needsType() ? "type=\"{$this->inputType()}\", " : '';
        $inputComponent =$this->getVueFormComponent();
        $label          = $this->label();

        return "$inputComponent($inputType:form=\"form\", field=\"{$this->name}\", " .
            "label=\"$label\", v-model=\"form.{$this->name}\")";
    }

    public function needsType() : bool
    {
        return collect([
            'integer', 'date', 'dateTime', 'email', 'decimal', 'float',
        ])->contains($this->type);
    }
}

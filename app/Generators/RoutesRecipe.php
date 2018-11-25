<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;

class RoutesRecipe extends Recipe
{
    public function make()
    {
        $resource = $this->getUrlForm($this->model);
        $this->addRoute("\nRoute::get('$resource/index', '{$this->model}Controller@list')->name('$resource');\n");
        $this->addRoute(
            "Route::post('$resource/bulk-destroy', '{$this->model}Controller@bulkDestroy')" .
            "->name('$resource.bulk-destroy');\n"
        );
        $this->addRoute("Route::resource('$resource', '{$this->model}Controller');\n");

        return "Added routes for '/$resource' resource in routes/web.php";
    }
}

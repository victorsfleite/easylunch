<?php

namespace App\Http\Controllers;

use App\Http\Requests\Options\StoreRequest;
use App\Http\Requests\Options\UpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index(Request $request)
    {
        return DataResource::collection(Option::orderBy('name')->search($request->search)->get());
    }

    public function store(StoreRequest $request)
    {
        return Option::create($request->validated());
    }

    public function update(UpdateRequest $request, Option $option)
    {
        $option->update($request->validated());

        return $option;
    }

    public function destroy(Option $option)
    {
        $option->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuBulkDestroyRequest;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\DataResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class MenuController extends Controller
{
    public function list(Request $request)
    {
        return view('menus.index', compact('request'));
    }

    public function index(Request $request)
    {
        return DataResource::collection(
            QueryBuilder::for(Menu::class)->search($request->search)->paginate()
        );
    }

    public function create()
    {
        return view('menus.create');
    }

    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    public function store(MenuRequest $request)
    {
        return DataResource::make(Menu::create($request->validated()));
    }

    public function show(Menu $menu)
    {
        return DataResource::make($menu);
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        return DataResource::make($menu);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(MenuBulkDestroyRequest $request)
    {
        Menu::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

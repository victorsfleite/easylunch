<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuBulkDestroyRequest;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\DataResource;
use App\Models\Menu;
use App\Notifications\MenuCreated;
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
            QueryBuilder::for(Menu::class)->with('orders')->search($request->search)->paginate()
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
        $menu = Menu::create($request->validated());

        if ($request->options) {
            $menu->syncOptions($request->options);
        }

        $this->addMediaIfExists($menu, 'new_image', 'image');

        user()->notify(new MenuCreated);

        return DataResource::make($menu);
    }

    public function show(Request $request, Menu $menu)
    {
        return $request->ajax()
            ? DataResource::make($menu)
            : view('menus.show', compact('menu'));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());

        $this->addMediaIfExists($menu, 'new_image', 'image');

        if ($request->options) {
            $menu->syncOptions($request->options);
        }

        return DataResource::make($menu);
    }

    public function destroy(Menu $menu)
    {
        abort_if($menu->orders()->completed()->count(), Response::HTTP_BAD_REQUEST);

        $menu->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(MenuBulkDestroyRequest $request)
    {
        Menu::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

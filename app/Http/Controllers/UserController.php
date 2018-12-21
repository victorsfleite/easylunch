<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\DataResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\UserBulkDestroyRequest;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;

class UserController extends Controller
{
    public function list(Request $request)
    {
        return view('users.index', compact('request'));
    }

    public function index(Request $request)
    {
        return DataResource::collection(
            QueryBuilder::for(User::class)->search($request->search)->paginate()
        );
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function store(UserCreateRequest $request)
    {
        return DataResource::make(User::create($request->validated()));
    }

    public function show(User $user)
    {
        return DataResource::make($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return DataResource::make($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(UserBulkDestroyRequest $request)
    {
        User::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

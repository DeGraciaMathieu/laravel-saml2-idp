<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate();

        return view('admin.user.index', ['users' => $users]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', ['user' => $user]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());

        return redirect()
            ->route('user.edit', ['id' => $user->id])
            ->with('success','User created successfully');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        User::find($id)->update($request->all());

        return redirect()
            ->route('user.edit', ['id' => $id])
            ->with('success','User updated successfully');
    }
}

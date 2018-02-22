<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate();

        return view('admin.client.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return view('admin.client.edit', ['client' => $client]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());

        return redirect()->route('client.edit', ['id' => $client->id])->with('success','Client created successfully');
    }

    public function update(UpdateClientRequest $request, $id)
    {
        Client::find($id)->update($request->all());

        return redirect()->route('client.index')->with('success','Client updated successfully');
    }
}

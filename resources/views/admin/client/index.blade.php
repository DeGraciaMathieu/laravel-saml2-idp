@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ol class="breadcrumb">
              <li><a href="/admin">Admin</a></li>
              <li class="active">Clients</li>
            </ol>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif  
            <div class="panel panel-default">
                <div class="panel-heading">Tableau de bord</div>
                <div class="panel-body">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>entity_id</th>
                        <th>endpoint</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                      <tr>
                        <td>{{$client->entity_id}}</td>
                        <td>{{$client->endpoint}}</td>
                        <td>
                            <a href="{{ route('client.edit', $client->id) }}" class="btn btn-info btn-rounded btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-rounded btn-sm">Remove</button>
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('client.create') }}" class="btn btn-primary btn-sd">Créer un client</a>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection

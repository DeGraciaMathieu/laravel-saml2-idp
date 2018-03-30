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
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-rounded btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-rounded btn-sm">Remove</button>
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

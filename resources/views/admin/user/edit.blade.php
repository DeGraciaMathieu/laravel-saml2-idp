@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ol class="breadcrumb">
              <li><a href="/admin">Admin</a></li>
              <li><a href="{{ route('user.index') }}">Users</a></li>
              <li class="active">{{ $user->name }}</li>
            </ol>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif              
            <div class="panel panel-default">
                <div class="panel-heading">Tableau de bord</div>
                <div class="panel-body">
                <form method='POST' action="{{ route('user.update', $user->id) }}">
                    {!! method_field('patch') !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <strong>name:</strong>
                                <input type="input" name="name" class='form-control' value='{{$user->name}}'>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <strong>email:</strong>
                                <input type="input" name="email" class='form-control' value='{{$user->email}}'>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('uuid') ? 'has-error' : '' }}">
                                <strong>uuid:</strong>
                                <input type="input" name="uuid" class='form-control' value='{{$user->uuid}}'>
                                @if ($errors->has('uuid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('uuid') }}</strong>
                                    </span>
                                @endif                                  
                            </div>
                        </div>                        
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </div>                    
                </form>               
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Options</div>
                <div class="panel-body">
                <form method='POST' action="{{ route('user.update', $user->id) }}">
                    {!! method_field('patch') !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>recover password:</strong>
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </div>                       
                    </div>                    
                </form>               
                </div>
            </div>            
        </div>
    </div>
</div>
@endsection

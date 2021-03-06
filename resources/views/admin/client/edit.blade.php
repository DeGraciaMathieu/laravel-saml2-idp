@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif              
            <ol class="breadcrumb">
              <li><a href="/admin">Admin</a></li>
              <li><a href="{{ route('client.index') }}">Clients</a></li>
              <li class="active">{{ $client->entity_id }}</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">Tableau de bord</div>
                <div class="panel-body">
                <form method='POST' action="{{ route('client.update', $client->id) }}">
                    {!! method_field('patch') !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('entity_id') ? 'has-error' : '' }}">
                                <strong>entity_id:</strong>
                                <input type="input" name="entity_id" class='form-control' value='{{$client->entity_id}}'>
                                @if ($errors->has('entity_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('entity_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('endpoint') ? 'has-error' : '' }}">
                                <strong>endpoint:</strong>
                                <input type="input" name="endpoint" class='form-control' value='{{$client->endpoint}}'>
                                @if ($errors->has('endpoint'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('endpoint') }}</strong>
                                    </span>
                                @endif                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('certificate') ? 'has-error' : '' }}">
                                <strong>certificate:</strong>
                                <textarea rows="5" name="certificate" class='form-control'>{{$client->certificate}}</textarea>
                                @if ($errors->has('certificate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('certificate') }}</strong>
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
        </div>
    </div>
</div>
@endsection

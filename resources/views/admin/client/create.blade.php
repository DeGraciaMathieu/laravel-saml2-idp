@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ol class="breadcrumb">
              <li><a href="/admin">Admin</a></li>
              <li><a href="{{ route('client.index') }}">Clients</a></li>
              <li class="active">Create</li>
            </ol>            
            <div class="panel panel-default">
                <div class="panel-heading">Tableau de bord</div>
                <div class="panel-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method='POST' action="{{ route('client.store') }}">
                    {!! method_field('POST') !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>entity_id:</strong>
                                <input type="input" name="entity_id" class='form-control' value="{{ old('entity_id') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>endpoint:</strong>
                                <input type="input" name="endpoint" class='form-control' value="{{ old('endpoint') }}">
                            </div>
                        </div>                                              
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </div>                    
                </form>               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

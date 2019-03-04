@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Create New Lobby</h1>
    <hr/>

    {!! Form::open(['url' => '/lobbies', 'class' => 'form-horizontal']) !!}
            @if (isset($game))
            <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('game', trans('lobbies.game'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('game_name', $game->name, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    {!! Form::hidden('game_id', $game->id) !!}
                </div>
            </div>
            @endif
                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', trans('lobbies.name'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('description', trans('lobbies.description'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('maxplayers') ? 'has-error' : ''}}">
                {!! Form::label('maxplayers', trans('lobbies.maxplayers'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('maxplayers', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('maxplayers', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status', trans('lobbies.status'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('status', [ 0 => 'Pending', 2 => 'Active', 3 => 'Complete'], null, ['class' => 'form-control']) !!}
                    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Create', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

</div>
@endsection
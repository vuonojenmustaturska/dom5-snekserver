@extends('layouts.app')

@section('content')
<div class="container">

    <h1>{{ $game->name }}</h1>
    <div class="row">
    <div class="table-responsive col-md-8">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th> {{ trans('games.owner') }} </th>
                    <td> {{ $game->user->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('games.address') }} </th>
                    <td> snek.earth </td>
                </tr>
                <tr>
                    <th> {{ trans('games.port') }} </th>
                    <td> {{ (30000+$game->id) }} </td>
                </tr>
                @if (Auth::check() && Auth::id() == $game->user->id)
                <tr>
                    <th> {{ trans('games.masterpw') }} </th>
                    <td> {{ $game->masterpw }} </td>
                </tr>
                @endif
                <tr>
                    <th> {{ trans('games.hours') }} </th>
                    <td> {{ ($game->hours) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('games.state') }} </th>
                    <td> {{ trans('games.state_'.$game->state) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('games.status') }} </th>
                    <td> {!! trans('games.status_'.$game->status) !!} </td>
                </tr>
            </tbody>
           
            <tfoot>
                @if (Auth::check() && Auth::id() == $game->user->id)
                <tr>
                    <td colspan="2">
                        <a href="{{ url('games/' . $game->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Game"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;Edit</a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['games', $game->id],
                            'style' => 'display:inline'
                        ]) !!}
                        @if (Auth::check() and Auth::id() == 1)
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Game',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!} 
                        @endif
                        {!! Form::close() !!} 
                    </td>

                </tr>

                @endif
            </tfoot>
            
        </table>
        <small class="text-muted">Server status is updated once per minute.</small>
    </div>
    <div class="col-md-4">
           <a href="{{ url('maps/' . $game->map->id) }}"><img src="{{ asset('/img/maps/'.$game->map->id.'-lg.png') }}" width="400" height="400" class="img-thumbnail"/></a>
    </div>
    </div>

    <div class="row">
    <div class="col-md-12">
    @if (Auth::check() && (Auth::id() == $game->user->id || Auth::user()->is_admin))
        @if ($game->state == 0)
            {!! Form::open([
                'method'=>'POST',
                'url' => ['games', $game->id, 'server-createnew'],
                'style' => 'display:inline'
            ]) !!}
                {!! Form::button('<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>&nbsp;Start pretender upload', array(
                        'type' => 'submit',
                        'class' => 'btn btn-success',
                        'title' => 'Start',
                        'onclick'=>'return confirm("Confirm start pretender upload?")'
                ));!!}
            {!! Form::close() !!} 
        @endif

        @if ($game->state >= 2)
            {!! Form::open([
    'method'=>'POST',
    'url' => ['games', $game->id, 'server-start'],
    'style' => 'display:inline'
]) !!}
    {!! Form::button('<span class="glyphicon glyphicon-play" aria-hidden="true"></span>&nbsp;Start', array(
            'type' => 'submit',
            'class' => 'btn btn-success',
            'title' => 'Start',
            'onclick'=>'return confirm("Confirm start?")'
    ));!!} {!! Form::close() !!} 
        @endif
        

    @endif
    @if (Auth::check() && Snek\GamePermission::canRestart($game, Auth::user()))
        {!! Form::open([
        'method'=>'POST',
        'url' => ['games', $game->id, 'server-restart'],
        'style' => 'display:inline'
    ]) !!}
        {!! Form::button('<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp;Restart', array(
                'type' => 'submit',
                'class' => 'btn btn-warning',
                'title' => 'Restart',
                'onclick'=>'return confirm("Confirm restart?")'
        ));!!} {!! Form::close() !!} 
        @endif
    @if (Auth::check() && Auth::user()->is_admin)
    <a href="{{ url('lobbies/createfromgame/' . $game->id) }}" class="btn btn-primary" title="Create lobby"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;Create lobby</a>
    @endif
    </div>
    </div>
    <div class="row">
            <div class="col-md-6">
            <h2>Game rules:</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Setting</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($game->getNonDefaultSettings() as $key => $value)
                            @if ($value !== NULL && !is_array($value))
                                @if ($key == 'storyevents')
                                    <tr>
                                        <td>{{ trans('games.'.$key) }}</td><td>{{ trans('games.storyevents_'.$value) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ trans('games.'.$key) }}</td><td>{{ $value }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($game->mods) > 0)
        <div class="col-md-6">
            <h2>Mods:</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Version</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($game->orderedMods() as $mod)
                        <tr>
                            <td>{{ $mod->pivot->load_order }}</td><td>{{ $mod->name }}</td><td>{{ $mod->version }}</td><td>{{ $mod->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @if (Auth::id() == $game->user->id)
    <div class="row">
        <div class="col-md-12">
            <h2>{{ trans('games.journal') }}</h2>
            <pre>{{ $game->journal }}</pre>
        </div>
    </div>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">

    <h1>{{ $game->name }}</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="list-group">
                @foreach ($state->nations as $nationid => $nationdata)
                @if ($nationid > 4)
                  <a href="#" class="list-group-item col-md-4 {{ trans('serverstate.submitted_'.$nationdata['submitted'].'_class') }}" style="border-radius: 4px;">
                  	@if ($nationdata['submitted'] == 2)
                     <img src="/img/flagok.png" class="pull-left" />
                     @else
                     <img src="/img/flag_default.png" class="pull-left" />
                     @endif
                    <h4 class="list-group-item-heading">{{ $nationid }}: {{ $nations[$nationid]['name'] }} - {{ trans('games.era_'.$nations[$nationid]['era']) }}</h4>
                    <p class="list-group-item-text"><small>{{ $nations[$nationid]['epithet'] }}</small></p>
                    <p class="list-group-item-text"><small>{{ trans('serverstate.nationstatus_'.$nationdata['status']) }}&nbsp;{{ trans('serverstate.submitted_'.$nationdata['submitted']) }}&nbsp;{{ trans('serverstate.connected_'.$nationdata['connected']) }}</small></p>
                  </a>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    <br />
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
                            <tr>
                                <td>{{ trans('games.'.$key) }}</td><td>{{ $value }}</td>
                            </tr>
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
                            <th>Name</th>
                            <th>Version</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($game->mods as $mod)
                        <tr>
                            <td>{{ $mod->name }}</td><td>{{ $mod->version }}</td><td>{{ $mod->description }}</td>
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
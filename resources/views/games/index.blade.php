@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Games
    @if (Auth::check() && Auth::user()->is_vouched)
            <a href="{{ url('/games/create') }}" class="btn btn-primary btn-xs" title="Add New Game"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Create a new game</a>
    @endif
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> {{ trans('games.name') }} </th><th> {{ trans('games.port') }} </th><th> {{ trans('games.era') }} </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($games as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td><a href="{{ url('/games/' . $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->port }}</td><td>{{ trans('games.era_'.$item->era) }}</td>
                    <td>
                        <a href="{{ url('/games/' . $item->id) }}" class="btn btn-success btn-xs" title="View Game"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        @if (Auth::id() === $item->user->id)
                            <a href="{{ url('/games/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Game"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/games', $item->id],
                                'style' => 'display:inline'
                            ]) !!}
                                {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Game" />', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Delete Game',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                ));!!}
                        @endif
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $games->render() !!} </div>
    </div>

</div>
@endsection

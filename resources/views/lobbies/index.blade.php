@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Lobbies <a href="{{ url('/lobbies/create') }}" class="btn btn-primary btn-xs" title="Add New Lobby"><span class="glyphicon glyphicon-plus" aria-hidden="true"/></a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> {{ trans('lobbies.name') }} </th><th> {{ trans('lobbies.description') }} </th><th> {{ trans('lobbies.server_address') }} </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($lobbies as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td>{{ $item->name }}</td><td>{{ $item->description }}</td><td>{{ $item->server_address }}</td>
                    <td>
                        <a href="{{ url('/lobbies/' . $item->id) }}" class="btn btn-success btn-xs" title="View Lobby"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        <a href="{{ url('/lobbies/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Lobby"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['/lobbies', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Lobby" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Lobby',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $lobbies->render() !!} </div>
    </div>

</div>
@endsection

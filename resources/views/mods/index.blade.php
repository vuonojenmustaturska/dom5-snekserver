@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Mods 
    @if (Auth::check() && Auth::user()->is_vouched)
            <a href="{{ url('/mods/upload') }}" class="btn btn-primary btn-xs" title="Add New Mod"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>&nbsp;Upload new mods</a>
    @endif
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> {{ trans('mods.name') }} </th><th> {{ trans('mods.description') }} </th><th> {{ trans('mods.filename') }} </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($mods as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td><td>{{ $item->description }}</td><td>{{ $item->filename }}</td>
                    <td>
                        <a href="{{ url('/mods/' . $item->id) }}" class="btn btn-success btn-xs" title="View Mod"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        {{-- <a href="{{ url('/mods/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Mod"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['/mods', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Mod" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Mod',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!} --}}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $mods->render() !!} </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Maps 
    @if (Auth::check() && Auth::user()->is_vouched)
    <a href="{{ url('/maps/upload') }}" class="btn btn-primary btn-xs" title="Add New Map"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>&nbsp;Upload new maps</a>
    @endif
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> {{ trans('maps.name') }} </th><th> {{ trans('maps.description') }} </th><th> {{ trans('maps.filename') }} </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($maps as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td><a href="{{ url('/maps/' . $item->id) }}" title="View Map">{{ $item->name }}</a></td><td>{{ $item->description }}</td><td>{{ $item->filename }}</td>
                    <td>
                        <a href="{{ url('/maps/' . $item->id) }}" class="btn btn-success btn-xs" title="View Map"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        {{-- <a href="{{ url('/maps/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Map"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['/maps', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Map" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Map',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!} --}}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $maps->render() !!} </div>
    </div>

</div>
@endsection

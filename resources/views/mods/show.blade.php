@extends('layouts.app')

@section('content')
<div class="container">

    <h1>{{ $mod->name }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr><th> {{ trans('mods.name') }} </th><td> {{ $mod->name }} </td></tr><tr><th> {{ trans('mods.description') }} </th><td> {{ $mod->description }} </td></tr><tr><th> {{ trans('mods.filename') }} </th><td> {{ $mod->filename }} </td></tr>
                <tr><th> {{ trans('mods.version') }} </th><td> {{ $mod->version }} </td></tr>

            </tbody>
            {{-- <tfoot>
                <tr>
                    <td colspan="2">
                        <a href="{{ url('mods/' . $mod->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Mod"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['mods', $mod->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"/>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Mod',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ));!!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            </tfoot> --}}
        </table>
    </div>

</div>
@endsection
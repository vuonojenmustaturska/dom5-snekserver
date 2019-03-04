@extends('layouts.app')

@section('content')
<div class="container">

    <h1>{{ $lobby->game->name }}</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="list-group">
                @foreach ($lobby->lobbystats() as $stat)
                  <a href="#" class="list-group-item col-md-4 {{ trans('lobbies.class'.$stat->turn_status) }}" style="border-radius: 4px;">
                    @if (!empty($stat->nation['flag']))
                     <img src="{{ asset('assets/mods/'.$stat->nation['flag'].'.png') }}" class="pull-left" />
                     @else
                     <img src="{{ asset('/img/flag_default.png') }}" class="pull-left" />
                     @endif
                    @if (isset($signupsById[$stat->nation['nation_id']]))
                    <h4 class="list-group-item-heading">{{ $signupsById[$stat->nation['nation_id']]->user->name }}</h4>
                    @else
                    <h4 class="list-group-item-heading">{{  $stat->nation['name'] }}</h4>
                    @endif
                    <p class="list-group-item-text"><small>{{ $stat->nation['nation_id'] }}: {{ $stat->nation['name'] }}</small></p>
                    @if (Auth::check() && ($signupsById[$stat->nation['nation_id']]->id && Auth::id() == $signupsById[$stat->nation['nation_id']]->user_id) or (Auth::user()->is_admin))
                        <p class="list-group-item-text"><small>{{ $signupsById[$stat->nation['nation_id']]->password }}</small></p>
                    @endif
                    <p class="list-group-item-text"><small>{{ trans('lobbies.turn_status'.$stat->turn_status) }}</small></p>
                    @if (Auth::check() && (Auth::id() == $lobby->owner->id or Auth::id() == $signup->user->id))
                    <p class="list-group-item-text">
                    {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['lobbies', $lobby->id, 'remove', $signupsById[$stat->nation['nation_id']]->id],
                                'style' => 'display:inline'
                            ]) !!}
                                {!! Form::button('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>Remove', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs pull-right',
                                        'title' => 'Delete signup',
                                        'onclick'=>'return confirm("Confirm delete signup?")'
                                ));!!}
                            {!! Form::close() !!} </p> 
                    @endif
                  </a>
                @endforeach
            </div>
        </div>
    </div>
    <br />
    <div class="row">
    	<div class="col-md-12">
            @if ($lobby->signups->where('user_id', Auth::id())->count() == 0 && $lobby->game->state < 3)
    		    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#signupmodal">
                      Sign up
                    </button>
            @endif

            <div class="modal fade" id="signupmodal" tabindex="-1" role="dialog" aria-labelledby="signuplabel">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="signuplabel">Sign up</h4>
                  </div>
                  <div class="modal-body">
                  {!! Form::open(['url' => ['lobbies', $lobby->id, 'signup'], 'class' => 'form-horizontal']) !!}
                  		<div class="form-group">
			                {!! Form::label('password', trans('lobbies.password'), ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::text('password', null, ['class' => 'form-control']) !!}
			                </div>
			            </div>
                  	    <div class="form-group">
		                {!! Form::label('nationmod', trans('lobbies.nationmod'), ['class' => 'col-sm-3 control-label']) !!}
			                <div class="col-sm-6">
			                    {!! Form::select('nationmod', [1 => trans('lobbies.detected_nations'), 0 => trans('lobbies.nationmod_core'), 2 => trans('lobbies.fallback_nations')], 1, ['class' => 'form-control']) !!}
			                </div>
		            	</div>
			            <select id="nation-image-picker" class="image-picker show-labels show-html" name="nation">
                        @foreach($lobby->game->nations() as $nation)
                                <option data-nation-epithet="{{ $nation['epithet'] }}" data-img-src="{{ asset('assets/mods/'.$nation['flag'].'.png') }}" value="{{ $nation['nation_id'] }}">{{ $nation['name'] }}</option>
                        @endforeach
                    </select>
                  </div>
                  
                  <div class="modal-footer">
                    {!! Form::button('<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;Sign Up', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-primary',
                                        'title' => 'Sign up',
                                        'onclick'=>'return confirm("Confirm signup?")'
                                ));!!}
                  </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>

    	</div>
    </div>
    <br />
    <div class="row">
    	<div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr><th> {{ trans('lobbies.description') }} </th><td> {{ $lobby->description }} </td></tr>
                    <tr><th> {{ trans('lobbies.maxplayers') }} </th><td> {{ $lobby->maxplayers }} </td></tr>
                    <tr><th> {{ trans('games.era') }} </th><td> {{ trans('games.era_'.$lobby->game->era) }} </td></tr>
                    <tr><th> {{ trans('lobbies.server_address') }} </th><td>snek.earth:{{ 50000+$lobby->game->id }} </td></tr>
                    <tr><th> {{ trans('lobbies.turn') }} </th><td>{{ $lobby->turn }} </td></tr>
                </tbody>
                <tfoot>
                   {{-- <tr>
                        <td colspan="2">
                            <a href="{{ url('lobbies/' . $lobby->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Lobby"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                             {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['lobbies', $lobby->id],
                                'style' => 'display:inline'
                            ]) !!}
                                {!! Form::button('Sign up', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Delete Lobby',
                                        'onclick'=>'return confirm("Confirm delete?")'
                                ));!!}
                            {!! Form::close() !!} 
                        </td>
                    </tr>--}}
                </tfoot>
            </table>
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <th> {{ trans('maps.landprovinces') }} </th><td>{{ $mapprovs['land'] }}</a></td><th> {{ trans('maps.uwprovinces') }} </th><td> {{ $mapprovs['underwater'] }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('maps.swamps') }} </th><td>{{ $mapprovs['swamp'] }}</a></td><th> {{ trans('maps.caves') }} </th><td> {{ $mapprovs['cave'] }} </td>
                    </tr>
                                        <tr>
                        <th> {{ trans('maps.wastes') }} </th><td>{{ $mapprovs['waste'] }}</a></td><th> {{ trans('maps.forests') }} </th><td> {{ $mapprovs['forest'] }} </td>
                    </tr>
                    </tr>
                                        <tr>
                        <th> {{ trans('maps.farms') }} </th><td>{{ $mapprovs['farm'] }}</a></td><th> {{ trans('maps.mountainsinc') }} </th><td> {{ ($mapprovs['mountain']+$mapprovs['bordermountain']) }} </td>
                    </tr>
                    </tr>
                                        <tr>
                        <th> {{ trans('maps.seas') }} </th><td>{{ $mapprovs['sea'] }}</a></td><th> {{ trans('maps.deepseas') }} </th><td> {{ $mapprovs['deepsea'] }} </td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
        </div>
        <div class="col-md-4">
           <a href="{{ url('maps/' . $lobby->game->map->id) }}"><img src="{{ asset('/img/maps/'.$lobby->game->map->id.'-lg.png') }}" width="400" height="400" class="img-thumbnail"/></a>
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
                        @foreach($lobby->game->getUINonDefaultSettings() as $key => $value)
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
        @if(count($lobby->game->mods) > 0)
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
                        @foreach($lobby->game->orderedMods() as $mod)
                        <tr>
                            <td>{{ $mod->pivot->load_order }}</td><td>{{ $mod->name }}</td><td>{{ $mod->version }}</td><td>{{ $mod->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="col-md-6">
            <h2>Mods:</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No mods</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
.nation-defeated {
    background-color: lightgray;
    webkit-filter: grayscale(1);
}

.nation-pending {
    border-color: red;
}

.nation-played {
    border-color: green;
}

.nation-partial {
    border-color: orange;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
function populatenations(id)
{
	console.log(id);
	switch (id)
	{
		case "0":
			$.get('/json/lobbies/{{ $lobby->id }}/corenations', '', function (data) {
				$('#nation-image-picker option').remove();  
				var $select = $('#nation-image-picker');
				$.each(data,function(key, nation) 
				{
					if (nation.flag != null && nation.flag.length > 0)
					{
						$select.append('<option data-nation-epithet='+ nation.epithet +' data-img-src="/assets/mods/' + nation.flag + '.png" value=' + nation.nation_id + '>' + nation.name + '</option>');
					}
				    else
				    {
				    	$select.append('<option data-nation-epithet='+ nation.epithet +' data-img-src="{{ asset('/img/flag_default.png') }}" value=' + nation.nation_id + '>' + nation.name + '</option>');
				    }
				});
				$('#nation-image-picker').imagepicker({show_label: true});
			});
			break;
		case "1":
			$.get('/json/lobbies/{{ $lobby->id }}/nations', '', function (data) {
				$('#nation-image-picker option').remove();  
				var $select = $('#nation-image-picker');
				$.each(data,function(key, nation) 
				{
					if (nation.flag != null && nation.flag.length > 0)
					{
						$select.append('<option data-nation-epithet='+ nation.epithet +' data-img-src="/assets/mods/' + nation.flag + '.png" value=' + nation.nation_id + '>' + nation.name + '</option>');
					}
				    else
				    {
				    	$select.append('<option data-nation-epithet='+ nation.epithet +' data-img-src="{{ asset('/img/flag_default.png') }}" value=' + nation.nation_id + '>' + nation.name + '</option>');
				    }
				});
				$('#nation-image-picker').imagepicker({show_label: true});
			});
			break;
		case "2":
			$('#nation-image-picker option').remove();  

			for (i = 5; i < 251; i++)
			{
				$('#nation-image-picker').append('<option data-nation-epithet="Nation '+ i +'" data-img-src="{{ asset('/img/flag_default.png') }}" value=' + i + '>Nation ' + i + '</option>');
			}
				
			$('#nation-image-picker').imagepicker({show_label: true});
			break;
		default:
			console.log('asdf');
			break;

	}

}

$("#nationmod").select2({
	  minimumResultsForSearch: Infinity,
	  width: '100%'
	}).on('select2:select', function (e) { 
		console.log(e);
		populatenations(e.params.data.id);
	});


</script>
           <script src="{{ asset('/js/image-picker.min.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('/css/image-picker.css') }}">
            <script>
                $(document).ready(function() {
					$('#nation-image-picker').imagepicker({show_label: true});
                });
            </script>
            <style>
            .overflow-auto {
                max-height: calc(100vh - 210px);
                overflow-y: auto;
            }
    </style>
@endsection
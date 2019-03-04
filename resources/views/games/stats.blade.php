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
    </div>
    <div class="col-md-4">
           <a href="{{ url('maps/' . $game->map->id) }}"><img src="{{ asset('/img/maps/'.$game->map->id.'-lg.png') }}" width="400" height="400" class="img-thumbnail"/></a>
    </div>
    </div>

    <div class="row">

    </div>


    <div class="row">
        <div class="col-md-12">
        <div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#turntracker" aria-controls="turntracker" role="tab" data-toggle="tab">Turn tracker</a></li>
            @if ($game->id == 129) 
            <li role="presentation"><a href="#armysize" aria-controls="armysize" role="tab" data-identifier="armysize" data-toggle="tab">Army size</a></li>
            <li role="presentation"><a href="#forts" aria-controls="forts" role="tab" data-identifier="forts" data-toggle="tab">Forts</a></li>
            <li role="presentation"><a href="#income" aria-controls="income" role="tab" data-identifier="income" data-toggle="tab">Income</a></li>
            <li role="presentation"><a href="#gemincome" aria-controls="gemincome" role="tab" data-identifier="gemincome" data-toggle="tab">Gem income</a></li>
            <li role="presentation"><a href="#research" aria-controls="research" role="tab" data-identifier="research" data-toggle="tab">Research</a></li>
            <li role="presentation"><a href="#victorypoints" aria-controls="victorypoints" data-identifier="victorypoints" role="tab" data-toggle="tab">Victory points</a></li>
            <li role="presentation"><a href="#dominion" aria-controls="dominion" role="tab" data-identifier="dominion" data-toggle="tab">Dominion</a></li>
            <li role="presentation"><a href="#provinces" aria-controls="provinces" role="tab" data-identifier="provinces" data-toggle="tab">Provinces</a></li>
            @endif
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="turntracker">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nation</th>
                            @foreach ($turns as $turnnumber => $turn)
                            <th>{{ $turnnumber }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($nationturns as $nation => $turn)
                            <th scope="row">{{ $nation }}</td>
                                @foreach ($turn as $turnnumber => $status)
                                    @if ($status == '-')
                                    <td class="danger">Staled</td>
                                    @else
                                    <td>{{ $status }}</td>
                                    @endif
                                @endforeach
                                </tr><tr>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            @if ($game->id == 129)
             <div role="tabpanel" class="tab-pane" id="armysize"></div>
            <div role="tabpanel" class="tab-pane" id="forts"></div>
            <div role="tabpanel" class="tab-pane" id="income"></div>
            <div role="tabpanel" class="tab-pane" id="gemincome"></div>
            <div role="tabpanel" class="tab-pane" id="research"></div>
            <div role="tabpanel" class="tab-pane" id="victorypoints"></div>
            <div role="tabpanel" class="tab-pane" id="dominion"></div>
            <div role="tabpanel" class="tab-pane" id="provinces"></div>
            @endif
          </div>

        </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
            <script src="{{ asset('/js/raphael.min.js') }}"></script>
            <script src="{{ asset('/js/morris.min.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('/css/morris.css') }}">
            <script>
            $('ul.nav a').on('shown.bs.tab', function (e) {
                console.log('morris'+$(this).attr("data-identifier"));
                window['morris'+$(this).attr("data-identifier")].resizeHandler();
    });
</script>
            <script>
            @if ($game->id == 129)
            @foreach ($trackedstats as $stat)
            window.morris{{$stat}} = Morris.Line({
  element: '{{ $stat }}',
  data: {!! $chartdata[$stat] !!},
  xkey: 'turn',
  ykeys: {!! $chartdata['ykeys'] !!},
  labels: {!! $chartdata['labels'] !!},
  parseTime: false,
  resize: true,
  redraw: true
}); 
            @endforeach
            @endif
</script>
@endsection
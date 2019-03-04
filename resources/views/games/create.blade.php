@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Create New Game</h1>
    <hr/>

    {!! Form::open(['url' => '/games', 'class' => 'form-horizontal']) !!}

                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', trans('games.name'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('masterpw') ? 'has-error' : ''}}">
                {!! Form::label('masterpw', trans('games.masterpw'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('masterpw', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('masterpw', '<p class="help-block">:message</p>') !!}
                </div>
            </div>



            <div class="form-group {{ $errors->has('map') ? 'has-error' : ''}}">
                {!! Form::label('mapname', trans('games.map'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                 <div class="input-group">
                  {!! Form::text('mapname', null, ['class' => 'form-control']) !!}
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#mappicker">Pick!</button>
                  </span>
                </div><!-- /input-group -->
                    {!! $errors->first('mapname', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('era') ? 'has-error' : ''}}">
                {!! Form::label('era', trans('games.era'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('era', [ 1 => 'Early Age', 2 => 'Middle Age', 3 => 'Later Age'], null, ['class' => 'form-control']) !!}
                    {!! $errors->first('era', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('mods') ? 'has-error' : ''}}">
                {!! Form::label('mods', trans('games.mods'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    <small>Drag mods to change the load order</small>
                    <div id="modslist" class="list-group">
                    </div>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modspicker">
                      Mod picker
                    </button>
                    {!! $errors->first('mods', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('hours') ? 'has-error' : ''}}">
                {!! Form::label('hours', trans('games.hours'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('hours', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('hours', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('hofsize') ? 'has-error' : ''}}">
                {!! Form::label('hofsize', trans('games.hofsize'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('hofsize', 10, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => 10, 'data-slider-ticks' => "[5,10,15,20]", 'data-slider-ticks-snap-bounds' => "5", 'data-slider-ticks-labels' => '[5,10,15,20]']) !!}
                    {!! $errors->first('hofsize', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('indepstr') ? 'has-error' : ''}}">
                {!! Form::label('indepstr', trans('games.indepstr'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('indepstr', [0 => '0: None?', 1, 2, 3, 4, 5 => "5: Default", 6, 7, 8, 9 => "9: I love you"], 5, ['class' => 'form-control']) !!}
                    {!! $errors->first('indepstr', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('magicsites') ? 'has-error' : ''}}">
                {!! Form::label('magicsites', trans('games.magicsites'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('magicsites', 40, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '40', 'data-slider-min' => "0", 'data-slider-max' => "75" ]) !!}
                    {!! $errors->first('magicsites', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('eventrarity') ? 'has-error' : ''}}">
                {!! Form::label('eventrarity', trans('games.eventrarity'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('eventrarity', [1=>"Common", 2=>"Rare"], 1, ['class' => 'form-control']) !!}
                    {!! $errors->first('eventrarity', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('storyevents') ? 'has-error' : ''}}">
                {!! Form::label('storyevents', trans('games.storyevents'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('storyevents', [0=>"None", 1=>"Some", 2=>"All"], 1, ['class' => 'form-control']) !!}
                    {!! $errors->first('storyevents', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('research') ? 'has-error' : ''}}">
                {!! Form::label('research', trans('games.research'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('research', null, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '1', 'data-slider-min' => "0", 'data-slider-max' => "4" ]) !!}
                    {!! $errors->first('research', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            
            <div class="form-group {{ $errors->has('richness') ? 'has-error' : ''}}">
                {!! Form::label('richness', trans('games.richness'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('richness', null, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '100', 'data-slider-min' => "50", 'data-slider-max' => "300" ]) !!}
                    {!! $errors->first('richness', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('resources') ? 'has-error' : ''}}">
                {!! Form::label('resources', trans('games.resources'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('resources', null, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '100', 'data-slider-min' => "50", 'data-slider-max' => "300" ]) !!}
                    {!! $errors->first('resources', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('supplies') ? 'has-error' : ''}}">
                {!! Form::label('supplies', trans('games.supplies'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('supplies', null, ['class' => 'form-control', 'data-provide' => 'slider', 'data-slider-value' => '100', 'data-slider-min' => "50", 'data-slider-max' => "300" ]) !!}
                    {!! $errors->first('supplies', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('startprov') ? 'has-error' : ''}}">
                {!! Form::label('startprov', trans('games.startprov'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('startprov', null, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '1', 'data-slider-min' => "1", 'data-slider-max' => "9" ]) !!}
                    {!! $errors->first('startprov', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('scoregraphs') ? 'has-error' : ''}}">
                {!! Form::label('scoregraphs', trans('games.scoregraphs'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::checkbox('scoregraphs', 1) !!}
                    {!! $errors->first('scoregraphs', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('nonationinfo') ? 'has-error' : ''}}">
                {!! Form::label('nonationinfo', trans('games.nonationinfo'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::checkbox('nonationinfo', 1) !!}
                    {!! $errors->first('nonationinfo', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('noartrest') ? 'has-error' : ''}}">
                {!! Form::label('noartrest', trans('games.noartrest'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::checkbox('noartrest', 1) !!}
                    {!! $errors->first('noartrest', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('norandres') ? 'has-error' : ''}}">
                {!! Form::label('norandres', trans('games.norandres'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::checkbox('norandres', 1) !!}
                    {!! $errors->first('norandres', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('teamgame') ? 'has-error' : ''}}">
                {!! Form::label('teamgame', trans('games.teamgame'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('teamgame', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control']) !!}
                    {!! $errors->first('teamgame', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('clustered') ? 'has-error' : ''}}">
                {!! Form::label('clustered', trans('games.clustered'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::checkbox('clustered', 1) !!}
                    {!! $errors->first('clustered', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('victorycond') ? 'has-error' : ''}}">
                {!! Form::label('victorycond', trans('games.victorycond'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::select('victorycond', [0 => trans('games.victorycond_0'), 1 => trans('games.victorycond_1')], 1, ['class' => 'form-control']) !!}
                    {!! $errors->first('victorycond', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requiredap') ? 'has-error' : ''}}">
                {!! Form::label('requiredap', trans('games.requiredap'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('requiredap', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requiredap', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('lvl1thrones') ? 'has-error' : ''}}">
                {!! Form::label('lvl1thrones', trans('games.lvl1thrones'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('lvl1thrones', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('lvl1thrones', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('lvl2thrones') ? 'has-error' : ''}}">
                {!! Form::label('lvl2thrones', trans('games.lvl2thrones'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('lvl2thrones', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('lvl2thrones', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('lvl3thrones') ? 'has-error' : ''}}">
                {!! Form::label('lvl3thrones', trans('games.lvl3thrones'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('lvl3thrones', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('lvl3thrones', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('globals') ? 'has-error' : ''}}">
                {!! Form::label('globals', trans('games.globals'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('globals', null, ['class' => 'form-control', 'data-provide' => 'slider',  'data-slider-value' => '5', 'data-slider-min' => "3", 'data-slider-max' => "9" ]) !!}
                    {!! $errors->first('globals', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('cataclysm') ? 'has-error' : ''}}">
                {!! Form::label('cataclysm', trans('games.cataclysm'), ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('cataclysm', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('cataclysm', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

                        <!-- Mods -->
            <div class="modal fade" id="modspicker" tabindex="-1" role="dialog" aria-labelledby="modspickerlabel">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modspickerlabel">Pick mods</h4>
                  </div>
                  <div class="modal-body">
                    <select id="mod-image-picker" class="image-picker show-labels show-html" name="mods[]" multiple="multiple">
                        @foreach(Snek\Mod::all() as $mod)
                            @if(file_exists('/home/dom5snek/snek/public/img/mods/'.$mod->id.'.png'))
                                <option data-map-description="{{ $mod->description }}"  data-img-src="{{ asset('/img/mods/'.$mod->id.'.png') }}" value="{{ $mod->id }}">{{ $mod->name }}</option>
                            @else
                                <option data-map-description="{{ $mod->description }}" data-img-src="http://placekitten.com/140/140" value="{{ $mod->id }}">{{ $mod->name }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Choose</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Map -->
            <div class="modal fade" id="mappicker" tabindex="-1" role="dialog" aria-labelledby="mappickerlabel">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="mappickerlabel">Choose a map</h4>
                  </div>
                  <div class="modal-body overflow-auto">
                    <select id="map-image-picker" class="image-picker show-labels" name="map_id">
                        @foreach(Snek\Map::all() as $map)
                            @if(file_exists('/home/dom5snek/snek/public/img/maps/'.$map->id.'.png'))
                                <option data-map-description="{{ $map->description }}" data-img-label="{{ $map->name }}<br /><small>{{ $map->filename }}</small>" data-img-src="{{ asset('/img/maps/'.$map->id.'.png') }}" value="{{ $map->id }}">{{ $map->name }}</option>
                            @else
                                <option data-map-description="{{ $map->description }}" data-img-label="{{ $map->name }}<br /><small>{{ $map->filename }}</small>" data-img-src="http://placekitten.com/140/140" value="{{ $map->id }}">{{ $map->name }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Choose</button>
                  </div>
                </div>
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

@section('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/bootstrap-slider.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/css/bootstrap-slider.min.css">
            <script src="{{ asset('/js/image-picker.min.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('/css/image-picker.css') }}">
            <script src="{{ asset('/js/Sortable.min.js') }}"></script>
            <script>
                $(document).ready(function() {
                    Sortable.create(modslist, { 
                            onEnd: function (evt) {
                                $.each($('#modslist').children(), function(i, val)
                                {
                                    $(val).children('span').text(i+1);
                                    $(val).children('input').val(i+1);
                                });
                                 // element's old index within parent
                                  // element's new index within parent
                            }
                     });
                    $('#mod-image-picker').imagepicker({show_label: true,
                        clicked: function(select) {
                            if (select.option.prop('selected'))
                            {
                                $('#modslist').append('<div class="list-group-item" data-id='+select.option.val()+'><input type="hidden" name="load-order-'+ select.option.val() +'" value="0" /><span class="badge"></span><img src="//dom5.snek.earth/img/mods/'+ select.option.val() +'-xs.png" />&nbsp;'+ select.option.text() +'</div>');
                            }
                            else
                            {
                                $('#modslist').children('*[data-id="'+select.option.val()+'"]').remove();
                            }
                            
                            $.each($('#modslist').children(), function(i, val)
                                {
                                    $(val).children('span').text(i+1);
                                    $(val).children('input').val(i+1);
                                });
                        }});
                    $('#map-image-picker').imagepicker({
                        show_label: true,
                        selected: function() {
                            $('input[name=mapname]').val($("#map-image-picker option:selected").text());
                        }
                    });
                });
            </script>
            <style>
            .overflow-auto {
                max-height: calc(100vh - 210px);
                overflow-y: auto;
            }
    </style>
@endsection

@extends('layouts.master')

@section('title', 'Collection')
@section('class', 'collection')

@section('content')

    <!-- Example : http://codepen.io/desandro/pen/Ehgij -->
    <div class="btn-group btn-group-lg filter-playingtime" role="group">
        <button type="button" class="btn btn-default is-checked" data-filter="*">Tous</button>
        <button type="button" class="btn btn-default" data-filter=".shortgame">Jeu court</button>
        <button type="button" class="btn btn-default" data-filter=".longgame">Jeu long</button>
    </div>
    <div class="grid collection">
        @foreach($games as $idGame => $game)
            <div class="element-item {{ $game['class'] }}" data-toggle="tooltip" data-html="true" data-placement="top" title="{{ $game['tooltip'] }}">
                <a href="http://boardgamegeek.com/boardgame/{{ $idGame }}" target="_blank">
                    <div class="name">{{ $game['name'] }}</div>
                    <div class="image">{!! HTML::image($game['image']) !!}</div>
                </a>
            </div>
        @endforeach
    </div>

@endsection

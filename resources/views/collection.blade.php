@extends('layouts.master')

@section('title', 'Collection')
@section('class', 'collection')

@section('content')

    @include('partials.userInfo')

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="navbar-header">
                        <span class="navbar-brand">Filtrer</span>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="btn-group filter-playingtime" role="group">
                            <button type="button" class="navbar-btn btn btn-default active" data-filter="*">Tous</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter=".shortgame">Jeu court (30 min. et -)</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter=".longgame">Jeu long (60 min. et +)</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="navbar-header">
                        <span class="navbar-brand">Trier</span>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <div class="btn-group sort-by-button-group" role="group">
                            <button type="button" class="navbar-btn btn btn-default active" data-sort-by="original-order">Alphabétique</button>
                            <button type="button" class="navbar-btn btn btn-default" data-sort-by="rating">Classification</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="grid collection">
        @foreach($games as $idGame => $game)
            <div class="element-item {{ $game['class'] }}" data-toggle="tooltip" data-html="true" data-placement="top" title="{{ $game['tooltip'] }}">
                <a href="http://boardgamegeek.com/boardgame/{{ $idGame }}" target="_blank">
                    <div class="name">{{ $game['name'] }}</div>
                    <div class="image">{!! HTML::image($game['image']) !!}</div>
                    <span class="hidden rating">{{ 10 - $game['rating'] }}</span>
                </a>
            </div>
        @endforeach
    </div>

@endsection

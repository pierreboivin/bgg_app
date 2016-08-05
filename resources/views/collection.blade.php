@extends('layouts.master')

@section('title', 'Collection')
@section('class', 'collection')

@section('content')

    @include('partials.userInfo')

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="navbar-header">
                        <span class="navbar-brand">Types de jeux</span>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-filtrer">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="nav-filtrer">
                        <div class="btn-group option-set filter-playingtime" role="group" data-filter-group="type-game">
                            <button type="button" class="navbar-btn btn btn-default active" data-filter-value="">Tous</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".30minus">30 minutes et moins</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".31to60">Entre 31 et 60 minutes</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".61to120">Entre 61 et 120 minutes</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".121plus">121 minutes et plus</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="navbar-header">
                        <span class="navbar-brand">Mécaniques</span>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-mechanics">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="nav-mechanics">
                        <div class="btn-group option-set" role="group">
                            <select class="filter-mechanics form-control">
                                <option value="">Tous</option>
                                @foreach($mechanics as $slugMechanics => $mechanic)
                                    <option value="{{ $slugMechanics }}">{{ $mechanic }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="navbar-header">
                        <span class="navbar-brand">Nombre de joueurs</span>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-joueurs">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="nav-joueurs">
                        <div class="pull-left">
                            <div class="btn-group option-set filter-players" role="group" data-filter-group="players">
                                <button type="button" class="navbar-btn btn btn-default active" data-filter-value="">Tous</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_solo">Solo</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_2">2</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_3">3</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_4">4</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_5">5</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_6">6</button>
                                <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players_plus">7 et plus</button>
                                <input type="hidden" class="selector-players" value="">
                            </div>
                        </div>
                        <div class="pull-left">
                            <select class="form-control option-set" id="players-type-filter" name="players-type-filter">
                                <option value="">Tous</option>
                                <option value="best">Meilleurs selon les votes</option>
                                <option value="recommended">Recommandés selon les votes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="navbar-header">
                        <span class="navbar-brand">Trier</span>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-trier">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="nav-trier">
                        <div class="btn-group sort-by-button-group" role="group">
                            <button type="button" class="navbar-btn btn btn-default active" data-sort-by="original-order">Alphabétique</button>
                            <button type="button" class="navbar-btn btn btn-default" data-sort-by="rating" date-sort-direction="desc">Évaluation</button>
                            @if(\App\Helpers\Helper::ifLogin())
                                <button type="button" class="navbar-btn btn btn-default" data-sort-by="acquisitiondate" date-sort-direction="desc">Date d'acquisition</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="navbar-header">
                        <span class="navbar-brand">Afficher les extensions</span>
                    </div>
                    <div class="collapse navbar-collapse">
                        <input id="show_expansions" type="checkbox" name="show_expansions" value="1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="navbar-header">
                        <span class="navbar-brand">Nombre de jeux : <span class="nb-games">{{ count($games) }}</span></span>
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
                    <span class="hidden rating">{{ $game['rating'] }}</span>
                    @if(\App\Helpers\Helper::ifLogin())
                        <span class="hidden acquisitiondate">{{ $game['acquisitiondate'] }}</span>
                    @endif
                </a>
                @if($game['expansions'])
                    <div class="expansions">
                        <ul>
                        @foreach($game['expansions'] as $idExpansion => $expansion)
                            <li>{{ $expansion['name'] }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

@endsection

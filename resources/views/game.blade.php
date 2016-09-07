@extends('layouts.master')

@section('title', 'Jeu')
@section('class', 'game')

@section('content')

    @include('partials.userInfo')

    <div class="detail">
        <h2>{{ $game['name'] }}</h2>

        <div class="row">
            <div class="col-md-2">
                <div class="thumbnail">
                    {!! HTML::image($game['image']) !!}
                    <div class="caption text-center">
                        <a target="_blank" href="https://boardgamegeek.com/boardgame/{{ $game['id'] }}">Lien vers BGG</a>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-info">
                    <table class="table table-hover table-condensed">
                        <tr><td>Designer</td><td>{{ implode(', ', array_map(function ($entry) { return $entry['value']; }, $game['detail']['boardgamedesigner'])) }}</td></tr>
                        <tr><td>Durée partie</td><td>{{ $game['playingtime'] . ' minutes' }}</td></tr>
                        <tr><td>Nombre de joueurs</td><td>{{ $game['minplayer'] . ' à ' . $game['maxplayer']}}</td></tr>

                        @if(\App\Helpers\Helper::ifLogin())
                            <tr><td>Date d'acquisition</td><td>{{ $game['acquisitiondate'] }}</td></tr>
                        @endif

                        <tr><td>Évaluation BGG</td><td>{{ $game['rating_bgg'] }}</td></tr>
                        <tr><td>Évaluation de {{ $userinfo['username'] }}</td><td>{{ $game['rating'] }}</td></tr>
                        <tr><td>Nombre de parties jouées</td><td>{{ $game['numplays'] }}</td></tr>
                        <tr><td>Dernière partie joué</td><td>{{ date('Y-m-d', $lastPlayed['date']) }} ({{ $lastPlayed['since'] }})</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Extensions possédés</div>
                    <div class="panel-body">
                        @if($game['expansions'])
                        <ul>
                            @foreach($game['expansions'] as $expansion)
                                <li>{{ $expansion['name'] }}</li>
                            @endforeach
                        </ul>
                        @else
                            Aucune
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Mécaniques</div>
                    <div class="panel-body">
                        <ul>
                        @foreach($game['detail']['boardgamemechanic'] as $mechanic)
                            <li>{{ $mechanic['value'] }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Catégories du jeu</div>
                    <div class="panel-body">
                        <ul>
                            @foreach($game['detail']['boardgamecategory'] as $category)
                                <li>{{ $category['value'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Parties jouées</div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr><td>Date</td><td>Parties joués</td></tr>
                            </thead>
                            <tbody>
                            @foreach($plays as $play)
                                <tr>
                                    <td>{{ date('Y-m-d', $play['date']) }}</td>
                                    <td>{{ $play['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

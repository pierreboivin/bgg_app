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
                    {!! Html::image($game['thumbnail']) !!}
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

                        @if(\App\Helpers\Helper::ifLoginAsSelf() && isset($game['collection']['privateinfo']['@attributes']['acquisitiondate']))
                            <tr><td>Date d'acquisition</td><td>{{ $game['collection']['privateinfo']['@attributes']['acquisitiondate'] }}</td></tr>
                        @endif

                        <tr><td>Évaluation BGG</td><td>{{ isset($game['ratings']['average']) ? $game['ratings']['average'] : 'N/A' }}</td></tr>
                        <tr><td>Évaluation de {{ $userinfo['username'] }}</td><td>{{ isset($game['collection']['rating']) ? $game['collection']['rating'] : 'N/A' }}</td></tr>
                        <tr><td>Nombre de parties jouées</td><td>{{ $game['numplays'] }}</td></tr>
                        @if($game['lastPlayed'])
                            <tr><td>Dernière partie jouée</td><td>{{ date('Y-m-d', $game['lastPlayed']['date']) }} ({{ $game['lastPlayed']['since'] }})</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">Extensions possédées</div>
                    <div class="panel-body">
                        @if(isset($game['collection']['expansions']) && count($game['collection']['expansions']))
                        <ul>
                            @foreach($game['collection']['expansions'] as $expansion)
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
                        @if(isset($game['detail']['boardgamemechanic']))
                            <ul>
                                @foreach($game['detail']['boardgamemechanic'] as $mechanic)
                                    <li>{{ $mechanic['value'] }}</li>
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
                    <div class="panel-heading">Catégories du jeu</div>
                    <div class="panel-body">
                        @if(isset($game['detail']['boardgamecategory']))
                            <ul>
                                @foreach($game['detail']['boardgamecategory'] as $category)
                                    <li>{{ $category['value'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            Aucune
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if(count($game['plays']) > 0)
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
                                @foreach($game['plays'] as $play)
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
        @endif
    </div>
@endsection

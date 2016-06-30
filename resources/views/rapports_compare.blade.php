@extends('layouts.master')

@section('title', 'Comparaison à une autre personne')
@section('class', 'rapports_compareuser')

@section('content')

    @include('partials.userInfo')

    <h2>Comparaison à {{ $compareinfo['username_compared'] }}</h2>

    <h3>Jeux en communs</h3>
    <p class="well">
        Nombre de jeux à {{ $userinfo['username'] }} : {{ $compareinfo['nb_collection'] }} <br>
        Nombre de jeux à {{ $compareinfo['username_compared'] }} : {{ $compareinfo['nb_collection_compared'] }} <br>
        Nombre de jeux en communs : {{ count($gamesInCommon['games']) }} <br>
        Corrélation : {{ $gamesInCommon['correlation'] }} %
    </p>
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>Jeu</th>
        </tr>
        </thead>
        <tbody>
        @foreach($gamesInCommon['games'] as $game)
            <tr>
                <td><a href="http://boardgamegeek.com/boardgame/{{ $game['id'] }}" target="_blank">{{ $game['name'] }}</a></td>
            </tr>
        @endforeach
        </tbody>

    </table>

    <h3>Jeux non joués</h3>
    <p class="well">
        Nombre de jeux non joués : {{ $gamesNotPlayed['nbGames'] }} <br>
        Pourcentage de la collection : {{ $gamesNotPlayed['percentCollection'] }} %
    </p>
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>Jeu</th>
        </tr>
        </thead>
        <tbody>
        @foreach($gamesNotPlayed['games'] as $game)
            <tr>
                <td><a href="http://boardgamegeek.com/boardgame/{{ $game['id'] }}" target="_blank">{{ $game['name'] }}</a></td>
            </tr>
        @endforeach
        </tbody>

    </table>




@endsection

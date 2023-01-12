@extends('layouts.master')

@section('title', 'Jeux qui pourraient être achetés')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Jeux qui pourraient être achetés</h2>

    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>Jeu</th>
            <th>Raisons</th>
        </tr>
        </thead>
        <tbody>
        @foreach($games as $game)
            <tr>
                <td><a href="http://boardgamegeek.com/boardgame/{{ $game['id'] }}" target="_blank">{{ $game['name'] }}</a></td>
                <td>{{ implode(', ', $game['reason']) }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>

@endsection

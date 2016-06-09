@extends('layouts.master')

@section('title', 'Rapports jeux qui pourraient être vendus')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Rapport des jeux qui pourraient être vendus</h2>

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

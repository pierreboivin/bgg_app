@extends('layouts.master')

@section('title', 'Jeux qui pourraient être vendus')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Jeux qui pourraient être vendus</h2>

    @if(!Auth::check())
        <p class="well">Notez que ce rapport serait plus précis si vous étiez connecté.</p>
    @endif

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

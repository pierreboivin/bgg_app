@extends('layouts.master')

@section('title', 'Rapports annuel')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Rapport annuel</h2>

    {!! Form::open(array('url' => Request::fullUrl(), 'method' => 'get')) !!}
    <div class="form-group">
        <p>
        {!! Form::label('year', 'Année') !!}
        {!! Form::select('year', $listYear, $yearSelected, ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Afficher le rapport', ['class' => 'btn btn-mini btn-primary']) !!}</p>
    </div>
    {!! Form::close() !!}

    @if($playsThisYear)
        <div class="well">
            <p>Nombre de parties joués: {{$stats['playTotal']}}</p>
        </div>
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">Jeux les plus joués de l'année {{ $currentYear }}</div>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th>Jeu</th>
                    <th>Partie joué</th>
                </tr>
                </thead>
                <tbody>
                @foreach($playsThisYear as $idGame => $gameInfos)
                    <tr>
                        <td>
                            <a href="http://boardgamegeek.com/boardgame/{{ $idGame }}" target="_blank">{{ $gameInfos['otherInfo']['name'] }}</a>
                        </td>
                        <td>{{ $gameInfos['nbPlayed'] }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    @endif

@endsection

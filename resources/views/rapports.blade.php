@extends('layouts.master')

@section('title', 'Rapports')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Mensuel</h2>

    {!! Form::open(array('url' => Request::fullUrl())) !!}
    <div class="form-group">
        <p>
        {!! Form::label('month', 'Mois') !!}
        {!! Form::select('month', $listMonth, $monthSelected, ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Afficher le rapport', ['class' => 'btn btn-mini btn-primary']) !!}</p>
    </div>
    {!! Form::close() !!}

    @if($playsThisMonth)
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">Rapport du mois de {{ $currentMonth }}</div>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Jeu</th>
                        <th>Partie joué</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($playsThisMonth as $idGame => $gameInfos)
                    <tr class="{{ $gameInfos['addClass'] }}">
                        <td><a href="http://boardgamegeek.com/boardgame/{{ $idGame }}" target="_blank">{{ $gameInfos['otherInfo']['name'] }}</a></td>
                        <td>{{ $gameInfos['nbPlayedThisMonth'] }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    @endif

@endsection

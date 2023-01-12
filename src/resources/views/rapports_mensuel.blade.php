@extends('layouts.master')

@section('title', 'Rapports mensuel')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Rapport mensuel</h2>

    {!! Form::open(array('url' => Request::fullUrl(), 'method' => 'get')) !!}
    <div class="form-group">
        <p>
        {!! Form::label('month', 'Mois') !!}
        {!! Form::select('month', $listMonth, $monthSelected, ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Afficher le rapport', ['class' => 'btn btn-mini btn-primary']) !!}</p>
    </div>
    {!! Form::close() !!}

    @if($playsThisMonth)
        <div class="well">
            <p>Nombre de parties jouées: {{$stats['playTotal']}}</p>
            <p>Nouveaux jeux essayés : {{$stats['playNewGames']}}</p>
        </div>
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">Parties du mois de {{ $currentMonth }}</div>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Jeu</th>
                        <th>Parties jouées</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($playsThisMonth as $idGame => $gameInfos)
                    <tr>
                        <td>
                            <a href="{{ url('fiche', [$userinfo['username'], $idGame]) }}">{{ $gameInfos['otherInfo']['name'] }}</a>
                            @if($gameInfos['newGame'])
                                <span class="label label-success">Nouveau jeu</span>
                            @endif
                        </td>
                        <td>{{ $gameInfos['nbPlayed'] }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    @endif

    @if($acquisitionsThisMonth)
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">Acquisitions du mois de {{ $currentMonth }}</div>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Jeu</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($acquisitionsThisMonth as $idGame => $gameName)
                    <tr>
                        <td><a href="{{ url('fiche', [$userinfo['username'], $idGame]) }}">{{ $gameName }}</a></td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    @endif

@endsection

@extends('layouts.master')

@section('title', 'Statistiques')
@section('class', 'stats')

@section('content')

    <script>
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.animationEasing = 'easeOutBack';
        Chart.defaults.global.tooltipFillColor = '#2C4870';
        Chart.defaults.global.tooltipFontColor = '#fff';
        Chart.defaults.global.tooltipFontSize = 16;
        Chart.defaults.global.scaleFontSize = 14;
        Chart.defaults.global.tooltipTemplate = "<%if (label){%><%=label%> : <%}%><%= value %>";
    </script>

    <input type="hidden" id="username" value="{{ $userinfo['username'] }}">

    <h1>{{ $userinfo['firstname'] }} {{ $userinfo['lastname'] }} ({{ $userinfo['username'] }})</h1>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading1">
                <h2>
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        Statistiques générales
                    </a>
                </h2>
            </div>
            <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h3>Partie joués</h3>
                            <p>Nb de partie joué total : {{ $stats['nbPlaysTotal'] }}</p>
                            <p>Nb de jeu différent joué : {{ $stats['nbPlaysDifferentGame'] }}</p>
                            <p>Moyenne de parties par mois : {{ $stats['averagePlayByMonth'] }}</p>
                            <p>H-index : {{ $stats['hindex'] }}</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Jeux possédés</h3>
                            <p>Nombre d'acquisition moyennes par mois : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['averageAcquisitionByMonth']) !!}</p>
                            <p>Nombre de jeu possédé (sans expansion) : {{ $stats['nbGamesOwned'] }}</p>
                            <p>Nb de jeu possédé (avec expansion) : {{ $stats['nbGamesAndExpansionsOwned'] }}</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Valeur collection</h3>
                            <p>Valeur moyenne des jeux : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['averageValueGames']) !!}</p>
                            <p>Valeur total de la collection : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['totalValueGames']) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading2">
                <h2>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        Amis
                    </a>
                </h2>
            </div>
            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                <div class="panel-body">
                    <ul>
                        @foreach ($userinfo['lists']['buddies'] as $id => $name)
                            <li>{!! HTML::linkRoute('stats', $name, array($name)) !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading3">
                <h2>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        Jeux préférés
                    </a>
                </h2>
            </div>
            <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                <div class="panel-body">
                    <ul>
                        @foreach ($userinfo['lists']['topGames'] as $id => $name)
                            <li><a href="http://boardgamegeek.com/boardgame/{{ $id }}">{{ $name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading4">
                <h2>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="yes" aria-controls="collapse4">
                        Statisques sur les parties joués
                    </a>
                </h2>
            </div>
            <div id="collapse4" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading4">
                <div class="panel-body">
                    <div class="chart-container">
                        @include('partials.graphs-playByMonth')
                    </div>
                    <hr>
                    <div class="chart-container">
                        @include('partials.graphs-mostPlayed')
                    </div>
                    <hr>
                    <div class="table-container">
                        <div class="row">
                            <div class="col-md-6">
                                @include('partials.table-owned-lesstime')
                            </div>
                            <div class="col-md-6">
                                @include('partials.table-owned-mosttime')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading5">
                <h2>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="yes" aria-controls="collapse5">
                        Statisques sur la collection
                    </a>
                </h2>
            </div>
            <div id="collapse5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading5">
                <div class="panel-body">
                    <div class="chart-container">
                        @include('partials.graphs-nbplayer')
                    </div>
                    <hr>
                    @if($GLOBALS['parameters']['general']['password'])
                    <div class="chart-container">
                        @include('partials.graphs-acquisitionByMonth')
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

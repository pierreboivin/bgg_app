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

    @include('partials.userInfo')

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading1">
                <h2>
                    <a class="collapsed" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        Statistiques totales
                    </a>
                </h2>
            </div>
            <div id="collapse1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading1">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h3>Parties jouées</h3>
                            <p>Nombre de parties jouées totales : {{ $stats['nbPlaysTotal'] }}</p>
                            <p>Nombre de jeux différents joués : {{ $stats['nbPlaysDifferentGame'] }}</p>
                            <p>Moyenne de parties par mois : {{ $stats['averagePlayByMonth'] }}</p>
                            <p>Moyenne de nouveaux jeux par mois : {{ $stats['averagePlayDifferentByMonth'] }}</p>
                            <p>Nombre de parties par jour : {{ $stats['nbPlayAverageByDay'] }}</p>
                            <p>Nombre de jeux différents par jour : {{ $stats['nbPlayDifferentAverageByDay'] }}</p>
                            <p>H-index : {{ $stats['hindex'] }}</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Jeux possédés</h3>
                            <p>Nombre de jeux possédés (sans extension) : {{ $stats['nbGamesOwned'] }}</p>
                            <p>Nombre de jeux possédés (avec extension) : {{ $stats['nbGamesAndExpansionsOwned'] }}</p>
                            <p>Nombre de parties jouées en moyenne dans les jeux possédés : {!! $stats['nbPlayAveragePlayCollectionGame'] !!}</p>
                            <p>Nombre d'acquisitions moyennes par mois : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['averageAcquisitionByMonth']) !!}</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Valeur collection</h3>
                            <p>Valeur moyenne des jeux : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['averageValueGames']) !!}</p>
                            <p>Valeur totale de la collection : {!! \App\Helpers\Helper::ifEmptyToolTip($stats['totalValueGames']) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading4">
                <h2>
                    <a role="button" data-toggle="collapse" href="#collapse4" aria-expanded="yes" aria-controls="collapse4">
                        Statistiques sur les parties jouées
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
                        @include('partials.graphs-playByYear')
                    </div>
                    <hr>
                    <div class="chart-container">
                        @include('partials.graphs-mostPlayed')
                    </div>
                    <hr>
                    <div class="chart-container">
                        @include('partials.graphs-playByDayWeek')
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
                    <a role="button" data-toggle="collapse" href="#collapse5" aria-expanded="yes" aria-controls="collapse5">
                        Statistiques sur la collection
                    </a>
                </h2>
            </div>
            <div id="collapse5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading5">
                <div class="panel-body">
                    <div class="chart-container">
                        @include('partials.graphs-nbplayer')
                    </div>
                    <hr>
                    <div class="table-container">
                        @include('partials.table-owned-mostdesigner')
                    </div>
                    <hr>
                    <div class="chart-container">
                        @include('partials.graphs-mostType')
                    </div>
                    <hr>
                    @if(\App\Helpers\Helper::ifLogin())
                    <div class="chart-container">
                        @include('partials.graphs-acquisitionByMonth')
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

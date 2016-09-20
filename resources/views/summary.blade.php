@extends('layouts.master')

@section('title', 'Résumé')
@section('class', 'summary')

@section('content')

    @include('partials.userInfo')

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Derniers jeux joués</h3>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-condensed last-play">
                <thead>
                    <tr><th>Jeu</th><th>Date de la partie</th></tr>
                </thead>
                <tbody>
                @include('partials.lines-table-last-games-played')
                </tbody>
            </table>
            <button data-page="2" data-replace="table.last-play tbody" data-href="{{ url('ajaxTableLastPlay/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary btn-block table-more-button">Plus</button>
        </div>
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Derniers jeux achetés</h3>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-condensed last-acquisition">
                <thead>
                    <tr><th>Jeu</th><th>Date de l'achat</th></tr>
                </thead>
                <tbody>
                @include('partials.lines-table-last-games-acquisition')
                </tbody>
            </table>
            <button data-page="2" data-replace="table.last-acquisition tbody" data-href="{{ url('ajaxTableLastAcquisition/' . $GLOBALS['parameters']['general']['username']) }}" class="btn btn-primary btn-block table-more-button">Plus</button>
        </div>
    </div>


@endsection

@extends('layouts.master')

@section('title', 'Accueil')

@section('content')

    @include('partials.userInfo')

    <div class="list-group">
        <a href="/stats/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Statistiques</h3>
            <p class="list-group-item-text">Consulter des graphiques sur vos parties joués, vos jeux préférés et plus encore.</p>
        </a>
        <a href="/collection/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Collection</h3>
            <p class="list-group-item-text">Consulter votre collection de jeux.</p>
        </a>
    </div>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

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
    </div>

@endsection

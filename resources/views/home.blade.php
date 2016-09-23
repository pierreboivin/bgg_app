@extends('layouts.master')

@section('title', 'Présentation')
@section('class', 'home')

@section('content')

    @include('partials.userInfo')

    <div id="progression-message-warning" class="alert alert-warning" role="alert">Attendez que vos données soient chargées. Cette étape peut nécessiter quelques minutes.</div>
    <div id="progression-message-success" class="alert alert-success" role="alert">Vos données ont été chargées. Vous pouvez les consulter par les options ci-dessous ou par le menu.</div>
    <div id="progression" class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width: 0%"></div>
    </div>

    <div class="list-group">
        <a href="/stats/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item desactivate-if-not-loaded">
            <h3 class="list-group-item-heading">Statistiques</h3>
            <p class="list-group-item-text">Consultez des graphiques sur vos parties jouées, vos jeux préférés et plus encore.</p>
        </a>
        <a href="/collection/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item desactivate-if-not-loaded">
            <h3 class="list-group-item-heading">Collection</h3>
            <p class="list-group-item-text">Consultez votre collection de jeux.</p>
        </a>
        <a href="/rapports/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item desactivate-if-not-loaded">
            <h3 class="list-group-item-heading">Rapports</h3>
            <p class="list-group-item-text">Rapports sur vos parties jouées.</p>
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h2>Raccourcis vers BoardGameGeek</h2>
            <div class="list-group">
                <a class="list-group-item" target="_blank" href="http://boardgamegeek.com/user/{{$GLOBALS['parameters']['general']['username']}}">Votre page sur BGG</a>
                <a class="list-group-item" target="_blank" href="http://boardgamegeek.com/plays/bydate/user/{{$GLOBALS['parameters']['general']['username']}}/subtype/boardgame">Derniers jeux joués</a>
                <a class="list-group-item" target="_blank" href="http://boardgamegeek.com/collection/user/{{$GLOBALS['parameters']['general']['username']}}?sort=rating&sortdir=desc&rankobjecttype=subtype&rankobjectid=1&columns=title%7Cstatus%7Cversion%7Crating%7Cbggrating%7Cplays%7Ccomment%7Ccommands&geekranks=Board+Game+Rank&excludesubtype=boardgameexpansion&rated=1&ff=1&subtype=boardgame">Jeux les mieux évalués</a>
                <a class="list-group-item" target="_blank" href="http://boardgamegeek.com/collection/user/{{$GLOBALS['parameters']['general']['username']}}?sort=rating&sortdir=desc&rankobjecttype=subtype&rankobjectid=1&columns=title%7Cstatus%7Cversion%7Crating%7Cbggrating%7Cplays%7Ccomment%7Ccommands&geekranks=%0A%09%09%09%09%09%09%09%09%09Board+Game+Rank%0A%09%09%09%09%09%09%09%09&excludesubtype=boardgameexpansion&rated=0&played=1&ff=1&subtype=boardgame">Jeux joués non évalués</a>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Informations sur l'utilisateur</h2>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading2">
                        <h2>
                            <a role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                Amis
                            </a>
                        </h2>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                        <div class="panel-body">
                            <ul>
                                @foreach ($userinfo['lists']['buddies'] as $id => $name)
                                    <li>{!! HTML::linkRoute('home', $name, array($name)) !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading3">
                        <h2>
                            <a role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
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
        </div>
    </div>

@endsection

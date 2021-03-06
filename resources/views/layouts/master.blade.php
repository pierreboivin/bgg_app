<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - BGG App - Système de statistique BGG</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="@yield('meta.description')">
    <meta name="dcterms.description" content="@yield('meta.description')">
    <meta property="og:title" content="@yield('title')"/>
    <meta property="og:description" content="@yield('meta.description')">
    <meta property="og:site_name" content="BGG App"/>
    <meta property="og:type" content="website"/>
    <meta name="author" content="Pierre Boivin">
    <meta name="dcterms.creator" content="Pierre Boivin" />

    <script src="{!! \App\Helpers\Helper::asset_timed('/assets/js/app.min.js') !!}"></script>

    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{!! \App\Helpers\Helper::asset_timed('/assets/css/app.min.css') !!}" />
</head>
<body class="@yield('class')">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-bar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand">BGG App</span>
            </div>
            <div class="collapse navbar-collapse" id="navigation-bar">
                <ul class="nav navbar-nav">
                        <li><a href="/">Accueil</a></li>
                        @if(\App\Helpers\Helper::ifBGGInfo())
                            <li class="{{ \App\Helpers\Helper::set_active('home') }}"><a href="/home/{{ $GLOBALS['parameters']['general']['username'] }}">Présentation</a></li>
                            <li class="{{ \App\Helpers\Helper::set_active('stats') }}"><a class="desactivate-if-not-loaded" href="/stats/{{ $GLOBALS['parameters']['general']['username'] }}">Statistiques</a></li>
                            <li class="{{ \App\Helpers\Helper::set_active('collection') }}"><a class="desactivate-if-not-loaded" href="/collection/{{ $GLOBALS['parameters']['general']['username'] }}">Collection</a></li>
                            <li class="{{ \App\Helpers\Helper::set_active('rapports') }} dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rapports <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="desactivate-if-not-loaded" href="/rapports/mensuel/{{ $GLOBALS['parameters']['general']['username'] }}">Mensuel</a></li>
                                    <li><a class="desactivate-if-not-loaded" href="/rapports/annuel/{{ $GLOBALS['parameters']['general']['username'] }}">Annuel</a></li>
                                    <li><a class="desactivate-if-not-loaded" href="/rapports/vendre/{{ $GLOBALS['parameters']['general']['username'] }}">Jeux qui pourraient être vendus</a></li>
                                    <li><a class="desactivate-if-not-loaded" href="/rapports/tobuy/{{ $GLOBALS['parameters']['general']['username'] }}">Jeux qui pourraient être achetés</a></li>
                                    <li><a class="desactivate-if-not-loaded" href="/rapports/home_compare_user/{{ $GLOBALS['parameters']['general']['username'] }}">Comparaison à une autre personne</a></li>
                                    <li><a class="desactivate-if-not-loaded" href="/resume/{{ $GLOBALS['parameters']['general']['username'] }}">Résumé</a></li>
                                </ul>
                            </li>
                        @endif
                        <li class="{{ \App\Helpers\Helper::set_active('lists') }}"><a href="/lists/">Listes</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li id="background-loading"><img src="/assets/img/ajax-loader.gif" title="Chargement des nouvelles statistiques en cours" /></li>
                    @if(\App\Helpers\Helper::ifAuthenticated())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Connecté en tant que {{ $GLOBALS['parameters']['login']['username'] }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @if(\App\Helpers\Helper::ifAdmin())
                                <li><a href="/admin/">Administration</a></li>
                                @endif
                                <li><a href="/modules/">Modules</a></li>
                                <li><a href="/home/">Retour à votre page d'accueil</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/logout">Se déconnecter</a></li>
                            </ul>
                        </li>
                    @else
                        <li><a href="/login">Se connecter</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if (isset($userinfo['username']))
        @if(\App\Helpers\Helper::ifBGGInfo())
            <input type="hidden" id="username" value="{{ $userinfo['username'] }}">
        @else
            <input type="hidden" id="username" value="">
        @endif
    @endif
    <input type="hidden" id="cacheLevel" value="{{ isset($GLOBALS['parameters']['cache']['level']) ? $GLOBALS['parameters']['cache']['level'] : '' }}">

    <div class="container-fluid">
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        @yield('content')
    </div>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-77752482-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>

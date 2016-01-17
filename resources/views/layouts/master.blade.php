<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BGG App - @yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/assets/js/app.min.js"></script>

    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/assets/css/app.min.css" />
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
                    @if(isset($GLOBALS['parameters']['general']['username']))
                        <li class="{{ \App\Helpers\Helper::set_active('home') }}"><a href="/home/{{ $GLOBALS['parameters']['general']['username'] }}">Accueil</a></li>
                        <li class="{{ \App\Helpers\Helper::set_active('stats') }}"><a class="desactivate-if-not-loaded" href="/stats/{{ $GLOBALS['parameters']['general']['username'] }}">Statistiques</a></li>
                        <li class="{{ \App\Helpers\Helper::set_active('collection') }}"><a class="desactivate-if-not-loaded" href="/collection/{{ $GLOBALS['parameters']['general']['username'] }}">Collection</a></li>
                        <li class="{{ \App\Helpers\Helper::set_active('rapports') }} dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rapport <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a class="desactivate-if-not-loaded" href="/rapports/mensuel/{{ $GLOBALS['parameters']['general']['username'] }}">Mensuel</a></li>
                                <li><a class="desactivate-if-not-loaded" href="/rapports/annuel/{{ $GLOBALS['parameters']['general']['username'] }}">Annuel</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
                @if(Auth::check())
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Connecté en tant que {{ $GLOBALS['parameters']['login']['username'] }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/home/{{ $GLOBALS['parameters']['login']['username'] }}">Retour à votre page d'accueil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/logout">Se déconnecter</a></li>
                        </ul>
                    </li>
                </ul>
                @else
               <ul class="nav navbar-nav navbar-right">
                    <li><a href="/login">Se connecter</a></li>
               </ul>
                @endif
            </div>
        </div>
    </nav>

    @if (isset($userinfo))
        <input type="hidden" id="username" value="{{ $userinfo['username'] }}">
    @endif

    <div class="container-fluid">
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>

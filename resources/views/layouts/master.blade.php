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
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <span class="navbar-brand">BGG App</span>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    @if(isset($GLOBALS['parameters']['general']['username']))
                        <li class="{{ \App\Helpers\Helper::set_active('home') }}"><a href="/home/{{ $GLOBALS['parameters']['general']['username'] }}">Accueil</a></li>
                        <li class="{{ \App\Helpers\Helper::set_active('stats') }}"><a href="/stats/{{ $GLOBALS['parameters']['general']['username'] }}">Statistiques</a></li>
                        <li class="{{ \App\Helpers\Helper::set_active('collection') }}"><a href="/collection/{{ $GLOBALS['parameters']['general']['username'] }}">Collection</a></li>
                    @endif
                    <li><a href="http://boardgamegeek.com/" target="_blank">Site BGG</a></li>
                    @if(Auth::check())
                        <li><a href="/logout">Se déconnecter</a></li>
                    @else
                        <li><a href="/login">Se connecter</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>
</body>
</html>

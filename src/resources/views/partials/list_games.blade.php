<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="navbar-header">
                    <span class="navbar-brand">Durée des jeux</span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-filtrer">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="nav-filtrer">
                    <div class="btn-group option-set filter-playingtime" role="group" data-filter-group="time">
                        <button type="button" class="navbar-btn btn btn-default active" data-filter-value="">Tous</button>
                        <button type="button" class="navbar-btn btn btn-default" data-filter-value=".30minus">30 minutes et moins</button>
                        <button type="button" class="navbar-btn btn btn-default" data-filter-value=".31to60">Entre 31 et 60 minutes</button>
                        <button type="button" class="navbar-btn btn btn-default" data-filter-value=".61to120">Entre 61 et 120 minutes</button>
                        <button type="button" class="navbar-btn btn btn-default" data-filter-value=".121plus">121 minutes et plus</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="navbar-header">
                    <span class="navbar-brand">Mécaniques</span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-mechanics">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="nav-mechanics">
                    <div class="btn-group option-set" role="group">
                        <select class="filter-mechanics form-control">
                            <option value="">Tous</option>
                            @foreach($mechanics as $slugMechanics => $mechanic)
                                <option value="{{ $slugMechanics }}">{{ $mechanic }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="navbar-header">
                    <span class="navbar-brand">Nombre de joueurs</span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-joueurs">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="nav-joueurs">
                    <div class="pull-left">
                        <div class="btn-group option-set filter-players" role="group" data-filter-group="players">
                            <button type="button" class="navbar-btn btn btn-default active" data-filter-value="">Tous</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players1">Solo</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players2">2</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players3">3</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players4">4</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players5">5</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".players6">6</button>
                            <button type="button" class="navbar-btn btn btn-default" data-filter-value=".playersplus">7 et plus</button>
                            <input type="hidden" class="selector-players" value="">
                        </div>
                    </div>
                    <div class="pull-left">
                        <select class="form-control option-set filter-players-type" id="filter-players-type" name="filter-players-type">
                            <option value="">Tous</option>
                            <option value="best">Meilleurs selon les votes</option>
                            <option value="recommended">Recommandés selon les votes</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="navbar-header">
                    <span class="navbar-brand">Trier</span>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-trier">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="nav-trier">
                    <div class="btn-group sort-by-button-group" role="group">
                        <button type="button" class="navbar-btn btn btn-default active" data-sort-by="original-order">Alphabétique</button>
                        @if(isset($userinfo))
                            <button type="button" class="navbar-btn btn btn-default" data-sort-by="rating" date-sort-direction="desc">Évaluation personnel</button>
                        @endif
                        <button type="button" class="navbar-btn btn btn-default" data-sort-by="weight" date-sort-direction="desc">Complexité</button>
                        @if(\App\Helpers\Helper::ifLoginAsSelf() && isset($userinfo))
                            <button type="button" class="navbar-btn btn btn-default" data-sort-by="acquisitiondate" date-sort-direction="desc">Date d'acquisition</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="navbar-header">
                    <span class="navbar-brand">Afficher les extensions</span>
                </div>
                <div class="collapse navbar-collapse">
                    <input id="show_expansions" type="checkbox" name="show_expansions" value="1">
                </div>
            </div>
            <div class="col-md-2">
                <div class="navbar-header">
                    <span class="navbar-brand">Nombre de jeux : <span class="nb-games">{{ count($games) }}</span></span>
                </div>
            </div>
        </div>
    </div>
</nav>

<div id="collections" class="grid collection">
    @foreach($games as $idGame => $game)
        <div class="element-item {{ $game['class'] }}" data-toggle="tooltip" data-html="true" data-placement="top" title="{{ $game['tooltip'] }}">
            <div class="col-left">
                @if(isset($userinfo))
                    <a href="{{ url('fiche', [$userinfo['username'], $idGame]) }}">
                        <div class="name">{{ $game['name'] }}</div>
                        @if($game['image'])
                            <div class="image">{!! Html::image($game['image']) !!}</div>
                        @endif
                        <span class="hidden rating">{{ $game['ratingOn100'] }}</span>
                        <span class="hidden weight">{{ $game['weightOn100'] }}</span>
                        @if(\App\Helpers\Helper::ifLoginAsSelf())
                            <span class="hidden acquisitiondate">{{ $game['acquisitiondate'] }}</span>
                        @endif
                    </a>
                @else
                    <a href="http://boardgamegeek.com/boardgame/{{ $idGame }}" target="_blank">
                        <div class="name">{{ $game['name'] }}</div>
                        @if($game['image'])
                            <div class="image">{!! Html::image($game['image']) !!}</div>
                        @endif
                        <span class="hidden rating">{{ $game['ratingOn100'] }}</span>
                        <span class="hidden weight">{{ $game['weightOn100'] }}</span>
                        @if(\App\Helpers\Helper::ifLoginAsSelf())
                            <span class="hidden acquisitiondate">{{ $game['acquisitiondate'] }}</span>
                        @endif
                    </a>
                @endif
            </div>
            @if(isset($game['expansions']) && $game['expansions'])
                <div class="col-right">
                    <div class="expansions">
                        <ul>
                            @foreach($game['expansions'] as $idExpansion => $expansion)
                                <li>{{ \Illuminate\Support\Str::limit($expansion['name'], 50) }}</li>
                            @endforeach
                        </ul>
                        <a class="show-more" href="{{ url('fiche', [$userinfo['username'], $idGame]) }}">Voir plus</a>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>
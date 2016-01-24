<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Auteur le plus possédés dans les jeux</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats">
            <thead>
                <tr><th>Auteur</th><th>Jeux possédés</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @foreach ($table['mostDesigner'] as $id => $designer)
                <tr>
                    <td><a target="_blank" href="http://boardgamegeek.com/boardgamedesigner/{{ $id }} ">{{ $designer['name'] }}</a></td>
                    <td>{{ $designer['nbOwned'] }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">Voir les jeux</button>
                            <ul class="dropdown-menu" role="menu">
                                @foreach ($designer['games'] as $gameId => $gameName)
                                    <li><a target="_blank" href="http://boardgamegeek.com/boardgame/{{ $gameId }}">{{ $gameName }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux possédés les plus récemment joués</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats less-time">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées totales</th><th>Date de la dernière partie</th></tr>
            </thead>
            <tbody>
            @foreach ($table['ownedTimePlayed']['lessTime'] as $id => $game)
                <tr>
                    <td><a href="{{ $game['url'] }}" target="_blank">{{ $game['name'] }}</a></td>
                    <td>{{ $game['totalPlays'] }}</td>
                    <td><span class="hidden-xs">{{ $game['dateFormated'] }} (</span>{{ $game['since'] }}<span class="hidden-xs">)</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

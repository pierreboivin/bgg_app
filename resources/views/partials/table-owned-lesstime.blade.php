<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux possédés les plus récemment joués</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats less-time">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées total</th><th>Date de la dernière partie</th></tr>
            </thead>
            <tbody>
            @foreach ($graphs['ownedTimePlayed']['lessTime'] as $id => $game)
                <tr>
                    <td><a href="{{ $game['url'] }}" target="_blank">{{ $game['name'] }}</a></td>
                    <td>{{ $game['totalPlays'] }}</td>
                    <td>{{ $game['dateFormated'] }} ({{ $game['since'] }})</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

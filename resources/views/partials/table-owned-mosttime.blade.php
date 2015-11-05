<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">Jeux possédés les plus anciennement joués</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover table-condensed table-stats most-time">
            <thead>
                <tr><th>Jeu</th><th>Parties jouées total</th><th>Date de la dernière partie</th></tr>
            </thead>
            <tbody>
            @foreach ($graphs['ownedTimePlayed']['mostTime'] as $id => $game)
                <tr>
                    <td><a href="{{ $game['url'] }}" target="_blank">{{ $game['name'] }}</a></td>
                    <td>{{ $game['totalPlays'] }}</td>
                    <td>@if($game['totalPlays']) {{ $game['dateFormated'] }} ({{ $game['since'] }}) @endif</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
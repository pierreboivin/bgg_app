@foreach ($table['ownedTimePlayed']['lessTime'] as $id => $game)
    <tr>
        <td><a href="{{ $game['url'] }}" target="_blank">{{ $game['name'] }}</a></td>
        <td>{{ $game['totalPlays'] }}</td>
        <td><span class="hidden-xs">{{ $game['dateFormated'] }} (</span>{{ $game['since'] }}<span class="hidden-xs">)</span></td>
    </tr>
@endforeach
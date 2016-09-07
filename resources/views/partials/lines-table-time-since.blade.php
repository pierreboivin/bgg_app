@foreach ($table['ownedTimePlayed'][$type] as $id => $game)
    <tr>
        <td><a href="{{ url('fiche', [$userinfo['username'], $game['id']]) }}">{{ $game['name'] }}</a></td>
        <td>{{ $game['totalPlays'] }}</td>
        <td>@if($game['totalPlays']) <span class="hidden-xs">{{ $game['dateFormated'] }} (</span>{{ $game['since'] }}<span class="hidden-xs">)</span> @endif</td>
    </tr>
@endforeach
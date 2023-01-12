@foreach ($lastAcquisition as $game)
    <tr>
        <td><a href="{{ url('fiche', [$userinfo['username'], $game['id']]) }}">{{ $game['name'] }}</a></td>
        <td><span class="hidden-xs">{{ $game['dateFormated'] }} (</span>{{ $game['since'] }}<span class="hidden-xs">)</span></td>
    </tr>
@endforeach
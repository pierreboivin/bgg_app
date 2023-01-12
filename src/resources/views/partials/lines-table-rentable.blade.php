@foreach ($table['ownedRentable'][$type] as $id => $game)
    <tr>
        <td><a href="{{ url('fiche', [$userinfo['username'], $game['id']]) }}">{{ $game['name'] }}</a></td>
        <td>{{ $game['numplays'] }}</td>
        <td>{{ number_format($game['value'], 2) }} $</td>
        <td>{{ number_format($game['rentabilite'], 2) }} $</td>
    </tr>
@endforeach
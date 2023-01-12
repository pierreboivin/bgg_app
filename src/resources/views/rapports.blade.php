@extends('layouts.master')

@section('title', 'Rapports')
@section('class', 'rapports')

@section('content')

    <div class="list-group">
        <a href="/rapports/mensuel/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Rapports mensuel</h3>
        </a>
        <a href="/rapports/annuel/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Rapports annuel</h3>
        </a>
        <a href="/rapports/vendre/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Jeux qui pourraient être vendus</h3>
        </a>
        <a href="/rapports/tobuy/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Jeux qui pourraient être achetés</h3>
        </a>
        <a href="/rapports/home_compare_user/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Comparaison à une autre personne</h3>
        </a>
    </div>

@endsection

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
        @if(Auth::check())
        <a href="/rapports/vendre/{{ $GLOBALS['parameters']['general']['username'] }}" class="list-group-item">
            <h3 class="list-group-item-heading">Jeux qui pourraient être vendus</h3>
        </a>
        @endif
    </div>

@endsection

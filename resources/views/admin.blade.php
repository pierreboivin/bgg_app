@extends('layouts.master')

@section('title', 'Administration')
@section('class', 'admin')

@section('content')

    <div class="list-group">
        <a href="/tools/flushCaches" class="list-group-item">
            <h3 class="list-group-item-heading">Flush Caches</h3>
            <p class="list-group-item-text">Efface les caches temporaires (data de BGG).</p>
        </a>
        <a href="/tools/flushPersistentCaches" class="list-group-item desactivate-if-not-loaded">
            <h3 class="list-group-item-heading">Flush Persistent Caches</h3>
            <p class="list-group-item-text">Efface les caches de la base de données (data de BGG).</p>
        </a>
        <a href="/logs/" class="list-group-item">
            <h3 class="list-group-item-heading">Logs</h3>
            <p class="list-group-item-text">Historiques des accès et erreurs.</p>
        </a>
    </div>

@endsection

@extends('layouts.master')

@section('title', 'Comparaison à une autre personne')
@section('class', 'rapports_compareuser')

@section('content')

    @include('partials.userInfo')

    <h2>Comparaison à une autre personne</h2>

    <div id="messages">
    </div>

    {!! Form::open(array('id' => 'compareUser', 'url' => 'compare/loadCompare/' . $userinfo['username'], 'method' => 'get')) !!}
    <div class="form-group">
        <p>
            {!! Form::label('compare_user', 'Nom d\'utilisateur BGG') !!}
            {!! Form::text('compare_user', Input::old('compare_user'), ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Valider', ['id' => 'user_submit', 'class' => 'btn btn-mini btn-primary', 'data-loading-text' => 'Chargement en cours...']) !!}</p>
    </div>
    {!! Form::close() !!}

    {!! Form::open(array('id' => 'compareBuddy', 'url' => 'compare/loadCompare/' . $userinfo['username'], 'method' => 'get')) !!}
    <div class="form-group">
        <p>
            {!! Form::label('compare_buddy', 'Un de vos amis') !!}
            {!! Form::select('compare_buddy', array_merge([''], $userinfo['lists']['buddies']), null, ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Valider', ['id' => 'buddy_submit', 'class' => 'btn btn-mini btn-primary', 'data-loading-text' => 'Chargement en cours...']) !!}</p>
    </div>

    {!! Form::close() !!}

@endsection

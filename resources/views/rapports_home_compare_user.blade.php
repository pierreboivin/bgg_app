@extends('layouts.master')

@section('title', 'Comparaison à une autre personne')
@section('class', 'rapports_compareuser')

@section('content')

    @include('partials.userInfo')

    <h2>Comparaison à une autre personne</h2>

    {!! Form::open(array('id' => 'compareUser', 'url' => 'compare/loadCompare/' . $userinfo['username'], 'method' => 'get')) !!}
    <div class="form-group">
        <p>
            {!! Form::label('compare', 'Nom d\'utilisateur BGG') !!}
            {!! Form::text('compare', Input::old('compare'), ['class' => 'form-control']) !!}
        </p>
        <p>{!! Form::submit('Valider', ['class' => 'btn btn-mini btn-primary', 'data-loading-text' => 'Chargement en cours...']) !!}</p>
    </div>
    {!! Form::close() !!}

@endsection

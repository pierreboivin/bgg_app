@extends('layouts.master')

@section('title', 'Accueil')
@section('meta.description', 'Application permettant d\'afficher plusieurs statistiques à partir des données utilisateurs de boargamegeek.com.')

@section('content')

    <div class="jumbotron login vertical-center">
        <div class="container">

            <h1>Système de statistiques BoardGameGeek</h1>

            <div class="well">
                Ce site web vous permet de consulter plusieurs statistiques concernant vos parties jouées et vos jeux possédés sur boardgamegeek.com. Plusieurs rapports et graphiques sont à la disposition des utilisateurs.
            </div>

            <h2>Visitez le site en tant qu'invité</h2>

            {!! Form::open(array('url' => '/guestLogin')) !!}
            <div class="form-group">
                <p>
                    {!! Form::label('username', 'Nom d\'utilisateur BGG') !!}
                    {!! Form::text('username', Input::old('username'), ['class' => 'form-control']) !!}
                </p>
                <p>{!! Form::submit('Visiter', ['class' => 'btn btn-mini btn-primary', 'id' => 'btnGuestLogin', 'data-loading-text' => 'Chargement en cours...']) !!}</p>
            </div>
            {!! Form::close() !!}

            <h2>Connectez-vous</h2>

            {!! Form::open(array('url' => '/userLogin')) !!}

            <div class="form-group">
                @foreach ($errors->all() as $message)
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endforeach

                <p>
                    {!! Form::label('email', 'Adresse courriel') !!}
                    {!! Form::text('email', Input::old('email'), ['placeholder' => 'info@email.com', 'class' => 'form-control']) !!}
                </p>

                <p>
                    {!! Form::label('password', 'Mot de passe') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </p>

                <p>{!! Form::submit('Se connecter', ['class' => 'btn btn-mini btn-primary', 'id' => 'btnUserLogin', 'data-loading-text' => 'Chargement en cours...']) !!}</p>
            </div>
            {!! Form::close() !!}


        </div>
    </div>

@endsection

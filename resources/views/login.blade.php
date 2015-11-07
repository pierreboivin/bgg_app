@extends('layouts.master')

@section('title', 'Connexion')

@section('content')

    <div class="jumbotron login vertical-center">
        <div class="container">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <h2>Connectez-vous</h2>

            {!! Form::open(array('url' => '/userLogin')) !!}

            <div class="form-group">
                <!-- if there are login errors, show them here -->
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
        </div>
    </div>

@endsection

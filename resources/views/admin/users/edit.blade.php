@extends('layouts.master')

@section('title', 'Administration > Utilisateurs > Édition')
@section('class', 'admin')

@section('content')

    @if(isset($user))
        <h1>Modification d'un utilisateur</h1>
    @else
        <h1>Création d'un utilisateur</h1>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
    @endif

    @if(isset($user))
        {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch']) !!}
    @else
        {!! Form::open(['route' => 'admin.users.store']) !!}
    @endif

        <div class="form-group">
            {!! Form::label('name', 'Nom:') !!}
            {!! Form::text('name', null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('password', 'Mot de passe:') !!}
            {!! Form::password('password',  array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('password', 'Confirmez mot de passe:') !!}
            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('email', 'Courriel:') !!}
            {!! Form::text('email', null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('type', 'Type:') !!}
            {!! Form::select('type', array('nobgg' => 'Sans utilisateur BGG', 'normal' => 'Normal', 'admin' => 'Administrateur'), null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('bggusername', 'BGG - Nom d\'utilisateur:') !!}
            {!! Form::text('bggusername', null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('bggpassword', 'BGG - Mot de passe:') !!}
            {!! Form::text('bggpassword', null, array('class' => 'form-control')) !!}
        </div>


        <div class="form-group pull-left">
            {!! Form::submit('Sauvegarder', array('class' => 'btn btn-default')) !!}
            <a class="btn btn-default btn-close" href="{!! route('admin.users.index') !!}">Annuler</a>
        </div>

    {!! Form::close() !!}

@endsection
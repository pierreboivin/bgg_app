@extends('layouts.master')

@section('title', 'Modules > Listes > Édition')
@section('class', 'admin')

@section('content')

    @if(isset($user))
        <h1>Modification d'une liste</h1>
    @else
        <h1>Création d'une liste</h1>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
            </ul>
        </div>
    @endif

    @if(isset($list))
        {!! Form::model($list, ['route' => ['modules.lists.admin.update', $list->id], 'method' => 'patch']) !!}
    @else
        {!! Form::open(['route' => 'modules.lists.admin.store']) !!}
    @endif

        <div class="form-group">
            {!! Form::label('name', 'Nom:') !!}
            {!! Form::text('name', null, array('class' => 'form-control')) !!}
        </div>

        <div class="form-group">
            {!! Form::label('data', 'Liste de jeux:') !!}
            {!! Form::textarea('data', null, array('class' => 'form-control')) !!}
        </div>


        <div class="form-group pull-left">
            {!! Form::submit('Sauvegarder', array('class' => 'btn btn-default')) !!}
            <a class="btn btn-default btn-close" href="{!! route('modules.lists.admin.index') !!}">Annuler</a>
        </div>

    {!! Form::close() !!}

@endsection
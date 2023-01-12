@extends('layouts.master')

@section('title', 'Administration > Utilisateurs')
@section('class', 'admin')

@section('content')

    <h1>Administration des utilisateurs</h1>

    <p>{!! link_to_route('admin.users.create', 'Ajouter un utilisateur') !!}</p>

    @if ($users->count())
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Courriel</th>
                <th>Nom</th>
                <th>Type</th>
                <th>BGG - Nom d'utilisateur</th>
                <th>BGG - Mot de passe</th>
                <th colspan="2"></th>
            </tr>
            </thead>

            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->type }}</td>
                    <td>{{ $user->bggusername }}</td>
                    <td>{{ $user->bggpassword }}</td>
                    <td>{!! link_to_route('admin.users.edit', 'Modifier', array($user->id), array('class' => 'btn btn-info')) !!}</td>
                    <td>
                        {!! Form::open(array('method' => 'DELETE', 'route' => array('admin.users.destroy', $user->id))) !!}
                        {!! Form::submit('Supprimer', array('class' => 'btn btn-danger')) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>
    @else
        Il n'y a pas d'utilisateur
    @endif

@endsection

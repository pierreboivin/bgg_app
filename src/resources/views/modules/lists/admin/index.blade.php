@extends('layouts.master')

@section('title', 'Modules > Listes')
@section('class', 'admin')

@section('content')

    <h1>Administration des listes</h1>

    <p>{!! link_to_route('modules.lists.admin.create', 'Ajouter une liste', [], ['class' => 'btn btn-info']) !!}</p>

    <!-- TODO : gestion des accès, afficher seulement les listes que l'utilisateur a créé -->

    @if ($lists->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th colspan="2"></th>
                </tr>
            </thead>

            <tbody>
            @foreach ($lists as $list)
                <tr>
                    <td>{{ $list->name }}</td>
                    <td>
                        {!! Form::open(array('method' => 'DELETE', 'route' => array('modules.lists.admin.destroy', $list->id))) !!}
                        {!! link_to_route('modules.lists.admin.edit', 'Modifier', array($list->id), array('class' => 'btn btn-info')) !!}
                        {!! link_to_route('modules.lists.view.show', 'Voir', array($list->slug), array('class' => 'btn btn-info')) !!}
                        {!! Form::submit('Supprimer', array('class' => 'btn btn-danger')) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>
    @else
        Il n'y a pas de listes
    @endif

@endsection

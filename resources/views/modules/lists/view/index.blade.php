@extends('layouts.master')

@section('title', 'Modules > Listes')
@section('class', '')

@section('content')

    <h1>Listes de jeux</h1>

    @if ($lists->count())
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Nom</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($lists as $list)
                <tr>
                    <td>{!! link_to_route('modules.lists.view.show', $list->name, array($list->slug)) !!}</td>
                </tr>
            @endforeach

            </tbody>

        </table>
    @else
        Il n'y a pas de listes
    @endif

@endsection

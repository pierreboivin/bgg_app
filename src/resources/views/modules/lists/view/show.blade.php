@extends('layouts.master')

@section('title', 'Modules > Liste')
@section('class', 'collection')

@section('content')
    <h1>{{ $list['name'] }} </h1>

    <input type="hidden" name="collectionUrl" value="{{ url('lists/', [$list['url']]) }}" />

    @include('partials.list_games')

@endsection

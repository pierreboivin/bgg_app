@extends('layouts.master')

@section('title', 'Collection')
@section('class', 'collection')

@section('content')

    @include('partials.userInfo')

    <input type="hidden" name="collectionUrl" value="{{ url('collection', [$userinfo['username']]) }}" />

    @include('partials.list_games')

@endsection

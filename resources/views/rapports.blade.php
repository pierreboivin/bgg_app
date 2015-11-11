@extends('layouts.master')

@section('title', 'Rapports')
@section('class', 'rapports')

@section('content')

    @include('partials.userInfo')

    <h2>Mensuel</h2>

    {!! Form::open(array('url' => Request::fullUrl())) !!}
    <div class="form-group">
        <p>
        {!! Form::label('month', 'Mois') !!}
        {!! Form::select('month', $listMonth) !!}
        </p>
        <p>{!! Form::submit('Afficher le rapport', ['class' => 'btn btn-mini btn-primary']) !!}</p>
    </div>
    {!! Form::close() !!}

@endsection

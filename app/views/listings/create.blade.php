@extends('layouts.scaffold')

@section('main')

<h1>Create Listing</h1>

{{ Form::open(array('route' => 'listings.store')) }}
    <ul>
        <li>
            {{ Form::label('price', 'Price:') }}
            {{ Form::input('number', 'price') }}
        </li>

        <li>
            {{ Form::label('year', 'Year:') }}
            {{ Form::input('number', 'year') }}
        </li>

        <li>
            {{ Form::label('date', 'Date:') }}
            {{ Form::text('date') }}
        </li>

        <li>
            {{ Form::label('url', 'Url:') }}
            {{ Form::text('url') }}
        </li>

        <li>
            {{ Form::label('region', 'Region:') }}
            {{ Form::text('region') }}
        </li>

        <li>
            {{ Form::label('search', 'Search:') }}
            {{ Form::text('search') }}
        </li>

        <li>
            {{ Form::submit('Submit', array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@stop



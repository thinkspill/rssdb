@extends('layouts.scaffold')

@section('main')

<h1>Edit Listing</h1>
{{ Form::model($listing, array('method' => 'PATCH', 'route' => array('listings.update', $listing->id))) }}
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
            {{ Form::submit('Update', array('class' => 'btn btn-info')) }}
            {{ link_to_route('listings.show', 'Cancel', $listing->id, array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}

@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif

@stop
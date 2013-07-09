@extends('layouts.scaffold')

@section('main')

<h1>All Listings</h1>

<p>{{ link_to_route('listings.create', 'Add new listing') }}</p>

@if ($listings->count())
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Price</th>
				<th>Year</th>
				<th>Date</th>
				<th>Url</th>
				<th>Region</th>
				<th>Search</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($listings as $listing)
                <tr>
                    <td>{{{ $listing->price }}}</td>
					<td>{{{ $listing->year }}}</td>
					<td>{{{ $listing->date }}}</td>
					<td>{{{ $listing->url }}}</td>
					<td>{{{ $listing->region }}}</td>
					<td>{{{ $listing->search }}}</td>
                    <td>{{ link_to_route('listings.edit', 'Edit', array($listing->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('listings.destroy', $listing->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    There are no listings
@endif

@stop
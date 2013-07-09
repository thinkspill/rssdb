@extends('layouts.scaffold')

@section('main')

<h1>Show Listing</h1>

<p>{{ link_to_route('listings.index', 'Return to all listings') }}</p>

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
    </tbody>
</table>

@stop
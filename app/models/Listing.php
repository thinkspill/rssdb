<?php

class Listing extends Eloquent {
    protected $guarded = array();

    public static $rules = array(
		'year' => 'required',
		'date' => 'required',
		'search' => 'required'
	);
}
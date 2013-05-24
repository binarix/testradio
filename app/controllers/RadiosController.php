<?php

class RadiosController extends \BaseController {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('radios.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return Redirect::route('radios.create')
			->withInput()
			->with('message', "Input::get('station') from store method = ".Input::get('station'));
	}

}
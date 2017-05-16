<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
	/**
	 * Retrieve the cached version of the homepage
	 * @return string  The cached version of the homepage, with the full URL replacing placeholder values
	 */
	public function index()
	{

		return \Cache::remember('homepage', 30, function() {
			
			$response = file_get_contents(app()->basePath('index.html'));

			$response = str_replace('{{url}}', url(), $response);

			return $response;

		});

	}

}
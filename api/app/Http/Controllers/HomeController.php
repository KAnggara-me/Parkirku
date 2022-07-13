<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plate;

class HomeController extends Controller
{
	public function index()
	{
		$data = Plate::select("*")->get()->last();

		return view('home', [
			'title' => 'Home',
			'data' => $data,
		]);
	}
}

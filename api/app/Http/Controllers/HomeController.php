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

	public function review()
	{
		$data = Plate::select("*")->orderBy('login', 'desc')->get();

		return view('review', [
			'title' => 'Review',
			'data' => $data,
		]);
	}

	public function report()
	{
		$data = Plate::select("*")->get()->last();

		return view('report', [
			'title' => 'Report',
			'data' => $data,
		]);
	}

	public function delete(Request $req)
	{
		$uid = $req->uid;
		$data = Plate::where("uid", "=", $uid);
		$data->delete();

		return redirect('/review')->with(['success' =>  $uid . ' Berhasil Dihapus']);
	}

	public function detail(Request $req)
	{
		$uid = $req->uid;
		$data = Plate::select("*")->where("uid", "=", $uid)->get()->last();

		return view('detail', [
			'title' => 'Detail ' . $data['plate1'],
			'data' => $data,
		]);
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plate;

class APIController extends Controller
{
	public function index()
	{
		return abort(403, 'Unauthorized action.');
	}

	public function login(Request $request)
	{
		$uid = $request->input('uid');

		$cek = Plate::where('uid', "=", $uid)->first();

		if ($cek) {
			return response()->json(["msg" => "Not OK"], 409, [], JSON_NUMERIC_CHECK);
		}

		if (!$uid) {
			return response()->json(["msg" => "Not OK"], 400, [], JSON_NUMERIC_CHECK);
		}
		$plate = new Plate;
		$plate->uid = $uid;
		$plate->status = 1;
		$plate->save();

		$data = Plate::where('uid', "=", $uid)->first();
		return response()->json($data, 201, [], JSON_NUMERIC_CHECK);
	}

	public function get(Request $request)
	{
		$token = $request->header('Authorization');

		$uid = Plate::select('*')->get();

		return response()->json($uid, 201, [], JSON_NUMERIC_CHECK);
	}

	public function getById(Request $request)
	{
		$token = $request->header('Authorization');
		$id = $request->id;

		$uid = Plate::select('*')->where('id', "=", $id)->first();

		if (!$uid) {
			return abort(404, 'Not Found.');
		}

		return response()->json($uid);
	}
}

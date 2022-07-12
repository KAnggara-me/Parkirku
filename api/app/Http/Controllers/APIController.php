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
		if (!$uid) {
			return response()->json(["msg" => "Not OK"], 400, [], JSON_NUMERIC_CHECK);
		}

		$cek = Plate::where('uid', "=", $uid)->first();
		if ($cek) {
			return response()->json(["msg" => "Not OK"], 409, [], JSON_NUMERIC_CHECK);
		}

		$plate = new Plate;
		$plate->uid = $uid;
		$plate->status = 1;
		$plate->save();

		$data = Plate::where('uid', "=", $uid)->first();
		return response()->json($data, 201, [], JSON_NUMERIC_CHECK);
	}

	public function logout(Request $request)
	{
		$uid = $request->input('uid');
		if (!$uid) {
			return response()->json(["msg" => "Not OK"], 400, [], JSON_NUMERIC_CHECK);
		}

		$cek = Plate::where('uid', "=", $uid)->first();
		if (!$cek) {
			return response()->json(["msg" => "Not OK"], 404, [], JSON_NUMERIC_CHECK);
		}
		$plate = Plate::where('uid', "=", $uid)->first();

		if ($plate->plate1 != $plate->plate2) {
			return response()->json(["msg" => "Plate Not Match"], 406, [], JSON_NUMERIC_CHECK);
		}

		Plate::where('uid', '=', $uid)->update(['status' => 0, 'logout' => date('Y-m-d H:i:s')]);
		$update = $cek = Plate::where('uid', "=", $uid)->first();
		return response()->json($update, 201, [], JSON_NUMERIC_CHECK);
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

	public function delete(Request $req)
	{
		$uid = $req->uid;

		if (!$uid) {
			return response()->json(["mgs" => "Not OK"], 404, [], JSON_NUMERIC_CHECK);
		}

		Plate::where("uid", "=", $uid)->delete();

		return response()->json(["msg" => "Deleted"], 200, [], JSON_NUMERIC_CHECK);
	}

	public function getLast(Request $req)
	{
		$data = Plate::select("*")->get()->last();
		return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
	}

	public function update(Request $request)
	{
		$plate = $request->plate;
		if (!$plate) {
			return response()->json(["msg" => "Not OK"], 400, [], JSON_NUMERIC_CHECK);
		}
		Plate::select("*")->get()->last()->update(['status' => 2, 'plate1' => $plate]);
		return response()->json(["msg" => "Updated"], 200, [], JSON_NUMERIC_CHECK);
	}
}

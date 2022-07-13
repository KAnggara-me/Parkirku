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
			return response()->json(["msg" => "Missing UID"], 400, [], JSON_NUMERIC_CHECK);
		}

		$cek = Plate::where('uid', "=", $uid)->first();
		if (!$cek) {
			return response()->json(["msg" => "UID not Found"], 404, [], JSON_NUMERIC_CHECK);
		}

		Plate::where('uid', '=', $uid)->update(['status' => 3]);
		$update = Plate::where('uid', "=", $uid)->first();
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

		$filename = $request->file('img')->store('plates-img');

		Plate::select("*")->get()->last()->update(['status' => 2, 'plate1' => $plate, 'image1' => $filename]);
		return response()->json(["msg" => "Updated"], 200, [], JSON_NUMERIC_CHECK);
	}

	public function update2(Request $request)
	{
		$plate = $request->plate;
		if (!$plate) {
			return response()->json(["msg" => "Not OK"], 400, [], JSON_NUMERIC_CHECK);
		}

		$data = Plate::select('*')->where('plate1', '=', $plate)->get()->last();
		$filename = $request->file('img')->store('plates-img');

		if (strtolower($plate) != strtolower($data->plate1)) {
			Plate::select("*")->get()->last()->update(['status' => 4, 'plate2' => $plate, 'image2' => $filename]);
			return response()->json(["msg" => "Plate Not Match"], 404, [], JSON_NUMERIC_CHECK);
		}

		Plate::select("*")->get()->last()->update(['status' => 0, 'plate2' => $plate, 'image2' => $filename, 'logout' => date('Y-m-d H:i:s')]);
		return response()->json(["msg" => "Updated"], 200, [], JSON_NUMERIC_CHECK);
	}
}

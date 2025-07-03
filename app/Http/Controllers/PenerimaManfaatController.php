<?php

namespace App\Http\Controllers;

use App\Models\PenerimaManfaat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenerimaManfaatController extends Controller
{
    public function index() {
        $PM = PenerimaManfaat::all();
        return response()->json([
            'success' => true,
            'data' => $PM,
            'message' => 'Data Penerima Manfaat'
        ]);
    }

    public function createOrUpdate(Request $request) {
        $validator = Validator::make([
            'id' => ['nullable, exists:penerima_manfaats,id'],
            'nama' => ['required, string'],
            'alamat' => ['nullable, string'],
            'latitude' => ['nullable, numeric'],
            'longitude' => ['nullable, numeric'],
            'kondisi_sosial' => ['nullable, string'],
        ]);

        $data_input = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nilai_estimasi' => $request->nilai_estimasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi_sosial' => $request->kondisi_sosial,
        ];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $PM = PenerimaManfaat::createOrUpdate(['id' => $request->id], $data_input);

        $message = $PM->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Penerima Manfaat',
            'data' => $PM
        ]);
    }

    public function destroy(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:penerima_manfaats,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = PenerimaManfaat::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Penerima Manfaat',
            'data' => $data
        ]);
    }
}

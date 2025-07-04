<?php

namespace App\Http\Controllers;

use App\Models\Wakaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WakafController extends Controller
{
    public function show() {
        return view('wakaf.index');
    }

    public function index() {
        $wakaf = Wakaf::with('nazhir')->get();
        return response()->json([
            'success' => true,
            'data' => $wakaf,
            'message' => 'Data Wakaf'
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        $validator = Validator::make([
            'id' => ['nullable', 'exists:wakafs','id'],
            'nama_aset' => ['required'],
            'jenis_aset' => ['required, in:Tanah,Bangunan,Uang'],
            'nilai_estimasi' => ['nullable, numeric'],
            'lokasi' => ['nullable, string'],
            'latitude' => ['nullable, numeric'],
            'longitude' => ['nullable, numeric'],
            'status' => ['required. in:Produktif,Tidak Produktif'],
            'nazhir_id' => ['required, exists:nazhirs,id'],
        ]);

        $data_input = [
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
            'nilai_estimasi' => $request->nilai_estimasi,
            'lokasi' => $request->lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
            'nazhir_id' => $request->nazhir_id,
        ];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $wakaf = Wakaf::createOrUpdate(['id' => $request->id], $data_input);

        $message = $wakaf->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Wakaf',
            'data' => $wakaf
        ]);
    }

    public function destroy(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:wakafs,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = Wakaf::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Wakaf',
            'data' => $data
        ]);
    }
}

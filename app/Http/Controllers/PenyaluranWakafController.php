<?php

namespace App\Http\Controllers;

use App\Models\PenyaluranWakaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyaluranWakafController extends Controller
{
    public function show() {
        return view('penyaluran.index');
    }

    public function index() {
        $PW = PenyaluranWakaf::all()->with(['wakaf', 'penerima'])->get();
        return response()->json([
            'success' => true,
            'data' => $PW,
            'message' => 'Data Penyaluran Wakaf'
        ]);
    }

    public function createOrUpdate(Request $request) {
        $validator = Validator::make([
            'id' => ['nullable', 'exists:penyaluran_wakafs,id'],
            'wakaf_id' => ['required', 'exists:wakafs,id'],
            'penerima_id' => ['required', 'exists:penerima_manfaats,id'],
            'tanggal_diterima' => ['required'],
            'keterangan' => ['nullable']
        ]);

        $data_input = [
            'wakaf_id' => $request->id,
            'penerima_id' => $request->penerima_id,
            'tanggal_diterima' => $request->tanggal_diterima,
            'keterangan' => $request->keterangan
        ];

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $PW = PenyaluranWakaf::createOrUpdate(['id' => $request->id], $data_input);

        $message = $PW->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Penerima Manfaat',
            'data' => $PW
        ]);
    }

    public function destroy(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:penyaluran_wakafs,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = PenyaluranWakaf::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Penyaluran Wakaf',
            'data' => $data
        ]);
    }
}

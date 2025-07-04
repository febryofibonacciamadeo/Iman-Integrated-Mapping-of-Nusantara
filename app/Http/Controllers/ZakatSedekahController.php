<?php

namespace App\Http\Controllers;

use App\Models\ZakatSedekah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ZakatSedekahController extends Controller
{
    public function show() {
        return view('zakat.index');
    }

    public function index() {
        $zakat = ZakatSedekah::with(['donatue', 'penerima'])->get();
        return response()->json([
            'success' => true,
            'data' => $zakat,
            'message' => 'Data Zakat'
        ]);
    }

    public function createOrUpdate(Request $request) {
        $validator = Validator::make([
            'id' => ['nullable', 'exists:zakat_sedekahs,id'],
            'donatur_id' => ['required, exists:donaturs,id'],
            'jenis' => ['required, in:Zakat,Sedekah'],
            'kategori' => ['nullable, string'],
            'jumlah' => ['required, numeric'],
            'tanggal' => ['required, date'],
            'disalurkan_ke' => ['nullable,exists:penerima_manfaat,id'],
        ]);

        $data_input = [
            'donatur_id' => $request->donatur_id,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'disalurkan_ke' => $request->disalurkan_ke,
        ];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $zakat = ZakatSedekah::createOrUpdate(['id' => $request->id], $data_input);

        $message = $zakat->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Zakat',
            'data' => $zakat
        ]);
    }

    public function destroy(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:zakat_sedekahs,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = ZakatSedekah::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Zakat',
            'data' => $data
        ]);
    }
}

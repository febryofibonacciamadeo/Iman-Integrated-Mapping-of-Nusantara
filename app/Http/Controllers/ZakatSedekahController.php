<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Rule;
use Illuminate\Container\Attributes\Auth;

class ZakatSedekahController extends Controller
{
    public function index()
    {
        $donatur = Donatur::all();
        return response()->json([
            'succes' => true,
            'data' => $donatur,
            'message' => 'Data donatur'
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        $validator = FacadesValidator::make([
            'id' => ['nullable', 'exists:donaturs,id'],
            'nama_lenkap' => ['required'],
            'jenis_identitas' => ['required'],
            'jenis_kelamin' => ['required'],
            'email' => ['required', Rule::unique('donaturs', 'email')->ignore($request->id)],
            'no_hp' => ['required'],
            'alamat' => ['required']
        ]);

        $data_input = [
            'nama_lenkap' => $request->nama_lengkap,
            'jenis_identitas' => $request->jenis_identitas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ];
        
        $donatur = Donatur::createOrUpdate(['id' => $request->id], $data_input);

        $message = $donatur->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Donatue',
            'data' => $donatur
        ]);
    }
    
    public function destroy(string $id)
    {
        // // Validasi data
        // $validator = FacadesValidator::make($request->all(), [
        //     'id' => ['nullable', 'exists:jadwal_inspirasi_pagi,id'],
        // ]);

        // // Cek jika validasi gagal
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // // Ambil id
        // $id = $request->id;

        // // Hapus dari DB utama
        // $data = JadwalInspirasiPagi::where('id', $id)->first();
        // $data->delete();

        // $data_input = ['deleted_by' => Auth::id()];

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Berhasil Hapus Jadwal Inspirasi Pagi',
        //     'data' => $data
        // ]);
    }
}

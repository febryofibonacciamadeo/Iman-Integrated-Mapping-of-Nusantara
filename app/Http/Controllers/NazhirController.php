<?php

namespace App\Http\Controllers;

use App\Models\Nazhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NazhirController extends Controller
{
    public function index()
    {
        $nazhir = Nazhir::all();
        return response()->json([
            'success' => true,
            'data' => $nazhir,
            'message' => 'Data Nazhir'
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        $validator = Validator::make([
            'id' => ['nullable', 'exists:nazhirs,id'],
            'nama_lenkap' => ['required'],
            'no_hp' => ['required'],
            'alamat' => ['required']
        ]);

        $data_input = [
            'nama_lenkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ];

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $nazhir = Nazhir::createOrUpdate(['id' => $request->id], $data_input);

        $message = $nazhir->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Nazhir',
            'data' => $nazhir
        ]);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:nazhirs,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = Nazhir::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Nazhir',
            'data' => $data
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DonaturController extends Controller
{
    public function show() {
        return view('donatur.index');
    }

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
        $validator = Validator::make([
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

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $donatur = Donatur::createOrUpdate(['id' => $request->id], $data_input);

        $message = $donatur->wasRecentlyCreated ? 'Tambah' : 'Update';

        return response()->json([
            'success' => true,
            'message' => 'Berhasil ' . $message . ' Donatur',
            'data' => $donatur
        ]);
    }
    
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'exists:donaturs,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = $request->id;

        $data = Donatur::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Hapus Donatur',
            'data' => $data
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Rule;

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
            'jenis_identitas' => ['required'],
            'jenis_kelamin' => ['required'],
            'nama_lenkap' => ['required'],
            'email' => ['require', Rule::unique('donaturs', 'email')->ignore($request->id)],
            ''
        ]);

        $data = Donatur::create($validator);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil'
        ]);
    }
    
    public function destroy(string $id)
    {
        //
    }
}

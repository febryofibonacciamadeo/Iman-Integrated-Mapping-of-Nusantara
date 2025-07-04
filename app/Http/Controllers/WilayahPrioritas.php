<?php

namespace App\Http\Controllers;

use App\Models\WilayahPrioritas as ModelsWilayahPrioritas;
use Illuminate\Http\Request;

class WilayahPrioritas extends Controller
{
    public function show() {
        return view('wilayah-prioritas.index');
    }

    public function index() {
        $WP = ModelsWilayahPrioritas::all();
        return response()->json([
            'success' => true,
            'data' => $WP,
            'message' => 'Data Wilayah Prioritas'
        ]);
    }
}

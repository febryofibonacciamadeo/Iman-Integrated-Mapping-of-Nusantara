<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyaluranWakaf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penyaluran_wakafs';
    protected $guarded = ['id'];

    public function wakaf() {
        return $this->belongsTo(Wakaf::class);
    }

    public function penerima() {
        return $this->belongsTo(PenerimaManfaat::class, 'penerima');
    }
}

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

    public function pembuat() {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function pengupdate() {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    
    public function penghapus() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

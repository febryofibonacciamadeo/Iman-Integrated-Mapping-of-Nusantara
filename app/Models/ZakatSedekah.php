<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZakatSedekah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zakat_sedekahs';
    protected $guarded = ['id'];

    public function donatur() {
        return $this->belongsTo(Donatur::class);
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

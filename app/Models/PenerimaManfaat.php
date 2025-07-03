<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaManfaat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penerima_manfaats';
    protected $guarded = ['id'];

    public function disalurkan_ke() {
        return $this->hasMany(ZakatSedekah::class, 'disalurkan_ke');
    }

    public function penyaluran_wakaf() {
        return $this->hasMany(PenyaluranWakaf::class, 'penerima');
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

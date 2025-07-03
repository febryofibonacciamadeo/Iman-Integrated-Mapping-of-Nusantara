<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wakaf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wakafs';
    protected $guarded = ['id'];

    public function nazhir()
    {
        return $this->belongsTo(Nazhir::class);
    }

    public function penyaluran()
    {
        return $this->hasMany(PenyaluranWakaf::class);
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

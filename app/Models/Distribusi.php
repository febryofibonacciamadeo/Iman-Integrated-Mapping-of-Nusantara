<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribusi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'distribusis';
    protected $guarded = ['id'];

    public function donatur()
    {
        return $this->belongsTo(Donatur::class);
    }

    public function scopeWakaf($query)
    {
        return $query->where('jenis', 'wakaf');
    }

    public function scopeZakat($query)
    {
        return $query->where('jenis', 'zakat');
    }

    public function scopeSedekah($query)
    {
        return $query->where('jenis', 'sedekah');
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

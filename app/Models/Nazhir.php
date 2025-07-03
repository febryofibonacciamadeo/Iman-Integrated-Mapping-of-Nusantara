<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nazhir extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nazhirs';
    protected $guarded = ['id'];

    public function wakaf() {
        return $this->hasMany(Wakaf::class);
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

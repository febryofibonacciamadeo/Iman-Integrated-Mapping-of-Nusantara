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
}

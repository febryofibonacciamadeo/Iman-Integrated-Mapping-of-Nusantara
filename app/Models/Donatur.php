<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donatur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'donaturs';
    protected $guarded = ['id'];

    public function zakatSedekahs() {
        return $this->hasMany(ZakatSedekah::class);
    }
}

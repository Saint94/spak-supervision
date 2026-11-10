<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'ZoCode','ZoNom','ZoVille','ZoIsOn','ZoDateCree',
        'ZoUser','ZoDateOff','ZoDateLastActiv','ZoEntite'
     ];

    protected $table = 'zones';
    protected $primaryKey = 'ZoNumero';
    public $timestamps = false;
}

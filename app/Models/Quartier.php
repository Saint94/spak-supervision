<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    use HasFactory;

    protected $fillable = [
        'QuNom','QuVille','QuZone','QuIsOn','QuDateOff','QuEntite'
     ];

    protected $table = 'quartiers';
    protected $primaryKey = 'QuNumero';
    public $timestamps = false;
}

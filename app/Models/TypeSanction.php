<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeSanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'SoDesign','SoMontantDef','SoType','SoIsOn',
        'SoTypePerson','SoTypeProfil','SoEntite'
     ];

    protected $table = 'sanctions';
    protected $primaryKey = 'SoNumero';
    public $timestamps = false;
}

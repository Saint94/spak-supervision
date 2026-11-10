<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carriere extends Model
{
    use HasFactory;

    protected $fillable = [
        'CaAgent','CaDateDebut','CaDateFin',
        'CaPoste','CaVille','CaEntite'
     ];

    protected $table = 'carrieres';
    protected $primaryKey = 'CaNumero';
    public $timestamps = false;
}

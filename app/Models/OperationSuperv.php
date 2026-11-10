<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSuperv extends Model
{
    use HasFactory;

    protected $fillable = [
        'OsDate','OsVille','OsZone','OsSuperviseur','OsFaction','OsHeureDebut','OsHeureFin',
        'OsIsRetourFiche','OsIsSupervSigne','OsDateHFiche','OsStatut','OsDetails','OsEntite'
     ];

    protected $table = 'operations_superv';
    protected $primaryKey = 'OsNumero';
    public $timestamps = false;
}

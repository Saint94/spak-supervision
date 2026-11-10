<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RondeOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'RoDate','RoDateDebut','RoHeureDebut','RoDateFin','RoHeureFin','RoResponsable1',
        'RoResponsable2','RoResponsable3','RoObjectif','RoPlanAction','RoStatut','RoEntite'
     ];

    protected $table = 'ronde_operations';
    protected $primaryKey = 'RoNumero';
    public $timestamps = false;
}

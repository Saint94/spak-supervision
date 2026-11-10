<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoisPaie extends Model
{
    use HasFactory;

    protected $fillable = [
        'MpIdMois','MpAnnee','MpLibelle','MpDateDebut','MpDateFin',
        'MpStatut','MpCreePar','MpDateHCree','MpCloturePar',
        'MpDateHCloture','MpNbPersonSecDeb','MpNbPersonSecFin',
        'MpNbPersonAdmDeb','MpNbPersonAdmFin','MpObserv','MpEntite'
     ];

    protected $table = 'mois_paie';
    protected $primaryKey = 'MpNumero';
    public $timestamps = false;
}

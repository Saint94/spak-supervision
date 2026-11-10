<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'FcClient','FcNumFacture','FcIsMoisPeriode','FcDate','FcMois','FcAnnee',
        'FcMontantHT','FcMontantTTC','FcTotalPaye','FcDateDebut','FcDateFin',
        'FcNbVigileJour','FcNbVigileNuit','FcPrixUVigile',
        'FcNbreMCJour','FcNbreMCNuit','FcPrixUMC',
        'FcNbSupervJr','FcNbSupervNu','FcPrixUSuperv',
        'FcNbComdoJr','FcNbComdoNu','FcPrixUComm',
        'FcNbJourFact','FcNbNuitFact','FcMtSolde','FcIsSolde',
        'FcUserCree','FcDetail','FcDateHCree','FcEntite'
     ];

    protected $table = 'factures_clients';
    protected $primaryKey = 'FcNumero';
    public $timestamps = false;
}

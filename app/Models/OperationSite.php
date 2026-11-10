<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'SoOperSuperv','SoSite','SoAgentPgme','SoAgentOper','SoNomPrenAgent',
        'SoIsAgentSigne','SoHeureArrivAg','SoHeureDepart','SoPresentation',
        'SoObservSuperv','SoObservClt','SoStatut','SoIsPointageOK',
        'SoIsPresent','SoEcartPointage','SoLatitudeX','SoLongitudeY',
        'SoBiometricPtge','SoIsPtgeBiometricOK','SoChemPhoto','SoEntite'
     ];

    protected $table = 'sites_oper_superv';
    protected $primaryKey = 'SoNumero';
    public $timestamps = false;
}

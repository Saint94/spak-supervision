<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvenantContrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'AcClient','AcDate','AcRefAven','AcType','AcDateEffet',
        'AcDateFin','AcPrixAgent','AcNbAgentJr','AcNbAgentNu',
        'AcPrixUMC','AcNbreMCJour','AcNbreMCNuit','AcPrixUSuperv',
        'AcNbSupervJr','AcNbSupervNu','AcPrixUComm','AcNbComdoJr',
        'AcNbComdoNu','AcNbreOperVideo','AcPrixUOperVideo','AcNbLocatVidSurv',
        'AcPrixULocVidSurv','AcNbInstVidSurv','AcPrixUInstVidSurv',
        'AcChemDocContrat','AcUserCree','AcDateHCree',
        'AcIsOn','AcDateOff','AcDetails','AcEntite'
     ];

    protected $table = 'avenants_contrat';
    protected $primaryKey = 'AcNumero';
    public $timestamps = false;
}

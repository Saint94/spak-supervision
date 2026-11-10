<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'ClCode','ClDesign','ClTel1','ClTel2','ClEmail',
        'ClDateDebut','ClVilleSiege','ClQuartier','ClSectActivite',
        'ClNbreSites','ClNbAgentJour','ClNbAgentNuit','ClUserCree',
        'ClDateHCree','ClIsOn','ClIsTVAOn','ClMtImpayes','ClNbMoisImp',
        'ClDateOff','ClContactlNom','ClModePaiem','ClTypeReglem',
        'ClJourReglem','ClIsVigileOn','ClNbVigileJr','ClNbVigileNu',
        'ClIsMeChienOn','ClNbMeChienJr','ClNbMeChienNu','ClIsCmdoOn',
        'ClNbCmdoJr','ClNbCmdoNu','ClIsSupervOn','ClNbSupervJr',
        'ClNbSupervNu','ClIsOperVideo','ClIsLocatVideo','ClIsInstVidSurv',
        'ClNbOperVidJr','ClNbOperVideoNu','ClDetails','ClEntite'
     ];

    protected $table = 'clients';
    protected $primaryKey = 'ClNumero';
    public $timestamps = false;
}

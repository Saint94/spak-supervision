<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ScClient','ScNomSite','ScVille','ScZone',
        'ScQuartier','ScIsMultiSuperv','ScIsSiteSpak',
        'ScPrecision','ScLatitudeX','ScLongitudeY',
        'ScIsVigile','ScNbVigileJr','ScNbVigileNu',
        'ScIsMeChien','ScNbMeChienJr','ScNbMeChienNu',
        'ScIsCmdo','ScNbCmdoJr','ScNbCmdoNu',
        'ScIsSuperv','ScNbSupervJr','ScNbSupervNu',
        'ScIsOperVideo','ScIsLocatVideo','ScIsInstVidSurv',
        'ScUserCree','ScDateHCree','ScIsOn',
        'ScDateHOff','ScEntite'
     ];

    protected $table = 'sites_client';
    protected $primaryKey = 'ScNumero';
    public $timestamps = false;
}

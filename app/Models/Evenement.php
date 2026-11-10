<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'EvClient','EvType','EvTitre','EvDate','EvHeure',
        'EvDateHRappel','EvDateFinRappel','EvFreqRapAprXJr',
        'EvNbRapByJr','EvTmpEcartRappels','EvDateHNextRapp',
        'EvTitreRapp','EvMessageRapp','EvIsSonOn',
        'EvIsOnly1User','EvChemRapport',
        'EvUserRapp','EvUserCree','EvIsOn','EvEntite'
     ];

    protected $table = 'evenements';
    protected $primaryKey = 'EvNumero';
    public $timestamps = false;
}

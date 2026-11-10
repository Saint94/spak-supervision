<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    use HasFactory;

    protected $fillable = [
        'EnAbrge','EnNom','EnNomComplet','EnBP','EnTel1',
        'EnTel2','EnEmail','EnSiteWeb','EnChemLogo','EnDG',
        'EnDT','EnRAF','EnDetails','EnTxTVA','EnNbMoisMaxImpClt',
        'EnPrixUMinAgentFixe','EnPrixUMinMC','EnPrixUMinSuperv',
        'EnPrixUMinComdo'
     ];

    protected $table = 'entites';
    protected $primaryKey = 'EnNumero';
    public $timestamps = false;
}

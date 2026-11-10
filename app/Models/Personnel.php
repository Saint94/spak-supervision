<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'PeMatric','PeNom','PePrenom','PeFonction','PeTypePiece','PeDateEmission',
        'PeLieuEmission','PeDateExpire','PeStatut','PeIsSupervOn',
        'PeDateDebut','PeTel1','PeTel2','PeEmail','PeNumPieceID',
        'PeDateEmb','PeMaladie',' PeMaladieChron ','PeHopotalVisite',
        'PeAptitudTechn','PePrefTravJN','PeLastDiplom','PeNivLect',
        'PeNivEcr','PeTaille','PePoids','PeGabarit','PeSpecialite',
        'PePositionGPSLatX','PePositionGPSLongY','PeVillage',
        'PeNomParrain','PeTel1Parr','PeTel2Parr','PeGPSLatXParr',
        'PeGPSLongYParr','PeLienParrain','PeSitFam','PeNomCompagne',
        'PeTel1Compagne','PeTel2Compagne','PeNbEnfants','PeNomEcolEnfant',
        'PePretenSal','PeNatureEmpl','PeQuartier','PeVille','PeIsRondier',
        'PeJourRepos','PeDomicile','PeUserCree','PeDateHCree','PeUserModif',
        'PeDateHModif','PeDateDepart','PeUserDepart','PeDetails','PeSitePoste',
        'PeIsAppliOn','PeDateDebApp','PeDateFinApp','PeAppAccesID',
        'PeAppAccessMDP','PeDateLastAccess','PeBiometricData','PeEntite'
     ];

    protected $table = 'personnels';
    protected $primaryKey = 'PeNumero';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteRondeOper extends Model
{
    use HasFactory;

    protected $fillable = [
        'SrRondeSuperv','SrClient','SrSiteClient','SrDateDebut','SrHeureDebut','SrDateFin',
        'SrHeureFin','SrTitre','SrStatut','SrTypeNonConform','SrIsPlanAction','SrDateLimPlanAct',
        'SrResumePlanAct','SrDocumPlanAct','SrIsPlanActionOk','SrEntite'
     ];

    protected $table = 'sites_ronde_oper';
    protected $primaryKey = 'SrRondeSuperv';
    public $timestamps = false;
}

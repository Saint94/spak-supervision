<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteAgentSupCtrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'SaSupervCtrl','SaSiteClt','SaAgent','SaDateDebut',
        'SaDateFin','SaUser','SaDateH','SaIsOn','SaEntite'
     ];

    protected $table = 'sites_agent_sup_ctr';
    protected $primaryKey = 'SaNumero';
    public $timestamps = false;
}

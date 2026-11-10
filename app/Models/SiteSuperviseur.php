<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSuperviseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'SsSuperv','SsMode','SsIsAllSites','SsSiteClient',
        'SsDateDebut','SsDateFin','SsUserCree',
        'SsDateHCree','SsIsOn','SsDetails','SsEntite'
     ];

    protected $table = 'sites_superviseur';
    protected $primaryKey = 'SsNumero';
    public $timestamps = false;
}

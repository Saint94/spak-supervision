<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'CsContratClient','CsSiteClient','CsNbreAgentJr',
        'CsNbreAgentNu','CsNbreMCJr','CsNbreMCNu','CsNbSupervJr',
        'CsNbSupervNu','CsNbComdoJr','CsNbComdoNu','CsNbreOperVideo',
        'CsNbLocatVidSurv','CsNbInstVidSurv','CsIsOn','CsDateOn',
        'CsUserOn','CsDateOff','CsUserOff','CsDetails','CsEntite'
     ];

    protected $table = 'contrat_site';
    protected $primaryKey = 'CsNumero';
    public $timestamps = false;
}

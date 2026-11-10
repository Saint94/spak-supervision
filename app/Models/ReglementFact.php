<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglementFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'RfFacture','RfDate','RfMtAPayer','RfMtPaye','RfModeRegl','RfReferReglem',
        'RfNewSolde','RfDetails','RfUserCree','RfDateHCree','RfIsValide','RfEntite'
     ];

    protected $table = 'reglements_fact';
    protected $primaryKey = 'RfNumero';
    public $timestamps = false;
}

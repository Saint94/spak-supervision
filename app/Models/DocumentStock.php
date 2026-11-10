<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'DsDate',
        'DsNature',
        'DsTypeDest',
        'DsFournBenef',
        'DsRefer',
        'DsMontantHT',
        'DsMontantTTC',
        'DsIsAgentSigne',
        'DsIsGestSigne',
        'DsIsRespOperSigne',
        'DsCreePar',
        'DsDateHCree',
        'DsUserModif',
        'DsDateHModif',
        'DsEntite',
    ];

    protected $table = 'documents_stock';
    protected $primaryKey = 'DsNumero';
    public $timestamps = false;
}

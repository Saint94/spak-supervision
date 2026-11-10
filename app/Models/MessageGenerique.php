<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageGenerique extends Model
{
    use HasFactory;

    protected $fillable = [
        'MgType','MgClient','MgDate','MgMontant','MgDateLimite',
        'MgTitre','MgTexteMsg','MgChemDocFile','MgStatut','MgUserCree',
        'MgUserModif','MgDateHCree','MgDateHModif','MgEntite'
    ];

    protected $table = 'messages_generique';
    protected $primaryKey = 'MgNumero';
    public $timestamps = false;
}

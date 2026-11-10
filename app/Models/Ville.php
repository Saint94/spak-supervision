<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = [
        'ViCode','ViNom','ViRespOper','ViIsZoneOn',
        'ViIsAllSiteOn','ViIsOn','ViDateOff','ViEntite'
     ];

    protected $table = 'villes';
    protected $primaryKey = 'ViNumero';
    public $timestamps = false;
}

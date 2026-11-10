<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosterVigile extends Model
{
    use HasFactory;

    protected $fillable = [
        'PvSiteClient','PvVigile','PvStatut','PvFaction',
        'PvDateDebut','PvDateFin','PvDateHOper','PvUserCree',
        'PvIsOn','PvDateOff','PvUserOff','PvDetails','PvEntite'
     ];

    protected $table = 'poster_vigile';
    protected $primaryKey = 'PvNumero';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RondierSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'RtRondier','RtPersonnel','RtSiteClient','RtJourRepos',
        'RtDateDebut','RtIsOn','RtDateFin','RtCreePar','RtDateHPgme',
        'RtUserFin','RtDateHFin','RtEntite'
     ];

    protected $table = 'rondier_sites';
    protected $primaryKey = 'RtNumero';
    public $timestamps = false;
}

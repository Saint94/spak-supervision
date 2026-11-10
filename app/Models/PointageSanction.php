<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointageSanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'PsSanctionObserv','PsPersonnel','PsDatEffet','PsMontant','PsMotif','PsObserv',
        'PsIsConfirme','PsMtValide','PsMoisPaie','PsUser','PsDateHOper','PsEntite'
     ];

    protected $table = 'pointages_sanctions';
    protected $primaryKey = 'PsNumero';
    public $timestamps = false;
}

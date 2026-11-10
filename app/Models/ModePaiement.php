<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'MpNom','MpIsOn','MpEntite'
     ];

    protected $table = 'modes_paiement';
    protected $primaryKey = 'MpNumero';
    public $timestamps = false;
}

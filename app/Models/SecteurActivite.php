<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecteurActivite extends Model
{
    use HasFactory;

    protected $fillable = [
        'SaNom','SaIsOn','SaEntite'
     ];

    protected $table = 'secteurs_activite';
    protected $primaryKey = 'SaNumero';
    public $timestamps = false;
}

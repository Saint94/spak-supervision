<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnqueteClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'EcClient','EcDate','EcHeure','EcType','EcNivSatisf',
        'EcRemarques','EcDateRapp','EcChemDocEnq','EcEntite'
     ];

    protected $table = 'enquetes_client';
    protected $primaryKey = 'EcNumero';
    public $timestamps = false;
}

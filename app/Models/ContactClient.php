<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'CcClient','CcSiteContact','CcNom','CcFonction',
        'CcTel1','CcTel2','CcEMail','CcIsOn',
        'CcDateFin','CcDateFin'
     ];

    protected $table = 'contacts_client';
    protected $primaryKey = 'CcNumero';
    public $timestamps = false;
}

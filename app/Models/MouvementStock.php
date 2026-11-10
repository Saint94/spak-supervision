<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'MsDocumStock',
        'MsArticle',
        'MsQuantite',
        'MsSensMvm',
        'MsQteApres',
        'MsPrixArticle',
        'MsEntite'
    ];

    protected $table = 'mouvements_stock';
    protected $primaryKey = 'MsNumero';
    public $timestamps = false;
}

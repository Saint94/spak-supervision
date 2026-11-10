<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'ArCode','ArNom','ArType','ArIsCoulNoirJaunOn','ArPrixAchatCons',
        'ArPrixAchatMax','ArIsPayeParAgent','ArPrixAgent','ArPhoto',
        'ArQteStock','ArStockSeuil','ArFournDef','ArIsOn','ArCreePar',
        'ArDateHCree','ArUserModif','ArDateHModif','ArEntite'
     ];

    protected $table = 'articles';
    protected $primaryKey = 'ArNumero';
    public $timestamps = false;
}

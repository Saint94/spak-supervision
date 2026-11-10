<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'FoCode','FoNom','FoAdresse','FoTel1',
        'FoTel2','FoEmail','FoVille','FoPays',
        'FoIsTVAOn','FoIsOn','FoCreePar','FoDateHCree',
        'FoUserModif','FoDateHModif','FoEntite',
     ];

    protected $table = 'fournisseurs';
    protected $primaryKey = 'FoNumero';
    public $timestamps = false;
}

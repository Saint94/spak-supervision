<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'AuIDItem','AuTypeElt','AuTypeOper','AuDateHOper','AuUser',
        'AuID1Open','AuID2Open','AuID3Open','AuTextOuvert','AuID1Ferm',
        'AuID2Ferm','AuID3Ferm','AuTextFerm','AuDateHFerm','AuEntite'
     ];

    protected $table = 'action_users';
    protected $primaryKey = 'AuNumero';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryLogoEntite extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder','file','created_at'
     ];

    protected $table = 'temporary_logo_entite';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryDocEvenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder','file','created_at'
     ];

    protected $table = 'temporary_doc_evenement';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

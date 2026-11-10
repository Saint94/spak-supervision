<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryDocEnquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder','file','created_at'
     ];

    protected $table = 'temporary_doc_enquete';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

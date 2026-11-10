<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryImageArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder','file','created_at'
    ];

    protected $table = 'temporary_image_article';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

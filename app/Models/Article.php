<?php

namespace App\Models;

use App\Models\Concerns\Categorizable;
use App\Models\Concerns\Taggable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Categorizable, Taggable;
    
    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'content',
        'excerpt',
        'featured_image',
        'thumbnail',
        'is_featured',
        'author',
        'status',
        'published_at',
        'read_time',
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'content',
        'char_count',
        'pub_date',
        'priority',
        'image_url',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $returnType = 'array';
}

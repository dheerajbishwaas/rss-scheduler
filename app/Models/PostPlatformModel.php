<?php

namespace App\Models;

use CodeIgniter\Model;

class PostPlatformModel extends Model
{
    protected $table = 'post_platform';
    protected $primaryKey = false;

    protected $allowedFields = ['post_id', 'platform_id'];
    protected $returnType = 'array';

    public $timestamps = false;
}

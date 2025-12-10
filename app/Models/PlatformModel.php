<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatformModel extends Model
{
    protected $table = 'platforms';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
    protected $returnType = 'array';
}

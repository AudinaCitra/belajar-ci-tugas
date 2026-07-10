<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discount';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['tanggal', 'nominal'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
}
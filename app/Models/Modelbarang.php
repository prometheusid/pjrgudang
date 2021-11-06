<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table                = 'barang';
    protected $primaryKey           = 'brgid';
    protected $allowedFields        = [
        'brgid', 'brgnama'
    ];
}

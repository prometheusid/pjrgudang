<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelsatuan extends Model
{
    protected $table                = 'satuan';
    protected $primaryKey           = 'satid';
    protected $allowedFields        = [
        'satid', 'satnama'
    ];

    //searchbox
    public  function cariData($cari)
    {
        return $this->like('satnama', $cari);
    }
}

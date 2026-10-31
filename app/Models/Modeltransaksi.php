<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeltransaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';
    protected $allowedFields = [
        'transaksi_jenis',
        'transaksi_customer',
        'transaksi_total',
        'transaksi_tanggal',
    ];
    protected $useTimestamps = false;
}

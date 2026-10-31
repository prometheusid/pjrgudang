<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table                = 'barang';
    protected $primaryKey           = 'brgkode';
    protected $allowedFields        = [
        'brgkode', 'brgnama', 'brgkatid', 'brgsatid', 'brgharga', 'brggambar', 'brgstok'
    ];

    public function tampildata()
    {
        return $this->select('barang.*, kategori.katnama, satuan.satnama')
            ->join('kategori', 'barang.brgkatid=kategori.katid')
            ->join('satuan', 'barang.brgsatid=satuan.satid');
    }

    // tampil data hasil pencarian
    public function tampildata_cari($cari)
    {
        return $this->tampildata()
            ->groupStart()
            ->like('brgkode', $cari)
            ->orLike('brgnama', $cari)
            ->orLike('katnama', $cari)
            ->groupEnd();
    }
}

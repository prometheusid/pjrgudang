<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modelkategori;
use App\Models\Modelsatuan;

class Barang extends BaseController
{
    //load mmodel barang
    public function __construct()
    {
        $this->barang = new Modelbarang();
    }

    public function index()
    {
        //ambil data dari tabel barang dan tampilkan di view barang
        $data = [
            'tampildata' => $this->barang->tampildata()
        ];
        return view('barang/viewbarang', $data);
    }

    //function tambah data
    public function tambah()
    {
        //load isi kategori dan satuan
        $modelkategori = new Modelkategori();
        $modelsatuan = new Modelsatuan();
        //kirim data hasil load ke form select
        $data = [
            'datakategori' => $modelkategori->findAll(),
            'datasatuan' => $modelsatuan->findAll()
        ];
        return view('barang/formtambah', $data);
    }
}

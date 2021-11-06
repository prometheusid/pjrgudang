<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;

class Barang extends BaseController
{
    public function __construct()
    {
        $this->barang = new Modelbarang();
    }

    public function index()
    {
        $data = [
            'tampildata' => $this->barang->findAll()
        ];
        return view('barang/viewbarang', $data);
    }

    public function formtambah()
    {
        return view('barang/formtambah');
    }

    public function simpandata()
    {
        $namabarang = $this->request->getVar('namabarang');
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} Wajib di Isi !'
                ]
            ]
        ]);
        if (!$valid) {
            $pesan = [
                'errorNamaBarang' => '<br> <div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('barang/formtambah');
        } else {
            $this->barang->insert([
                'brgnama' => $namabarang
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">Data berhasil di tambahkan </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('barang/index');
        }
    }
}

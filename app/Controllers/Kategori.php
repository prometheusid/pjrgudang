<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelkategori;

class Kategori extends BaseController
{
    public function __construct()
    {
        $this->kategori = new Modelkategori();
    }

    public function index()
    {
        $data = [
            'tampildata' => $this->kategori->findAll()
        ];
        return view('kategori/viewkategori', $data);
    }

    public function formtambah()
    {
        return view('kategori/formtambah');
    }

    public function simpandata()
    {
        $namakategori = $this->request->getVar('namakategori');
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} Wajib di Isi !'
                ]
            ]
        ]);
        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<br> <div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('kategori/formtambah');
        } else {
            $this->kategori->insert([
                'katnama' => $namakategori
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">Data berhasil di tambahkan </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('kategori/index');
        }
    }
}

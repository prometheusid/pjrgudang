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

    public function formedit($id)
    {
        $rowData = $this->kategori->find($id);

        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['katnama']
            ];
            return view('kategori/formedit', $data);
        } else {
            exit('Data Tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idkategori = $this->request->getVar('idkategori');
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
            return redirect()->to('kategori/formedit/' . $idkategori);
        } else {
            $this->kategori->update($idkategori, [
                'katnama' => $namakategori
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Update
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('kategori/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->kategori->find($id);

        if ($rowData) {
            $this->kategori->delete($id);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Hapus
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('kategori/index');
        } else {
            exit('Data Tidak ditemukan');
        }
    }
}

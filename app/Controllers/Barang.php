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

    public function formedit($id)
    {
        $rowData = $this->barang->find($id);

        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['brgnama']
            ];
            return view('barang/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idbarang = $this->request->getVar('idbarang');
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
            return redirect()->to('barang/formedit' . $idbarang);
        } else {
            $this->barang->update($idbarang, [
                'brgnama' => $namabarang
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Update
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('barang/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->barang->find($id);

        if ($rowData) {
            $this->barang->delete($id);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Hapus
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('barang/index');
        } else {
            exit('Data Tidak ditemukan');
        }
    }
}

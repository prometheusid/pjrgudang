<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelsatuan;

class Satuan extends BaseController
{
    public function __construct()
    {
        $this->satuan = new Modelsatuan();
    }

    public function index()
    {
        //mengambil input dari searchbox
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_satuan', $cari);
            redirect()->to('/satuan/index');
        } else {
            $cari = session()->get('cari_satuan');
        }
        $dataSatuan = $cari ? $this->satuan->cariData($cari)->paginate(5, 'satuan') : $this->satuan->paginate(5, 'satuan');

        $nohalaman = $this->request->getVar('page_satuan') ? $this->request->getVar('page_satuan') : 1;
        $data = [
            'tampildata' => $dataSatuan,
            'pager' => $this->satuan->pager,
            'nohalaman' => $nohalaman,
            'cari' => $cari
        ];
        return view('satuan/viewsatuan', $data);
    }

    public function formtambah()
    {
        return view('satuan/formtambah');
    }

    public function simpandata()
    {
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama Satuan',
                'errors' => [
                    'required' => '{field} Wajib di Isi !'
                ]
            ]
        ]);
        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<br> <div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('satuan/formtambah');
        } else {
            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success">Data berhasil di tambahkan </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('satuan/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->satuan->find($id);

        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['satnama']
            ];
            return view('satuan/formedit', $data);
        } else {
            exit('Data Tidak ditemukan');
        }
    }

    public function updatedata()
    {
        $idsatuan = $this->request->getVar('idsatuan');
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama Satuan',
                'errors' => [
                    'required' => '{field} Wajib di Isi !'
                ]
            ]
        ]);
        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<br> <div class="alert alert-danger">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('satuan/formedit/' . $idsatuan);
        } else {
            $this->satuan->update($idsatuan, [
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Update
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('satuan/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->satuan->find($id);

        if ($rowData) {
            $this->satuan->delete($id);
            $pesan = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i>Berhasil !</h5> Data Berhasil di Hapus
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('satuan/index');
        } else {
            exit('Data Tidak ditemukan');
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\Modelsatuan;
use CodeIgniter\Exceptions\PageNotFoundException;

class Satuan extends BaseController
{
    private $satuan;

    public function __construct()
    {
        $this->satuan = new Modelsatuan();
    }

    public function index()
    {
        if ($this->request->getMethod() === 'post') {
            $cari = $this->request->getPost('cari');
            session()->set('cari_satuan', is_string($cari) ? trim($cari) : '');
            return redirect()->to(site_url('satuan/index'));
        }

        $cari = (string) session()->get('cari_satuan');
        $query = $cari === '' ? $this->satuan : $this->satuan->cariData($cari);
        $data = $query->paginate(5, 'satuan');

        return view('satuan/viewsatuan', [
            'tampildata' => $data,
            'pager' => $this->satuan->pager,
            'nohalaman' => $this->nomorHalaman($this->request->getGet('page_satuan')),
            'cari' => $cari,
        ]);
    }

    public function formtambah()
    {
        return view('satuan/formtambah');
    }

    public function simpandata()
    {
        $nama = $this->request->getPost('namasatuan');
        if ($nama !== null && ! is_string($nama)) {
            session()->setFlashdata('error', 'Nama satuan tidak valid.');
            return redirect()->to(site_url('satuan/formtambah'));
        }
        if (is_string($nama) && trim($nama) === '') {
            session()->setFlashdata('error', 'Nama satuan wajib diisi.');
            return redirect()->to(site_url('satuan/formtambah'))->withInput();
        }
        if (! $this->validate([
            'namasatuan' => 'required|max_length[50]',
        ])) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('satuan/formtambah'))->withInput();
        }

        $inserted = $this->satuan->insert([
            'satnama' => trim((string) $this->request->getPost('namasatuan')),
        ]);
        if ($inserted === false) {
            session()->setFlashdata('error', 'Satuan gagal disimpan.');
            return redirect()->to(site_url('satuan/formtambah'))->withInput();
        }
        session()->setFlashdata('sukses', 'Satuan berhasil ditambahkan.');
        return redirect()->to(site_url('satuan/index'));
    }

    public function formedit(int $id)
    {
        $row = $this->satuan->find($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound('Satuan tidak ditemukan.');
        }

        return view('satuan/formedit', [
            'id' => $id,
            'nama' => $row['satnama'],
        ]);
    }

    public function updatedata()
    {
        $idInput = $this->request->getPost('idsatuan');
        $id = is_scalar($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false;
        if ($id === false || ! $this->satuan->find($id)) {
            throw PageNotFoundException::forPageNotFound('Satuan tidak ditemukan.');
        }
        $nama = $this->request->getPost('namasatuan');
        if ($nama !== null && ! is_string($nama)) {
            session()->setFlashdata('error', 'Nama satuan tidak valid.');
            return redirect()->to(site_url('satuan/formedit/' . $id));
        }
        if (is_string($nama) && trim($nama) === '') {
            session()->setFlashdata('error', 'Nama satuan wajib diisi.');
            return redirect()->to(site_url('satuan/formedit/' . $id))->withInput();
        }
        if (! $this->validate(['namasatuan' => 'required|max_length[50]'])) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('satuan/formedit/' . $id))->withInput();
        }

        if (! $this->satuan->update($id, [
            'satnama' => trim((string) $this->request->getPost('namasatuan')),
        ])) {
            session()->setFlashdata('error', 'Satuan gagal diperbarui.');
            return redirect()->to(site_url('satuan/formedit/' . $id))->withInput();
        }
        session()->setFlashdata('sukses', 'Satuan berhasil diperbarui.');
        return redirect()->to(site_url('satuan/index'));
    }

    public function hapus(int $id)
    {
        if (! $this->satuan->find($id)) {
            session()->setFlashdata('error', 'Satuan tidak ditemukan.');
            return redirect()->to(site_url('satuan/index'));
        }
        if (db_connect()->table('barang')->where('brgsatid', $id)->countAllResults() > 0) {
            session()->setFlashdata('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh barang.');
            return redirect()->to(site_url('satuan/index'));
        }

        if (! $this->satuan->delete($id)) {
            session()->setFlashdata('error', 'Satuan gagal dihapus.');
            return redirect()->to(site_url('satuan/index'));
        }
        session()->setFlashdata('sukses', 'Satuan berhasil dihapus.');
        return redirect()->to(site_url('satuan/index'));
    }

    private function nomorHalaman($input): int
    {
        $halaman = is_scalar($input) ? filter_var($input, FILTER_VALIDATE_INT) : false;
        return max(1, $halaman === false ? 1 : $halaman);
    }
}

<?php

namespace App\Controllers;

use App\Models\Modelkategori;
use CodeIgniter\Exceptions\PageNotFoundException;

class Kategori extends BaseController
{
    private $kategori;

    public function __construct()
    {
        $this->kategori = new Modelkategori();
    }

    public function index()
    {
        if ($this->request->getMethod() === 'post') {
            $cari = $this->request->getPost('cari');
            session()->set('cari_kategori', is_string($cari) ? trim($cari) : '');
            return redirect()->to(site_url('kategori/index'));
        }

        $cari = (string) session()->get('cari_kategori');
        $query = $cari === '' ? $this->kategori : $this->kategori->cariData($cari);
        $data = $query->paginate(5, 'kategori');

        return view('kategori/viewkategori', [
            'tampildata' => $data,
            'pager' => $this->kategori->pager,
            'nohalaman' => $this->nomorHalaman($this->request->getGet('page_kategori')),
            'cari' => $cari,
        ]);
    }

    public function formtambah()
    {
        return view('kategori/formtambah');
    }

    public function simpandata()
    {
        $nama = $this->request->getPost('namakategori');
        if ($nama !== null && ! is_string($nama)) {
            session()->setFlashdata('error', 'Nama kategori tidak valid.');
            return redirect()->to(site_url('kategori/formtambah'));
        }
        if (is_string($nama) && trim($nama) === '') {
            session()->setFlashdata('error', 'Nama kategori wajib diisi.');
            return redirect()->to(site_url('kategori/formtambah'))->withInput();
        }
        if (! $this->validate([
            'namakategori' => 'required|max_length[50]',
        ])) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('kategori/formtambah'))->withInput();
        }

        $inserted = $this->kategori->insert([
            'katnama' => trim((string) $this->request->getPost('namakategori')),
        ]);
        if ($inserted === false) {
            session()->setFlashdata('error', 'Kategori gagal disimpan.');
            return redirect()->to(site_url('kategori/formtambah'))->withInput();
        }
        session()->setFlashdata('sukses', 'Kategori berhasil ditambahkan.');
        return redirect()->to(site_url('kategori/index'));
    }

    public function formedit(int $id)
    {
        $row = $this->kategori->find($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        return view('kategori/formedit', [
            'id' => $id,
            'nama' => $row['katnama'],
        ]);
    }

    public function updatedata()
    {
        $idInput = $this->request->getPost('idkategori');
        $id = is_scalar($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false;
        if ($id === false || ! $this->kategori->find($id)) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }
        $nama = $this->request->getPost('namakategori');
        if ($nama !== null && ! is_string($nama)) {
            session()->setFlashdata('error', 'Nama kategori tidak valid.');
            return redirect()->to(site_url('kategori/formedit/' . $id));
        }
        if (is_string($nama) && trim($nama) === '') {
            session()->setFlashdata('error', 'Nama kategori wajib diisi.');
            return redirect()->to(site_url('kategori/formedit/' . $id))->withInput();
        }
        if (! $this->validate(['namakategori' => 'required|max_length[50]'])) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('kategori/formedit/' . $id))->withInput();
        }

        if (! $this->kategori->update($id, [
            'katnama' => trim((string) $this->request->getPost('namakategori')),
        ])) {
            session()->setFlashdata('error', 'Kategori gagal diperbarui.');
            return redirect()->to(site_url('kategori/formedit/' . $id))->withInput();
        }
        session()->setFlashdata('sukses', 'Kategori berhasil diperbarui.');
        return redirect()->to(site_url('kategori/index'));
    }

    public function hapus(int $id)
    {
        if (! $this->kategori->find($id)) {
            session()->setFlashdata('error', 'Kategori tidak ditemukan.');
            return redirect()->to(site_url('kategori/index'));
        }
        if (db_connect()->table('barang')->where('brgkatid', $id)->countAllResults() > 0) {
            session()->setFlashdata('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang.');
            return redirect()->to(site_url('kategori/index'));
        }

        if (! $this->kategori->delete($id)) {
            session()->setFlashdata('error', 'Kategori gagal dihapus.');
            return redirect()->to(site_url('kategori/index'));
        }
        session()->setFlashdata('sukses', 'Kategori berhasil dihapus.');
        return redirect()->to(site_url('kategori/index'));
    }

    private function nomorHalaman($input): int
    {
        $halaman = is_scalar($input) ? filter_var($input, FILTER_VALIDATE_INT) : false;
        return max(1, $halaman === false ? 1 : $halaman);
    }
}

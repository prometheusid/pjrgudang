<?php

namespace App\Controllers;

use App\Models\Modelbarang;
use App\Models\Modelkategori;
use App\Models\Modelsatuan;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class Barang extends BaseController
{
    private $barang;

    public function __construct()
    {
        $this->barang = new Modelbarang();
    }

    public function index()
    {
        if ($this->request->getMethod() === 'post') {
            $inputCari = $this->request->getPost('cari');
            $cari = is_string($inputCari) ? trim($inputCari) : '';
            session()->set('cari_barang', $cari);
            return redirect()->to(site_url('barang/index'));
        }

        $cari = (string) session()->get('cari_barang');
        $countModel = new Modelbarang();
        $totalData = $cari === ''
            ? $countModel->tampildata()->countAllResults()
            : $countModel->tampildata_cari($cari)->countAllResults();
        $dataBarang = $cari === ''
            ? $this->barang->tampildata()->paginate(5, 'barang')
            : $this->barang->tampildata_cari($cari)->paginate(5, 'barang');

        return view('barang/viewbarang', [
            'tampildata' => $dataBarang,
            'pager' => $this->barang->pager,
            'nohalaman' => $this->nomorHalaman($this->request->getGet('page_barang')),
            'totaldata' => $totalData,
            'cari' => $cari,
        ]);
    }

    public function tambah()
    {
        return view('barang/formtambah', $this->dataPilihan());
    }

    public function simpandata()
    {
        $file = $this->request->getFile('gambar');
        if (! $this->validasiBarang(true, $file)) {
            return redirect()->to(site_url('barang/tambah'))->withInput();
        }
        $nama = trim((string) $this->request->getPost('namabarang'));
        if ($nama === '') {
            session()->setFlashdata('error', 'Nama barang wajib diisi.');
            return redirect()->to(site_url('barang/tambah'))->withInput();
        }

        $kode = trim((string) $this->request->getPost('kodebarang'));
        $gambar = $this->simpanGambar($file);
        if ($gambar === null) {
            return redirect()->to(site_url('barang/tambah'))->withInput();
        }
        $saved = $this->barang->insert([
            'brgkode' => $kode,
            'brgnama' => $nama,
            'brgkatid' => $this->request->getPost('kategori'),
            'brgsatid' => $this->request->getPost('satuan'),
            'brgharga' => $this->request->getPost('harga'),
            'brgstok' => $this->request->getPost('stok'),
            'brggambar' => $gambar,
        ]);

        if ($saved === false) {
            if ($gambar !== '') {
                $this->hapusGambar($gambar);
            }
            session()->setFlashdata('error', 'Data barang gagal disimpan.');
            return redirect()->to(site_url('barang/tambah'))->withInput();
        }

        session()->setFlashdata('sukses', 'Data barang berhasil disimpan.');
        return redirect()->to(site_url('barang/index'));
    }

    public function edit(string $kode)
    {
        $barang = $this->barang->find($kode);
        if (! $barang) {
            throw PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        return view('barang/formedit', array_merge($this->dataPilihan(), [
            'kodebarang' => $barang['brgkode'],
            'namabarang' => $barang['brgnama'],
            'kategori' => $barang['brgkatid'],
            'satuan' => $barang['brgsatid'],
            'harga' => $barang['brgharga'],
            'stok' => $barang['brgstok'],
            'gambar' => $barang['brggambar'] ?? '',
        ]));
    }

    public function updatedata()
    {
        $kodeInput = $this->request->getPost('kodebarang');
        if (! is_string($kodeInput)) {
            session()->setFlashdata('error', 'Kode barang tidak valid.');
            return redirect()->to(site_url('barang/index'));
        }
        $kode = trim($kodeInput);
        $barang = $this->barang->find($kode);
        if (! $barang) {
            throw PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $file = $this->request->getFile('gambar');
        if (! $this->validasiBarang(false, $file)) {
            return redirect()->to(site_url('barang/edit/' . rawurlencode($kode)))->withInput();
        }
        $nama = trim((string) $this->request->getPost('namabarang'));
        if ($nama === '') {
            session()->setFlashdata('error', 'Nama barang wajib diisi.');
            return redirect()->to(site_url('barang/edit/' . rawurlencode($kode)))->withInput();
        }

        $gambarBaru = $this->simpanGambar($file);
        if ($gambarBaru === null) {
            return redirect()->to(site_url('barang/edit/' . rawurlencode($kode)))->withInput();
        }
        $gambarLama = (string) ($barang['brggambar'] ?? '');
        $gambar = $gambarBaru !== '' ? $gambarBaru : $gambarLama;
        $updated = $this->barang->update($kode, [
            'brgnama' => $nama,
            'brgkatid' => $this->request->getPost('kategori'),
            'brgsatid' => $this->request->getPost('satuan'),
            'brgharga' => $this->request->getPost('harga'),
            'brggambar' => $gambar,
        ]);

        if ($updated === false) {
            if ($gambarBaru !== '') {
                $this->hapusGambar($gambarBaru);
            }
            session()->setFlashdata('error', 'Data barang gagal diperbarui.');
            return redirect()->to(site_url('barang/edit/' . rawurlencode($kode)))->withInput();
        }

        if ($gambarBaru !== '' && $gambarLama !== '') {
            $this->hapusGambar($gambarLama);
        }

        session()->setFlashdata('sukses', 'Data barang berhasil diperbarui.');
        return redirect()->to(site_url('barang/index'));
    }

    public function hapus(string $kode)
    {
        $barang = $this->barang->find($kode);
        if (! $barang) {
            session()->setFlashdata('error', 'Barang tidak ditemukan.');
            return redirect()->to(site_url('barang/index'));
        }
        if (db_connect()->table('transaksi_detail')->where('barang_kode', $kode)->countAllResults() > 0) {
            session()->setFlashdata('error', 'Barang tidak dapat dihapus karena sudah tercatat dalam transaksi.');
            return redirect()->to(site_url('barang/index'));
        }

        if (! $this->barang->delete($kode)) {
            session()->setFlashdata('error', 'Barang gagal dihapus karena masih digunakan atau terjadi kesalahan.');
            return redirect()->to(site_url('barang/index'));
        }

        if (! empty($barang['brggambar'])) {
            $this->hapusGambar($barang['brggambar']);
        }

        session()->setFlashdata('sukses', 'Data barang berhasil dihapus.');
        return redirect()->to(site_url('barang/index'));
    }

    public function gambar(string $kode)
    {
        $barang = $this->barang->find($kode);
        if (! $barang || empty($barang['brggambar'])) {
            throw PageNotFoundException::forPageNotFound('Gambar tidak ditemukan.');
        }

        $path = $this->pathGambar($barang['brggambar']);
        if ($path === null) {
            throw PageNotFoundException::forPageNotFound('Gambar tidak ditemukan.');
        }

        $mime = mime_content_type($path);
        if (! in_array($mime, ['image/jpeg', 'image/png'], true)) {
            throw PageNotFoundException::forPageNotFound('Format gambar tidak valid.');
        }

        return $this->response
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setContentType($mime)
            ->setBody(file_get_contents($path));
    }

    private function dataPilihan(): array
    {
        return [
            'datakategori' => (new Modelkategori())->findAll(),
            'datasatuan' => (new Modelsatuan())->findAll(),
        ];
    }

    private function nomorHalaman($input): int
    {
        $halaman = is_scalar($input) ? filter_var($input, FILTER_VALIDATE_INT) : false;
        return max(1, $halaman === false ? 1 : $halaman);
    }

    private function validasiBarang(bool $perluKode, ?UploadedFile $file): bool
    {
        foreach (['namabarang', 'kategori', 'satuan', 'harga', 'stok'] as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null && ! is_scalar($value)) {
                session()->setFlashdata('error', 'Data yang dikirim tidak valid.');
                return false;
            }
        }
        if ($perluKode) {
            $value = $this->request->getPost('kodebarang');
            if ($value !== null && ! is_string($value)) {
                session()->setFlashdata('error', 'Kode barang tidak valid.');
                return false;
            }
        }

        $rules = [
            'namabarang' => 'required|max_length[100]',
            'kategori' => 'required|is_not_unique[kategori.katid]',
            'satuan' => 'required|is_not_unique[satuan.satid]',
            'harga' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[9999999999.99]',
        ];
        if ($perluKode) {
            $rules['kodebarang'] = 'required|alpha_numeric|max_length[10]|is_unique[barang.brgkode]';
            $rules['stok'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[4294967295]';
        }
        if ($file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            if (! $file->isValid()) {
                session()->setFlashdata('error', 'File gambar gagal diunggah.');
                return false;
            }
            $rules['gambar'] = 'mime_in[gambar,image/png,image/jpeg]|ext_in[gambar,png,jpg,jpeg]|max_size[gambar,2048]';
        }

        if ($this->validate($rules)) {
            return true;
        }

        session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
        return false;
    }

    private function simpanGambar(?UploadedFile $file): ?string
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'barang';
        if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
            throw new \RuntimeException('Direktori penyimpanan gambar tidak dapat dibuat.');
        }

        $extension = $file->guessExtension();
        if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            session()->setFlashdata('error', 'Format gambar harus JPG atau PNG.');
            return null;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $file->move($directory, $filename, true);

        return $filename;
    }

    private function hapusGambar(string $filename): void
    {
        $path = $this->pathGambar($filename);
        if ($path === null) {
            return;
        }
        if (is_file($path) && ! unlink($path)) {
            log_message('error', 'Gagal menghapus file gambar barang: {file}', ['file' => $path]);
        }
    }

    private function pathGambar(string $storedPath): ?string
    {
        $filename = basename($storedPath);
        $uploadPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'barang' . DIRECTORY_SEPARATOR . $filename;
        if (is_file($uploadPath)) {
            return $uploadPath;
        }

        if (strpos($storedPath, 'assets/img/') === 0) {
            $legacyPath = ROOTPATH . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $filename;
            if (is_file($legacyPath)) {
                return $legacyPath;
            }
        }

        return null;
    }
}

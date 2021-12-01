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
        // mengambil input dari searchbox
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_barang', $cari);
            redirect()->to('/barang/index');
        } else {
            $cari = session()->get('cari_barang');
        }

        //menampilkan jumlah data ditemukan
        $totaldata =
            $cari ? $this->barang->tampildata_cari($cari)->countAllResults() : $this->barang->tampildata()->countAllResults();

        $dataBarang =
            $cari ? $this->barang->tampildata_cari($cari)->paginate(5, 'barang') : $this->barang->tampildata()->paginate(5, 'barang');

        $nohalaman = $this->request->getVar('page_barang') ? $this->request->getVar('page_barang') : 1;

        //ambil data dari tabel barang dan tampilkan di view barang
        $data = [
            'tampildata' => $dataBarang,
            'pager' => $this->barang->pager,
            'nohalaman' => $nohalaman,
            'totaldata' => $totaldata,
            'cari' => $cari
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

    //function simpan data dari form input ke database
    public function simpandata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        //validasi data input
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'kodebarang' => [
                'rules' => 'required|is_unique[barang.brgkode]',
                'label' => 'Kode Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'is_unique' => '{field} sudah ada.'
                ]
            ],
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'numeric' => '{field} hanya boleh angka'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'numeric' => '{field} hanya boleh angka'
                ]
            ],
            'gambar' => [
                'rules' => 'mime_in[gambar,image/png,image/jpg,image/jpeg]|ext_in[gambar,png,jpg,gif,jpeg]|max_size[gambar,2048]',
                'label' => 'Gambar'
            ]
        ]);
        if (!$valid) {
            $sess_Pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                ' . $validation->listErrors() . '
                </div>'
            ];
            session()->setFlashdata($sess_Pesan);
            return redirect()->to('/barang/tambah');
        } else {
            //cek ada input gambar atau tidak
            $gambar = $_FILES['gambar']['name'];

            if ($gambar != NULL) {
                //set nama file gambar
                $namaFileGambar = $kodebarang;
                $fileGambar = $this->request->getFile('gambar');
                //pindahkan gambar ke folder assets/upload
                $fileGambar->move('assets/img', $namaFileGambar . '.' . $fileGambar->getExtension());
                $pathGambar = 'assets/img/' . $fileGambar->getName();
            } else {
                $pathGambar = '';
            }
            //insert semua input ke database
            $this->barang->insert([
                'brgkode' => $kodebarang,
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathGambar
            ]);

            //notif upload berhasil
            $pesan_sukses = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                Data barang dengan kode <strong>' . $kodebarang . '</strong> Berhasil disimpan
                </div>'
            ];

            session()->setFlashdata($pesan_sukses);
            return redirect()->to('/barang/tambah');
        }
    }

    //fungsi edit barang
    public function edit($kode)
    {
        $cekData = $this->barang->find($kode);
        if ($cekData) {
            //dropdown option for Satuan & Kategori
            $modelkategori = new Modelkategori();
            $modelsatuan = new Modelsatuan();

            $data = [
                'kodebarang' => $cekData['brgkode'],
                'namabarang' => $cekData['brgnama'],
                'kategori' => $cekData['brgkatid'],
                'satuan' => $cekData['brgsatid'],
                'harga' => $cekData['brgharga'],
                'stok' => $cekData['brgstok'],
                'gambar' => $cekData['brggambar'],
                'datakategori' => $modelkategori->findAll(),
                'datasatuan' => $modelsatuan->findAll(),
            ];
            return view('barang/formedit', $data);
        } else {
            $pesan_error = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-ban"></i> Error !</h5>
                Data Barang Tidak Ditemukan !
                </div>'
            ];

            session()->setFlashdata($pesan_error);
            return redirect()->to('/barang/index');
        }
    }

    //fungsi update/edit barang
    public function updatedata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        //validasi data input
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'numeric' => '{field} hanya boleh angka'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong.',
                    'numeric' => '{field} hanya boleh angka'
                ]
            ],
            'gambar' => [
                'rules' => 'mime_in[gambar,image/png,image/jpg,image/jpeg]|ext_in[gambar,png,jpg,gif,jpeg]|max_size[gambar,2048]',
                'label' => 'Gambar'
            ]
        ]);
        if (!$valid) {
            $sess_Pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                ' . $validation->listErrors() . '
                </div>'
            ];
            session()->setFlashdata($sess_Pesan);
            return redirect()->to('/barang/tambah');
        } else {
            //cek sebelum edit ada gambar atau tidak jika ada gambar lama akan dihapus
            $cekData = $this->barang->find($kodebarang);
            $pathGambarLama = $cekData['brggambar'];
            //cek ada input gambar atau tidak
            $gambar = $_FILES['gambar']['name'];

            if ($gambar != NULL) {
                //hapus gambar lama jika ada
                ($pathGambarLama == '' || $pathGambarLama == null) ? '' :
                    unlink($pathGambarLama);

                //set nama file gambar
                $namaFileGambar = $kodebarang;
                $fileGambar = $this->request->getFile('gambar');
                //pindahkan gambar ke folder assets/upload
                $fileGambar->move('assets/img', $namaFileGambar . '.' . $fileGambar->getExtension());
                $pathGambar = 'assets/img/' . $fileGambar->getName();
            } else {
                $pathGambar = $pathGambarLama;
            }
            //insert semua input ke database
            $this->barang->update($kodebarang, [
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathGambar
            ]);

            //notif upload berhasil
            $pesan_sukses = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                Data barang dengan kode <strong>' . $kodebarang . '</strong> Berhasil diupdate
                </div>'
            ];

            session()->setFlashdata($pesan_sukses);
            return redirect()->to('/barang/index');
        }
    }

    //fungsi hapus data
    public function hapus($kode)
    {
        $cekData = $this->barang->find($kode);
        if ($cekData) {
            //hapus file gambar di direktori
            $pathGambarLama = $cekData['brggambar'];
            ($pathGambarLama == '' || $pathGambarLama == null) ? '' :
                unlink($pathGambarLama);
            $this->barang->delete($kode);
            $pesan_sukses = [
                'sukses' => '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                Data barang dengan kode <strong>' . $kode . '</strong> Berhasil dihapus
                </div>'
            ];
            session()->setFlashdata($pesan_sukses);
            return redirect()->to('/barang/index');
        } else {
            $pesan_error = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h5><i class="icon fas fa-ban"></i> Error !</h5>
                Data Barang Tidak Ditemukan !
                </div>'
            ];
            session()->setFlashdata($pesan_error);
            return redirect()->to('/barang/index');
        }
    }
}

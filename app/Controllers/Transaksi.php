<?php

namespace App\Controllers;

use App\Models\Modelbarang;
use App\Models\Modeltransaksi;

class Transaksi extends BaseController
{
    public function index()
    {
        $history = db_connect()
            ->table('transaksi')
            ->orderBy('transaksi_tanggal', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        return view('transaksi/viewtransaksi', [
            'barang' => (new Modelbarang())->orderBy('brgnama')->findAll(),
            'history' => $history,
        ]);
    }

    public function simpan()
    {
        $jenis = $this->request->getPost('jenis');
        $kodeBarang = $this->request->getPost('barang');
        $jumlah = $this->request->getPost('jumlah');
        $customerInput = $this->request->getPost('customer');
        if (! is_string($customerInput)) {
            return $this->transaksiGagal('Nama customer tidak valid.');
        }
        $customer = trim($customerInput);

        if (! in_array($jenis, ['masuk', 'keluar'], true)) {
            return $this->transaksiGagal('Jenis transaksi tidak valid.');
        }
        if (! is_array($kodeBarang) || ! is_array($jumlah) || count($kodeBarang) !== count($jumlah)) {
            return $this->transaksiGagal('Pilih setidaknya satu barang dan jumlah yang valid.');
        }
        if (count($kodeBarang) > 100) {
            return $this->transaksiGagal('Satu transaksi maksimal berisi 100 jenis barang.');
        }

        $items = [];
        foreach ($kodeBarang as $index => $kode) {
            $jumlahInput = $jumlah[$index] ?? null;
            if (! is_scalar($jumlahInput)) {
                return $this->transaksiGagal('Jumlah barang tidak valid.');
            }
            $kuantitas = filter_var($jumlahInput, FILTER_VALIDATE_INT);
            if ($kode === '' && ($kuantitas === false || $kuantitas === 0)) {
                continue;
            }
            if (! is_string($kode) || $kode === '' || $kuantitas === false || $kuantitas < 1 || $kuantitas > 4294967295) {
                return $this->transaksiGagal('Setiap barang harus memiliki jumlah bilangan bulat lebih dari nol.');
            }

            if (isset($items[$kode])) {
                $items[$kode] += $kuantitas;
                if ($items[$kode] > 4294967295) {
                    return $this->transaksiGagal('Jumlah total barang melebihi batas yang didukung.');
                }
            } else {
                $items[$kode] = $kuantitas;
            }
        }

        if ($items === []) {
            return $this->transaksiGagal('Tambahkan setidaknya satu barang ke transaksi.');
        }
        if (mb_strlen($customer) > 100) {
            return $this->transaksiGagal('Nama customer maksimal 100 karakter.');
        }

        $db = db_connect();
        $barangModel = new Modelbarang();
        if (! $db->transBegin()) {
            return $this->transaksiGagal('Database tidak dapat memulai transaksi.');
        }

        $totalCents = 0;
        $detail = [];
        foreach ($items as $kode => $kuantitas) {
            $barang = $barangModel->find($kode);
            if (! $barang) {
                $db->transRollback();
                return $this->transaksiGagal('Barang yang dipilih tidak ditemukan.');
            }

            if ($jenis === 'keluar' && (int) $barang['brgstok'] < $kuantitas) {
                $db->transRollback();
                return $this->transaksiGagal('Stok ' . $barang['brgnama'] . ' tidak mencukupi.');
            }

            $hargaCents = (int) round((float) $barang['brgharga'] * 100);
            $maxTotalCents = 99999999999999;
            if ($hargaCents < 0) {
                $db->transRollback();
                return $this->transaksiGagal('Harga barang tidak valid.');
            }
            if ($hargaCents > 0 && $kuantitas > intdiv($maxTotalCents, $hargaCents)) {
                $db->transRollback();
                return $this->transaksiGagal('Nilai transaksi melebihi batas yang didukung.');
            }
            $subtotalCents = $hargaCents * $kuantitas;
            if ($subtotalCents > $maxTotalCents - $totalCents) {
                $db->transRollback();
                return $this->transaksiGagal('Nilai transaksi melebihi batas yang didukung.');
            }
            $totalCents += $subtotalCents;
            $detail[] = [
                'kode' => $kode,
                'jumlah' => $kuantitas,
                'harga' => $this->formatRupiah($hargaCents),
                'subtotal' => $this->formatRupiah($subtotalCents),
            ];
        }

        $transaksiModel = new Modeltransaksi();
        $transaksiId = $transaksiModel->insert([
            'transaksi_jenis' => $jenis,
            'transaksi_customer' => $customer === '' ? null : $customer,
            'transaksi_total' => $this->formatRupiah($totalCents),
            'transaksi_tanggal' => date('Y-m-d H:i:s'),
        ], true);
        if ($transaksiId === false) {
            $db->transRollback();
            return $this->transaksiGagal('Transaksi gagal disimpan.');
        }

        foreach ($detail as $item) {
            $builder = $db->table('barang');
            $builder->set('brgstok', $jenis === 'masuk' ? 'brgstok+' . $item['jumlah'] : 'brgstok-' . $item['jumlah'], false);
            $builder->where('brgkode', $item['kode']);
            if ($jenis === 'keluar') {
                $builder->where('brgstok >=', $item['jumlah']);
            } else {
                $builder->where('brgstok <=', 4294967295 - $item['jumlah']);
            }

            if (! $builder->update() || $db->affectedRows() !== 1) {
                $db->transRollback();
                return $this->transaksiGagal('Stok berubah atau tidak mencukupi. Transaksi dibatalkan; silakan coba lagi.');
            }

            if (! $db->table('transaksi_detail')->insert([
                'transaksi_id' => $transaksiId,
                'barang_kode' => $item['kode'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['subtotal'],
            ])) {
                $db->transRollback();
                return $this->transaksiGagal('Transaksi gagal disimpan.');
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return $this->transaksiGagal('Transaksi gagal disimpan.');
        }
        if (! $db->transCommit()) {
            $db->transRollback();
            return $this->transaksiGagal('Transaksi gagal dikonfirmasi oleh database.');
        }

        session()->setFlashdata('sukses', 'Transaksi ' . $jenis . ' berhasil disimpan.');
        return redirect()->to(site_url('transaksi/index'));
    }

    private function transaksiGagal(string $pesan)
    {
        session()->setFlashdata('error', $pesan);
        return redirect()->to(site_url('transaksi/index'))->withInput();
    }

    private function formatRupiah(int $sen): string
    {
        return intdiv($sen, 100) . '.' . str_pad((string) ($sen % 100), 2, '0', STR_PAD_LEFT);
    }
}

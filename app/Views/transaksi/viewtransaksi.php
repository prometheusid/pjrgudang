<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Transaksi Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('/') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('sukses')) : ?>
    <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('sukses')) ?></div>
<?php endif; ?>

<?= form_open('transaksi/simpan') ?>
    <div class="form-group row">
        <label for="customer" class="col-sm-2 col-form-label">Nama Customer</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="customer" name="customer" maxlength="100">
        </div>
    </div>
    <div class="form-group row">
        <label for="jenis" class="col-sm-2 col-form-label">Jenis Transaksi</label>
        <div class="col-sm-3">
            <select class="form-control" id="jenis" name="jenis" required>
                <option value="masuk">Barang Masuk</option>
                <option value="keluar">Barang Keluar</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-sm-7">
            <label for="barang-0">Barang</label>
            <select class="form-control" id="barang-0" name="barang[]" required>
                <option value="">Pilih barang</option>
                <?php foreach ($barang as $item) : ?>
                    <option value="<?= esc($item['brgkode'], 'attr') ?>"><?= esc($item['brgnama']) ?> (stok: <?= esc($item['brgstok']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label for="jumlah-0">Jumlah</label>
            <input type="number" class="form-control" id="jumlah-0" name="jumlah[]" min="1" step="1" required>
        </div>
        <div class="form-group col-sm-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-primary" id="tambah-barang">Tambah barang</button>
        </div>
    </div>
    <div id="baris-barang"></div>
    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
<?= form_close() ?>

<template id="template-barang">
    <div class="form-row item-barang">
        <div class="form-group col-sm-7">
            <label>Barang</label>
            <select class="form-control" name="barang[]">
                <option value="">Pilih barang</option>
                <?php foreach ($barang as $item) : ?>
                    <option value="<?= esc($item['brgkode'], 'attr') ?>"><?= esc($item['brgnama']) ?> (stok: <?= esc($item['brgstok']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Jumlah</label>
            <input type="number" class="form-control" name="jumlah[]" min="1" step="1">
        </div>
        <div class="form-group col-sm-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger hapus-barang">Hapus</button>
        </div>
    </div>
</template>

<script>
    document.getElementById('tambah-barang').addEventListener('click', function () {
        const row = document.getElementById('template-barang').content.cloneNode(true);
        row.querySelector('select').required = true;
        row.querySelector('input').required = true;
        row.querySelector('.hapus-barang').addEventListener('click', function () {
            this.closest('.item-barang').remove();
        });
        document.getElementById('baris-barang').appendChild(row);
    });
</script>
<hr>
<h4>Transaksi Terbaru</h4>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Customer</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $row) : ?>
            <tr>
                <td><?= esc($row['transaksi_tanggal']) ?></td>
                <td><?= esc(ucfirst($row['transaksi_jenis'])) ?></td>
                <td><?= esc($row['transaksi_customer'] ?? '-') ?></td>
                <td><?= esc(number_format((float) $row['transaksi_total'], 2, ',', '.')) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($history === []) : ?>
            <tr><td colspan="4">Belum ada transaksi.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection('isi'); ?>
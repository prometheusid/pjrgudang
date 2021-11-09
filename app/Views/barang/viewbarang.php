<?= $this->extend('main/layout'); ?>

<!-- Judul Barang -->
<?= $this->section('judul'); ?>
Management Data Barang
<?= $this->endSection('judul'); ?>

<!-- Sub Judul Barang -->
<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-secondary',
    'onclick' => "location.href=('" . site_url('barang/tambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<!-- Isi Barang -->
<?= $this->section('isi'); ?>
<table class="table-striped table-bordered" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Stok</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->getResultArray() as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['brgkode']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['katnama']; ?></td>
                <td><?= $row['satnama']; ?></td>
                <td><?= number_format($row['brgharga'], 0); ?></td>
                <td><?= number_format($row['brgstok'], 0); ?></td>
                <td>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection('isi'); ?>
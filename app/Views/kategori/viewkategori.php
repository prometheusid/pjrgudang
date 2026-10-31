<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Management Data Kategori
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-secondary',
    'onclick' => "location.href=('" . site_url('kategori/formtambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>
<?= $this->section('isi'); ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('sukses')) : ?>
    <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('sukses')) ?></div>
<?php endif; ?>

<!-- searchbox -->
<?= form_open('kategori/index') ?>
<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Cari Data Kategori" aria-label="Recipient's username" aria-describedby="button-addon2" name="cari" value="<?= esc($cari, 'attr') ?>">
    <div class="input-group-append">
        <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>
<?= form_close(); ?>
<!-- end seachbox -->

<table class="table-striped table-bordered" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Kategori</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= esc($row['katnama']) ?></td>
                <td>
                    <a class="btn btn-sm btn-info" title="Edit Data" href="<?= esc(site_url('kategori/formedit/' . $row['katid']), 'attr') ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="<?= esc(site_url('kategori/hapus/' . $row['katid']), 'attr') ?>" style="display:inline;" onsubmit="return confirm('Yakin Data Kategori di Hapus ?');">
                        <?= csrf_field() ?>
                        <input type="hidden" value="DELETE" name="_method">
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- pagination -->
<div class="float-left mt-1">
    <?= $pager->links('kategori', 'paging'); ?>
</div>
<!-- end pagination -->

<?= $this->endSection('isi'); ?>
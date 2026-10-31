<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Kategori
<?= $this->endSection('judul'); ?>


<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('kategori/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>


<?= $this->section('isi'); ?>
<?= form_open('kategori/updatedata', '', [
    'idkategori' => $id
]) ?>
<div class="form-group">
    <label for="namakategori">Nama Kategori</label>
    <?= form_input('namakategori', old('namakategori', $nama), [
        'class' => 'form-control',
        'id' => 'namakategori',
        'autofocus' => true,
    ]) ?>
</div>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<div class="form-group">
    <?= form_submit('', 'Update', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>
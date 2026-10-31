<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Satuan
<?= $this->endSection('judul'); ?>


<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('satuan/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>


<?= $this->section('isi'); ?>
<?= form_open('satuan/updatedata', '', [
    'idsatuan' => $id
]) ?>
<div class="form-group">
    <label for="namasatuan">Nama Satuan</label>
    <?= form_input('namasatuan', old('namasatuan', $nama), [
        'class' => 'form-control',
        'id' => 'namasatuan',
        'autofocus' => true,
    ]) ?>
</div>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<div class="form-group">
    <?= form_submit('', 'Simpan', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>
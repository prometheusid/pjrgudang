<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Barang
<?= $this->endSection('judul'); ?>


<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>


<?= $this->section('isi'); ?>
<?= form_open('barang/simpandata') ?>
<div class="form-group">
    <label for="namabarang">Nama Barang</label>
    <?= form_input('namabarang', '', [
        'class' => 'form-control',
        'id' => 'namabarang',
        'autofocus' => true,
        'placeholder' => 'Isikan Nama Barang'
    ]) ?>
    <?= session()->getFlashdata('errorNamaBarang'); ?>
</div>
<div class="form-group">
    <?= form_submit('', 'Simpan', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>
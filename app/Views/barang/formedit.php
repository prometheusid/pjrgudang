<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Barang
<?= $this->endSection('judul'); ?>


<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>


<?= $this->section('isi'); ?>
<?= form_open('barang/updatedata', '', [
    'idbarang' => $id
]) ?>
<div class="form-group">
    <label for="namabarang">Nama Barang</label>
    <?= form_input('namabarang', $nama, [
        'class' => 'form-control',
        'id' => 'namabarang',
        'autofocus' => true,
    ]) ?>
    <?= session()->getFlashdata('errorNamaBarang'); ?>
</div>
<div class="form-group">
    <?= form_submit('', 'Update', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>
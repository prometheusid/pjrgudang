<?= $this->extend('main/layout'); ?>

<!-- Judul Barang -->
<?= $this->section('judul'); ?>
Form Edit Barang
<?= $this->endSection('judul'); ?>

<!-- Sub Judul Barang -->
<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('/barang/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<!-- Isi Barang -->
<?= $this->section('isi'); ?>

<!-- Form input data barang -->
<?= form_open_multipart('barang/updatedata'); ?>

<!-- Alert Jika Terjadi Error (flashdata) -->
<?= session()->getFlashdata('error'); ?>
<!-- Alert jika input berhasil -->
<?= session()->getFlashdata('sukses'); ?>

<div class="form-group row">
    <label for="kodebarang" class="col-sm-3 col-form-label">Kode Barang</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="kodebarang" name="kodebarang" readonly value="<?= $kodebarang; ?>">
    </div>
</div>
<div class="form-group row">
    <label for="namabarang" class="col-sm-3 col-form-label">Nama Barang</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="namabarang" name="namabarang" value="<?= $namabarang; ?>">
    </div>
</div>
<div class="form-group row">
    <label for="kategori" class="col-sm-3 col-form-label">Kategori</label>
    <div class="col-sm-4">
        <select class="form-control" name="kategori" id="kategori">
            <?php foreach ($datakategori as $kat) : ?>
                <?php if ($kat['katid'] == $kategori) : ?>
                    <option selected value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
                <?php else : ?>
                    <option value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
    <div class="col-sm-4">
        <select class="form-control" name="satuan" id="satuan">
            <?php foreach ($datasatuan as $sat) : ?>
                <?php if ($sat['satid'] == $satuan) : ?>
                    <option selected value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
                <?php else : ?>
                    <option value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="harga" class="col-sm-3 col-form-label">Harga</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="harga" name="harga" value="<?= $harga; ?>">
    </div>
</div>
<div class="form-group row">
    <label for="stok" class="col-sm-3 col-form-label">Stok</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="stok" name="stok" value="<?= $stok; ?>">
    </div>
</div>
<div class="form-group row">
    <label for="gambar" class="col-sm-3 col-form-label">Gambar</label>
    <div class="col-sm-4">
        <img src="<?= base_url() . '/' . $gambar ?>" class="img-thumbnail" style="width: 50%;" alt="Gambar Barang">
    </div>
</div>
<div class="form-group row">
    <label for="gambar" class="col-sm-3 col-form-label">Upload Gambar Baru (<i> jika ada </i>)</label>
    <div class="col-sm-4">
        <input type="file" id="gambar" name="gambar">
    </div>
</div>
<div class="form-group row">
    <label for="simpan" class="col-sm-3 col-form-label"></label>
    <div class="col-sm-4">
        <input type="submit" value="simpan" class="btn btn-success">
    </div>
</div>
<?= form_close(); ?>

<?= $this->endSection('isi'); ?>
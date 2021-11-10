<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Transaction End Point
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('/../') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<form>
    <div class="form-group row">
        <label for="namacustomer" class="col-sm-2 col-form-label">Nama Customer</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="namacustomer" name="namacustomer">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-sm-1">
            <label for="no">No</label>
            <input type="number" class="form-control" id="no" name="no">
            <input type="number" class="form-control" id="no" name="no">
            <input type="number" class="form-control" id="no" name="no">
        </div>
        <div class="form-group col-sm-7">
            <label for="namaitem">Nama Item</label>
            <input type="text" class="form-control" id="namaitem1" name="namaitem1">
            <input type="text" class="form-control" id="namaitem2" name="namaitem2">
            <input type="text" class="form-control" id="namaitem3" name="namaitem3">
        </div>
        <div class="form-group col-sm-1">
            <label for="totalitem">Total Item</label>
            <input type="number" class="form-control" id="totalitem1" name="totalitem1">
            <input type="number" class="form-control" id="totalitem2" name="totalitem2">
            <input type="number" class="form-control" id="totalitem3" name="totalitem3">
        </div>
        <div class="form-group col-sm-3">
            <label for="harga">Harga</label>
            <input type="number" class="form-control" id="harga1" name="harga1">
            <input type="number" class="form-control" id="harga2" name="harga2">
            <input type="number" class="form-control" id="harga3" name="harga3">
        </div>
    </div>

    <!-- total harga pembayaran kembalian -->
    <div class="">
        <div class="form-group row">
            <label for="hargatotal" class="col-sm-9 col-form-label text-right">Total Harga</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="hargatotal" name="hargatotal">
            </div>
        </div>
        <div class="form-group row">
            <label for="bayar" class="col-sm-9 col-form-label text-right">Bayar</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="bayar" name="bayar">
            </div>
        </div>
        <div class="form-group row">
            <label for="kembalian" class="col-sm-9 col-form-label text-right">Kembalian</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="kembalian" name="kembalian">
            </div>
        </div>
    </div>
</form>


<?= $this->endSection('isi'); ?>
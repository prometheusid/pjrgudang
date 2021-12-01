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

<!-- Notif Error -->
<?= session()->getFlashdata('error'); ?>
<!-- notif berhasil -->
<?= session()->getFlashdata('sukses'); ?>

<!-- searchbox -->
<?= form_open('barang/index') ?>
<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Cari Kode, Nama, Kategori" name="cari" value="<?= $cari; ?>" autofocus>
    <div class="input-group-append">
        <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>

<!-- menammpilkan jumlah data -->
<span class="badge badge-success mb-2">
    <h6>
        Jumlah Data ditemukan = <?= $totaldata; ?>
    </h6>
</span>
<!-- end menampilkan jumlah data -->

<?= form_close(); ?>
<!-- end seachbox -->

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
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
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
                    <button type="button" class="btn btn-sm btn-info" title="Edit Data" onclick="edit('<?= $row['brgkode'] ?>')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="/barang/hapus/<?= $row['brgkode'] ?>" style="display:inline;" onsubmit="hapus();">
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
    <?= $pager->links('barang', 'paging'); ?>
</div>
<!-- end pagination -->

<script>
    function edit(kode) {
        window.location = ('/barang/edit/' + kode);
    }

    function hapus(kode) {
        pesan = confirm('Yakin Data Barang di Hapus ?');
        if (pesan) {
            return true;
        } else {
            return false;;
        }
    }
</script>
<?= $this->endSection('isi'); ?>
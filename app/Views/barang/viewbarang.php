<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Management Data Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-secondary',
    'onclick' => "location.href=('" . site_url('barang/formtambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>
<?= $this->section('isi'); ?>
<?= session()->getFlashdata('sukses'); ?>
<table class="table-striped table-bordered" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Barang</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td>
                    <button type="button" class="btn btn-info" title="Edit Data" onclick="edit('<?= $row['brgid'] ?>')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="/barang/hapus/<?= $row['brgid'] ?>" style="display:inline;" onsubmit="hapus();">
                        <input type="hidden" value="DELETE" name="_method">
                        <button type="submit" class="btn btn-danger" title="Hapus Data">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    function edit(id) {
        window.location = ('/barang/formedit/' + id);
    }

    function hapus() {
        pesan = confirm('Yakin Data Barang di Hapus ?');
        if (pesan) {
            return true;
        } else {
            return false;;
        }
    }
</script>
<?= $this->endSection('isi'); ?>
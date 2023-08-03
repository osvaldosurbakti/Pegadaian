<?php
$session = session();
?>

<?= $this->extend('layout/default'); ?>
<?= $this->section('content'); ?>
<section class="section">
    <?php if ($session->get('level') == 'superadmin') :  ?>
    <div class="section-header">
        <h1>Histori Penebusan</h1>
    </div>
    <?php else : ?>
    <div class="section-header">
        <h1>Histori Penebusan</h1>
    </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('Pesan')) : ?>
    <script type="text/javascript">
    $(document).ready(function() {
        swal({
            title: "Berhasil !",
            text: "<?= session()->getFlashdata('Pesan'); ?>",
            timer: 5000,
            icon: "success"
        });
    });
    </script>
    <?php endif; ?>
    <table class="table table-striped table-sm" id="historiPenebusan">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Kode Pinjaman</th>
                <th>Nama Nasabah</th>
                <th>Tgl. Penebusan</th>
                <th>Jumlah Dana</th>
                <th>Keterangan</th>
                <th>Kode Cabang</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($historiT as $row) :
            ?>
            <tr class="text-center">
                <td><?= $no++; ?></td>
                <td><?= $row->kode_pinjaman; ?></td>
                <td><?= $row->nama; ?></td>
                <td><?= $row->tanggal; ?></td>
                <td>Rp.<?= rupiah($row->dana); ?></td>
                <td><?= $row->keterangan ?></td>
                <td><?= $row->id_barang; ?></td>
                <textarea name="" hidden class="datarow-<?= $row->kode_pinjaman; ?>"
                    id=""><?= json_encode($row); ?></textarea>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#historiPenebusan').DataTable({
        responsive: true
    });
});
</script>

<?= $this->endSection(); ?>
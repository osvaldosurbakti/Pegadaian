<?php
$session = session();
?>

<?= $this->extend('layout/default'); ?>
<?= $this->section('content'); ?>
<section class="section">
    <?php if ($session->get('level') == 'superadmin') :  ?>
        <div class="section-header">
            <h1>Data Gadai</h1>
        </div>
    <?php else : ?>
        <div class="section-header">
            <h1>Data Gadai</h1>
            <div class="section-header-button">
                <a href="<?= site_url('formgadai') ?>" class="btn btn-primary">Tambah</a>
            </div>
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
    <div class="">
        <div class="petunjuk_warna">
            <ul>
                <li><i class="fas fa-square-full text-white"></i> Netral</li>
                <li><i class="fas fa-square-full text-warning"></i> Akan Jatuh Tempo</li>
                <li><i class="fas fa-square-full text-danger"></i> Jatuh Tempo Hari Ini</li>
            </ul>
        </div>
        <input type="hidden" id="base_url" value="<?= base_url() ?>" name="">
        <?php
        $getType = @$_GET['type'];
        ?>
        <input type="hidden" id="list_url" value="<?= base_url('listgadai') ?>" name="">
        <input type="hidden" id="type_data" value="<?= $getType ?>" name="">
        <div style="display: none;" id="table_column">
            [{"data":"no"},{"data":"kode_pinjaman"},{"data":"nama"},{"data":"nama_barang"},{"data":"tgl_gadai"},{"data":"tgl_jatuh_tempo"},{"data":"jumlah_pinjaman"},{"data":"bunga"}]
        </div>
        <div style="display: none;" id="table_columnDef">{"className":"white_space","targets":[2]}</div>
        <?php if ($session->get('level') == 'superadmin') :  ?>
            <div style="display: none;" data-style="dropdown" id="table_action">
                {"edit":false,"delete":false,"print":false,"notifWa":false,"detail":true,"pembayaran":false,"perpanjangan":false,"denda":false,"lelang":false}
            </div>
        <?php else : ?>
            <div style="display: none;" data-style="dropdown" id="table_action">
                {"edit":true,"delete":true,"print":true,"notifWa":false,"detail":true,"pembayaran":true,"perpanjangan":true,"denda":true,"lelang":true}
            </div>
        <?php endif; ?>
        <div class="my_box row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <select class="custom-select searchType" id="inputGroupSelect03">
                            <option value="kode_pinjaman" selected="">Kode Pinjaman</option>
                            <option value="nama">Nama Nasabah</option>
                        </select>
                    </div>
                    <input type="text" class="searchInput form-control" placeholder="Cari data...">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group-prepend">
                    <form action="" method="get">
                        <select class="custom-select" id="inputGroupSelect03" onchange="this.form.submit()" name="type">
                            <option value="">Semua Data</option>
                            <option value="akan_jatuh_tempo" <?= ($getType == 'akan_jatuh_tempo') ? 'selected' : ''; ?>>Akan Jatuh Tempo</option>
                            <option value="jatuh_tempo_sekarang" <?= ($getType == 'jatuh_tempo_sekarang') ? 'selected' : ''; ?>>Jatuh Tempo Hari Ini</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-daterange" style="border:none;align-items:flex-start">
                    <input type="text" class="form-control searchInputRange" name="tanggal_start" value="" placeholder="Dari" autocomplete="off" data-col-index="1" data-field="tanggal_start">
                    <div class="input-group-addon"><i class="fas fa-ellipsis-h"></i></div>
                    <input type="text" class="form-control searchInputRange" name="tanggal_end" value="" placeholder="Sampai" autocomplete="off" data-col-index="1" data-field="tanggal_end">
                </div>
            </div>
        </div>
        <table class="table table-md datatable table-hover table-bordered" id="">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Kode Pinjaman</th>
                    <th>Nama Nasabah</th>
                    <th>Jenis Barang</th>
                    <th>Tgl. Gadai</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Bunga</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="dataGadai"></tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="modalDetail">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Data Gadai Nasabah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text"><b>Kode Pinjaman : <span class="row_kode_pinjaman"></span></b></p>
                        <p class="card-text"><b>Nama Nasabah : <span class="row_nama"></span></b></p>
                        <p class="card-text"><b>No. Telpon : <span class="row_no_telp"></span></b></p>
                        <p class="card-text"><b>Tgl. Gadai: <span class="row_tgl_gadai"></span></b></p>
                        <p class="card-text"><b>Tgl. Jatuh Tempo: <span class="row_tgl_jatuh_tempo"></span></b></p>
                        <p class="card-text"><b>Tgl. Lelang: <span class="row_tgl_lelang"></span></b></p>
                        <p class="card-text"><b>Jumlah Pinjaman : <span class="row_jumlah_pinjaman"></span></b></p>
                        <p class="card-text"><b>Bunga : <span class="row_bunga"></span></b></p>
                        <p class="card-text"><b>Kode Cabang : <span class="row_kode_cabang"></span></b></p>
                        <p class="card-text"><b>Status : <span class="row_status_bayar"></span></b></p>
                        <p class="card-text"><b>Jenis Barang : <span class="row_nama_barang"></span></b></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Print -->
<div class="modal fade" id="modalPrintNota">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cetak Nota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="iframeNota" src="" style="height: 1191px; width: 100%;" frameborder="0" scrolling="no"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
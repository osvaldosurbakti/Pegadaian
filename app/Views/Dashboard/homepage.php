<?php
$session = session();
?>

<?= $this->extend('layout/default'); ?>
<title>Dashboard</title>
<?= $this->section('content'); ?>
<section class="section">
    <?php if ($session->get('level') == 'superadmin') :  ?>
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-button">
            <form action="" method="get">
                <div class="form-control">
                    <label>Pilih Cabang</label>
                    <select class="form-floating mb-3" name="kode_cabang" required onchange="this.form.submit()">
                        <option value="">Pilih Cabang!</option>
                        <?php foreach ($cabang as $row) : ?>
                        <option value="<?= $row['kode_cabang']; ?>"
                            <?= ($kode_cabang_sekarang == $row['kode_cabang']) ? 'selected' : ''; ?>>
                            <?= $row['nama_cabang']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="section-header-breadcrumb mt-3">
            <h6>Tanggal Hari Ini : <?= date('Y-m-d') ?></h6>
        </div>
    </div>
    <?php else : ?>
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb mt-3">
            <h6>Tanggal Hari Ini : <?= date('Y-m-d') ?></h6>
        </div>
        <div class="section-header-breadcrumb breadcrumb-item mt-3">
            <h6>Sisa saldo : Rp. <?php echo rupiah($saldo); ?></h6>
        </div>
    </div>
    <?php endif ?>


    <div class="section-body">
        <?php if ($session->get('level') == 'superadmin') :  ?>
        <div class="row">
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="<?= site_url('datagadai'); ?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Akan Jatuh Tempo</h4>
                            </div>
                            <div class="card-body">
                                <h6><?php echo $jTempo; ?></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Sisa Saldo</h4>
                        </div>
                        <div class="card-body">
                            <h6><?php echo rupiah($saldo); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Peminjaman Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <h6><?php echo rupiah($totalpinjam); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pendapatan Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <h6><?php echo rupiah($totaldapat); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="<?= site_url('datalelang'); ?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-dark">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Barang Masuk Lelang</h4>
                            </div>
                            <div class="card-body">
                                <h6><?php echo $masuk_lelang; ?></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php else : ?>
        <div class="row">
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="<?= site_url('datagadai'); ?>?type=jatuh_tempo_sekarang">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Akan Jatuh Tempo</h4>
                            </div>
                            <div class="card-body">
                                <h6><?php echo $jTempo; ?></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="<?= site_url('datalelang'); ?>">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-dark">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Barang Masuk Lelang</h4>
                            </div>
                            <div class="card-body">
                                <h6><?php echo $masuk_lelang; ?></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pendapatan Bulan Ini</h4>
                        </div>
                        <div class="card-body">
                            <h6><?php echo rupiah($totalDapatBulanIni); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cardmain col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pendapatan Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <h6><?php echo rupiah($totaldapat); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>History Transaksi Hari Ini</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Data Gadai</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tebus" role="tab"
                                aria-controls="profile" aria-selected="false">Data Penebusan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#perpanjang" role="tab"
                                aria-controls="contact" aria-selected="false">Data Perpanjangan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#denda" role="tab"
                                aria-controls="contact" aria-selected="false">Data Denda</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="">
                                <div class="petunjuk_warna">
                                    <ul>
                                        <li><i class="fas fa-square-full text-success"></i> Lunas (Nasabah Membayar Hari
                                            Ini)</li>
                                    </ul>
                                </div>
                                <table class="table table-striped table-sm">
                                    <tbody>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kode Pinjaman</th>
                                            <th>Nama Nasabah</th>
                                            <th>Tgl. Gadai</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Tgl. Lelang</th>
                                            <th>Jumlah Pinjaman</th>
                                            <th>Bunga</th>
                                            <th>Kode Cabang</th>
                                        </tr>
                                        <div>
                                            <?php
                                            $no = 1;
                                            foreach ($home as $row) :
                                            ?>
                                            <tr class="text-center <?= 'bg-' . $row->jatuh_tempo_now; ?>">
                                                <td><?= $no++; ?></td>
                                                <td><?= $row->kode_pinjaman; ?></td>
                                                <td><?= $row->nama; ?></td>
                                                <td><?= $row->tgl_gadai; ?></td>
                                                <td><?= $row->tgl_jatuh_tempo; ?></td>
                                                <td><?= $row->tgl_lelang; ?></td>
                                                <td><?= rupiah($row->jumlah_pinjaman); ?></td>
                                                <td><?= rupiah($row->bunga) ?></td>
                                                <td><?= $row->kode_cabang; ?></td>
                                                <textarea name="" hidden class="datarow-<?= $row->kode_pinjaman; ?>"
                                                    id=""><?= json_encode($row); ?></textarea>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tebus" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="">
                                <table class="table table-striped table-sm">
                                    <tbody>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kode Pinjaman</th>
                                            <th>Nama Nasabah</th>
                                            <th>Tgl. Penebusan</th>
                                            <th>Jumlah Dana</th>
                                            <th>Keterangan</th>
                                            <th>Kode Cabang</th>
                                        </tr>
                                        <div>
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
                                                <td><?= $row->kode_cabang; ?></td>
                                                <textarea name="" hidden
                                                    class="datarow-<?= $row->kode_pinjaman; ?>"
                                                    id=""><?= json_encode($row); ?></textarea>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="perpanjang" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="">
                                <table class="table table-striped table-sm">
                                    <tbody>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kode Pinjaman</th>
                                            <th>Nama Nasabah</th>
                                            <th>Jumlah Dana</th>
                                            <th>Keterangan</th>
                                            <th>Kode Cabang</th>
                                        </tr>
                                        <div>
                                            <?php
                                            $no = 1;
                                            foreach ($historiP as $row) :
                                            ?>
                                            <tr class="text-center">
                                                <td><?= $no++; ?></td>
                                                <td><?= $row->kode_pinjaman; ?></td>
                                                <td><?= $row->nama; ?></td>
                                                <td>Rp.<?= rupiah($row->dana); ?></td>
                                                <td><?= $row->keterangan ?></td>
                                                <td><?= $row->kode_cabang; ?></td>
                                                <textarea name="" hidden
                                                    class="datarow-<?= $row->kode_pinjaman; ?>"
                                                    id=""><?= json_encode($row); ?></textarea>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="denda" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="">
                                <table class="table table-striped table-sm">
                                    <tbody>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kode Pinjaman</th>
                                            <th>Nama Nasabah</th>
                                            <th>Jumlah Dana</th>
                                            <th>Keterangan</th>
                                            <th>Kode Cabang</th>
                                        </tr>
                                        <div>
                                            <?php
                                            $no = 1;
                                            foreach ($historiD as $row) :
                                            ?>
                                            <tr class="text-center">
                                                <td><?= $no++; ?></td>
                                                <td><?= $row->kode_pinjaman; ?></td>
                                                <td><?= $row->nama; ?></td>
                                                <td>Rp.<?= rupiah($row->dana); ?></td>
                                                <td><?= $row->keterangan ?></td>
                                                <td><?= $row->kode_cabang; ?></td>
                                                <textarea name="" hidden
                                                    class="datarow-<?= $row->kode_pinjaman; ?>"
                                                    id=""><?= json_encode($row); ?></textarea>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<?= $this->endSection(); ?>
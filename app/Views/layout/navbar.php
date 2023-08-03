<?php
$session = session();
?>
<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container">
        <a href="<?= base_url() ?>" class="navbar-brand sidebar-gone-hide">
            <img src="<?= base_url('template/assets/img/logo.jpg') ?>" alt="" style="width:50px">
        </a>
        <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
        <h1>UD.RICKY GADAI MEDAN</h1>
        <div class="nav-collapse">
            <a href="<?= site_url('login/logout') ?>" class="btn btn-round smooth btn-icon icon-left">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>
<nav class="navbar navbar-secondary navbar-expand-lg">
    <div class="container">
        <?php if ($session->get('level') == 'superadmin') :  ?>
            <ul class="navbar-nav ">
                <li class="nav-item"><a href="<?= site_url('dashboard') ?>" class="nav-link sc_load">Dashboard</a>
                </li>
                <li class="nav-item"><a href="<?= site_url('datagadai') ?>" class="nav-link sc_load">Pegadaian</a></li>
                <li class="nav-item"><a href="<?= site_url('saldo') ?>" class="nav-link sc_load">Saldo</a></li>
                <li class="nav-item"><a href="<?= site_url('datacabang') ?>" class="nav-link sc_load">Cabang</a></li>
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Pengaturan</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url('datanasabah') ?>" class="dropdown-item sc_load">Data
                                Nasabah</a></li>
                        <li><a href="<?= site_url('datauser') ?>" class="dropdown-item sc_load">Data
                                User</a></li>
                        <li><a href="<?= site_url('dataaturan') ?>" class="dropdown-item sc_load">Peraturan</a></li>
                        <li><a href="<?= site_url('databarang') ?>" class="dropdown-item sc_load">Jenis Barang</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Lelang</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url('terlelang') ?>" class="dropdown-item sc_load">Barang TerLelang</a></li>
                        <li><a href="<?= site_url('datalelang') ?>" class="dropdown-item sc_load">Akan Di
                                Lelang</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Histori</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url('historiT') ?>" class="nav-link sc_load">Histori Penebusan</a></li>
                        <li><a href="<?= site_url('historiD') ?>" class="nav-link sc_load">Histori Denda</a></li>
                        <li><a href="<?= site_url('historiP') ?>" class="nav-link sc_load">Histori Perpanjangan</a></li>
                    </ul>
                </li>
            </ul>
        <?php elseif ($session->get('level') == 'admin') : ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item"><a href="<?= site_url('datagadai') ?>" class="nav-link">Pegadaian</a>
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Lelang</a>
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a href="<?= site_url('terlelang') ?>" class="nav-link">Barang TerLelang</a>
                        </li>
                        <li class="nav-item"><a href="<?= site_url('datalelang') ?>" class="nav-link">Akan Di
                                Lelang</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="<?= site_url('datanasabah') ?>" class="nav-link">Data
                        Nasabah</a>
                <li class="nav-item dropdown"><a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Histori</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url('historiT') ?>" class="nav-link sc_load">Histori Penebusan</a></li>
                        <li><a href="<?= site_url('historiD') ?>" class="nav-link sc_load">Histori Denda</a></li>
                        <li><a href="<?= site_url('historiP') ?>" class="nav-link sc_load">Histori Perpanjangan</a></li>
                    </ul>
                </li>
            </ul>
        <?php else : ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Lelang</a>
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a href="<?= site_url('terlelang') ?>" class="nav-link">Barang TerLelang</a>
                        </li>
                        <li class="nav-item"><a href="<?= site_url('datalelang') ?>" class="nav-link">Akan Di
                                Lelang</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="<?= site_url('datanasabah') ?>" class="nav-link">Data
                        Nasabah</a>
                <li class="nav-item dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown" aria-expanded="false">Laporan</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= site_url('laporanakanlelang') ?>" class="nav-link sc_load">Akan Dilelang</a></li>
                        <li><a href="<?= site_url('laporanterlelang') ?>" class="nav-link sc_load">Barang Terlelang</a></li>
                    </ul>
                </li>
            </ul>
        <?php endif ?>
    </div>
</nav>
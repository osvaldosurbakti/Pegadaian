/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.31 : Database - pegadaian
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cabang` */

DROP TABLE IF EXISTS `cabang`;

CREATE TABLE `cabang` (
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nama_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `no_telp` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `kode_toko` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`kode_cabang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `cabang` */

insert  into `cabang`(`kode_cabang`,`nama_cabang`,`alamat`,`no_telp`,`kode_toko`) values ('FG00','SUPERADMIN','SUPERADMIN','123','SUPERADMIN');
insert  into `cabang`(`kode_cabang`,`nama_cabang`,`alamat`,`no_telp`,`kode_toko`) values ('FG01','Ricky Gadai','Jl. Jamin Ginting No.701, Titi Rantai, Kec. Medan Baru, Kota Medan, Sumatera Utara 20157','081 337 995 667','FG1');

/*Table structure for table `histori` */

DROP TABLE IF EXISTS `histori`;

CREATE TABLE `histori` (
  `id_histori` int NOT NULL AUTO_INCREMENT,
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `dana` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `jenis` enum('penebusan','perpanjangan','denda') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `keterangan` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_histori`),
  KEY `kode_pinjaman` (`kode_pinjaman`),
  KEY `kode_cabang` (`kode_cabang`),
  CONSTRAINT `histori_ibfk_1` FOREIGN KEY (`kode_pinjaman`) REFERENCES `pinjamangadai` (`kode_pinjaman`),
  CONSTRAINT `histori_ibfk_2` FOREIGN KEY (`kode_cabang`) REFERENCES `pinjamangadai` (`kode_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `histori` */

insert  into `histori`(`id_histori`,`kode_pinjaman`,`tanggal`,`dana`,`jenis`,`keterangan`,`kode_cabang`) values (1,'FG1-3260623','2023-06-26','50000','perpanjangan','Bunga Perpanjangan','FG01');
insert  into `histori`(`id_histori`,`kode_pinjaman`,`tanggal`,`dana`,`jenis`,`keterangan`,`kode_cabang`) values (2,'FG1-1260623','2023-06-26','500000','penebusan','Penebusan Barang','FG01');
insert  into `histori`(`id_histori`,`kode_pinjaman`,`tanggal`,`dana`,`jenis`,`keterangan`,`kode_cabang`) values (3,'FG1-2260623','2023-06-26','100000','denda','Keuntungan Lelang','FG01');
insert  into `histori`(`id_histori`,`kode_pinjaman`,`tanggal`,`dana`,`jenis`,`keterangan`,`kode_cabang`) values (4,'FG1-2260623','2023-06-26','500000','penebusan','Jumlah Pinjaman','FG01');

/*Table structure for table `kas` */

DROP TABLE IF EXISTS `kas`;

CREATE TABLE `kas` (
  `id_kas` int unsigned NOT NULL AUTO_INCREMENT,
  `jumlah_kas` float NOT NULL,
  `sisa_kas` float NOT NULL,
  `tgl_masuk` timestamp NULL DEFAULT NULL,
  `tgl_keluar` timestamp NULL DEFAULT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `jenis` enum('masuk','keluar','pembayaran','lelang','pembatalan') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `kode_transaksi` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_kas`),
  KEY `kode_cabang` (`kode_cabang`),
  CONSTRAINT `kas_ibfk_1` FOREIGN KEY (`kode_cabang`) REFERENCES `cabang` (`kode_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `kas` */

insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (1,50000000,50000000,'2023-06-26 12:49:43','2023-06-26 12:49:43','Saldo Awal','FG01','masuk','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (2,500000,49500000,'2023-06-26 12:50:26','2023-06-26 12:50:26','Transaksi Pegadaian Baru dengan kode FG1-1260623','FG01','keluar','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (3,500000,49000000,'2023-06-26 12:51:50','2023-06-26 12:51:50','Transaksi Pegadaian Baru dengan kode FG1-2260623','FG01','keluar','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (4,500000,48500000,'2023-06-26 12:52:25','2023-06-26 12:52:25','Transaksi Pegadaian Baru dengan kode FG1-3260623','FG01','keluar','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (5,500000,49000000,'2023-06-26 13:23:35','2023-06-26 13:23:35','Pembayaran','FG01','pembayaran','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (6,500000,48500000,'2023-06-26 13:32:01','2023-06-26 13:32:01','Transaksi Pegadaian Baru dengan kode FG1-4260623','FG01','keluar','');
insert  into `kas`(`id_kas`,`jumlah_kas`,`sisa_kas`,`tgl_masuk`,`tgl_keluar`,`keterangan`,`kode_cabang`,`jenis`,`kode_transaksi`) values (7,500000,49000000,'2023-06-26 13:36:33','2023-06-26 13:36:33','Lelang','FG01','lelang','');

/*Table structure for table `kategori_barang` */

DROP TABLE IF EXISTS `kategori_barang`;

CREATE TABLE `kategori_barang` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `nama_barang` (`nama_barang`),
  KEY `nama_barang_2` (`nama_barang`),
  KEY `nama_barang_3` (`nama_barang`),
  FULLTEXT KEY `nama_barang_4` (`nama_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `kategori_barang` */

insert  into `kategori_barang`(`id_barang`,`nama_barang`) values (4,'Elektronik');
insert  into `kategori_barang`(`id_barang`,`nama_barang`) values (2,'HP');
insert  into `kategori_barang`(`id_barang`,`nama_barang`) values (1,'Laptop');
insert  into `kategori_barang`(`id_barang`,`nama_barang`) values (3,'Motor');

/*Table structure for table `lelang` */

DROP TABLE IF EXISTS `lelang`;

CREATE TABLE `lelang` (
  `id_lelang` int NOT NULL AUTO_INCREMENT,
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `hasil_lelang` int NOT NULL,
  `tgl_lelang` date NOT NULL,
  `id_barang` int NOT NULL,
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `keterangan` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_lelang`),
  KEY `id_barang` (`id_barang`),
  KEY `kode_pinjaman` (`kode_pinjaman`),
  CONSTRAINT `lelang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `pinjamangadai` (`id_barang`),
  CONSTRAINT `lelang_ibfk_2` FOREIGN KEY (`kode_pinjaman`) REFERENCES `pinjamangadai` (`kode_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `lelang` */

insert  into `lelang`(`id_lelang`,`kode_pinjaman`,`hasil_lelang`,`tgl_lelang`,`id_barang`,`kode_cabang`,`keterangan`) values (7,'FG1-2260623',600000,'2023-06-26',0,'FG01','Lelang');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (1,'2022-01-03-080231','App\\Database\\Migrations\\Nasabah','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (2,'2022-01-03-080340','App\\Database\\Migrations\\Cabang','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (3,'2022-01-03-080356','App\\Database\\Migrations\\PinjamanGadai','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (4,'2022-01-03-080403','App\\Database\\Migrations\\Pembayaran','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (5,'2022-01-03-080411','App\\Database\\Migrations\\Peraturan','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (6,'2022-01-03-080416','App\\Database\\Migrations\\Perpanjangan','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (7,'2022-01-03-080420','App\\Database\\Migrations\\Kas','default','App',1641285754,1);
insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values (8,'2022-01-04-084948','App\\Database\\Migrations\\User','default','App',1641286892,2);

/*Table structure for table `nasabah` */

DROP TABLE IF EXISTS `nasabah`;

CREATE TABLE `nasabah` (
  `id_nasabah` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `alamat_nasabah` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `no_telp` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nasabah`),
  KEY `kode_cabang` (`kode_cabang`),
  CONSTRAINT `nasabah_ibfk_1` FOREIGN KEY (`kode_cabang`) REFERENCES `cabang` (`kode_cabang`),
  CONSTRAINT `nasabah_ibfk_2` FOREIGN KEY (`kode_cabang`) REFERENCES `cabang` (`kode_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `nasabah` */

insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (1,'Anta','191532308112323','fds','24444','FG01','Aktif','2023-06-16 19:34:43','2023-06-20 05:36:26');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (2,'Prima','19153230811231','awdwaddwa','0812054641876','FG01','Aktif','2023-06-16 19:34:43','2023-06-16 19:34:43');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (3,'Agus','19153230811231','jkjkjjkjk','08120546418','FG01','Tidak Aktif','2023-06-16 19:34:43','2023-06-21 06:41:27');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (5,'dasa','1208264607910002','aaa','32323','FG01','Aktif','2023-06-19 07:20:11','2023-06-19 07:20:11');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (6,'dasa','1208264607910002','aaa','32323','FG01','Aktif','2023-06-19 07:20:53','2023-06-19 07:20:53');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (7,'dasa','1208264607910002','aaa','32323','FG01','Aktif','2023-06-19 07:21:03','2023-06-19 07:21:03');
insert  into `nasabah`(`id_nasabah`,`nama`,`nik`,`alamat_nasabah`,`no_telp`,`kode_cabang`,`status`,`created_at`,`updated_at`) values (8,'dasa','1208264607910002','aaa','32323','FG01','Aktif','2023-06-19 07:21:50','2023-06-19 07:21:50');

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `id_pembayaran` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tgl_bayar` date NOT NULL,
  `jumlah_bayar` float NOT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `kode_pinjaman` (`kode_pinjaman`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`kode_pinjaman`) REFERENCES `pinjamangadai` (`kode_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`id_pembayaran`,`kode_pinjaman`,`tgl_bayar`,`jumlah_bayar`,`keterangan`) values (1,'FG1-1260623','2023-06-26',500000,'Pembayaran');

/*Table structure for table `pendapatan` */

DROP TABLE IF EXISTS `pendapatan`;

CREATE TABLE `pendapatan` (
  `id_pendapatan` int unsigned NOT NULL AUTO_INCREMENT,
  `jumlah_untung` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `jenis` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`id_pendapatan`),
  KEY `kode_pinjaman` (`kode_pinjaman`),
  CONSTRAINT `pendapatan_ibfk_1` FOREIGN KEY (`kode_pinjaman`) REFERENCES `pinjamangadai` (`kode_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `pendapatan` */

insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (1,'50000','FG1-1260623','Bunga','2023-06-26',NULL);
insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (2,'50000','FG1-2260623','Bunga','2023-06-26',NULL);
insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (3,'50000','FG1-3260623','Bunga','2023-06-26',NULL);
insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (4,'50000','FG1-3260623','bunga',NULL,'Perpanjangan');
insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (5,'50000','FG1-4260623','Bunga','2023-06-26',NULL);
insert  into `pendapatan`(`id_pendapatan`,`jumlah_untung`,`kode_pinjaman`,`jenis`,`tgl_masuk`,`keterangan`) values (6,'100000','FG1-2260623','Lelang','2023-06-26','Lelang');

/*Table structure for table `peraturan` */

DROP TABLE IF EXISTS `peraturan`;

CREATE TABLE `peraturan` (
  `id_peraturan` int unsigned NOT NULL AUTO_INCREMENT,
  `bunga` int NOT NULL,
  `denda` int NOT NULL,
  PRIMARY KEY (`id_peraturan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `peraturan` */

insert  into `peraturan`(`id_peraturan`,`bunga`,`denda`) values (1,10,5);
insert  into `peraturan`(`id_peraturan`,`bunga`,`denda`) values (2,15,5);

/*Table structure for table `perpanjangan` */

DROP TABLE IF EXISTS `perpanjangan`;

CREATE TABLE `perpanjangan` (
  `id_perpanjangan` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tgl_perpanjangan` date NOT NULL,
  PRIMARY KEY (`id_perpanjangan`),
  KEY `kode_pinjamann` (`kode_pinjaman`),
  CONSTRAINT `perpanjangan_ibfk_1` FOREIGN KEY (`kode_pinjaman`) REFERENCES `pinjamangadai` (`kode_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `perpanjangan` */

insert  into `perpanjangan`(`id_perpanjangan`,`kode_pinjaman`,`tgl_perpanjangan`) values (1,'FG1-3260623','2023-06-28');

/*Table structure for table `pinjamangadai` */

DROP TABLE IF EXISTS `pinjamangadai`;

CREATE TABLE `pinjamangadai` (
  `kode_pinjaman` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `id_nasabah` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `seri` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `kelengkapan` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `jumlah` int DEFAULT NULL,
  `kondisi` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tgl_gadai` date DEFAULT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `tgl_lelang` date DEFAULT NULL,
  `jumlah_pinjaman` double DEFAULT NULL,
  `bunga` float DEFAULT NULL,
  `kode_cabang` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status_bayar` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kode_pinjaman`),
  KEY `id_barang` (`id_barang`),
  KEY `id_nasabah` (`id_nasabah`),
  KEY `kode_cabang` (`kode_cabang`),
  CONSTRAINT `pinjamangadai_ibfk_1` FOREIGN KEY (`kode_cabang`) REFERENCES `cabang` (`kode_cabang`),
  CONSTRAINT `pinjamangadai_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `kategori_barang` (`id_barang`),
  CONSTRAINT `pinjamangadai_ibfk_3` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `pinjamangadai` */

insert  into `pinjamangadai`(`kode_pinjaman`,`id_nasabah`,`id_barang`,`seri`,`kelengkapan`,`password`,`jumlah`,`kondisi`,`tgl_gadai`,`tgl_jatuh_tempo`,`tgl_lelang`,`jumlah_pinjaman`,`bunga`,`kode_cabang`,`status_bayar`) values ('FG1-1260623',1,4,'123BGA','Full Set','1234',1,'Bagus','2023-06-26','2023-06-26','2023-06-26',500000,50000,'FG01','Lunas');
insert  into `pinjamangadai`(`kode_pinjaman`,`id_nasabah`,`id_barang`,`seri`,`kelengkapan`,`password`,`jumlah`,`kondisi`,`tgl_gadai`,`tgl_jatuh_tempo`,`tgl_lelang`,`jumlah_pinjaman`,`bunga`,`kode_cabang`,`status_bayar`) values ('FG1-2260623',2,1,'GFSA','Full Set','1234',1,'Bagus','2023-06-19','2023-06-19','2023-06-26',500000,50000,'FG01','TERLELANG');
insert  into `pinjamangadai`(`kode_pinjaman`,`id_nasabah`,`id_barang`,`seri`,`kelengkapan`,`password`,`jumlah`,`kondisi`,`tgl_gadai`,`tgl_jatuh_tempo`,`tgl_lelang`,`jumlah_pinjaman`,`bunga`,`kode_cabang`,`status_bayar`) values ('FG1-3260623',3,1,'123DSA','Full Set','1234',1,'Second','2023-06-26','2023-06-28','2023-06-28',500000,50000,'FG01','Belum Lunas');
insert  into `pinjamangadai`(`kode_pinjaman`,`id_nasabah`,`id_barang`,`seri`,`kelengkapan`,`password`,`jumlah`,`kondisi`,`tgl_gadai`,`tgl_jatuh_tempo`,`tgl_lelang`,`jumlah_pinjaman`,`bunga`,`kode_cabang`,`status_bayar`) values ('FG1-4260623',5,3,'BK1234G','Rem Blong','1234',1,'Bagus','2023-06-26','2023-06-26','2023-06-26',500000,50000,'FG01','Belum Lunas');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id_user` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `cabang` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `level` enum('superadmin','admin') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id_user`,`nama_user`,`username`,`password`,`cabang`,`level`) values (9,'SUPERADMIN 1','superadmin1','$2y$10$g/aQbqEFboXrIDULZ3fcfecOmg6i04ra9luez4iiNF/9oIEEUuSW6','FG00','superadmin');
insert  into `user`(`id_user`,`nama_user`,`username`,`password`,`cabang`,`level`) values (10,'Admin 2','admin2','$2y$10$Nap.qianfqFuwQL7QCwjmu7B1nrObaSNqyockukh4YwGEoVtpmqWO','FG01','admin');
insert  into `user`(`id_user`,`nama_user`,`username`,`password`,`cabang`,`level`) values (11,'ADMIN 1','admin1','$2y$10$a938/U6wes1N64JD056T6O2HFMQie/GxBJxU/XN1cWzPrpND0xqgu','FG02','admin');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

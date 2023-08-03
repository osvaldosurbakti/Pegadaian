<?php

namespace App\Models;

use CodeIgniter\Model;

class PegadaianModel extends Model
{
    protected $table = 'pinjamangadai';
    protected $primaryKey = 'kode_pinjaman';
    protected $allowedFields = ['kode_pinjaman', 'id_nasabah', 'id_barang', 'seri', 'kelengkapan', 'password', 'jumlah', 'kondisi', 'tgl_gadai', 'tgl_jatuh_tempo', 'tgl_lelang', 'jumlah_pinjaman', 'bunga', 'kode_cabang', 'status_bayar'];
    protected $returnType = 'array';

    public function getDataGadai($kode_cabang = null, $dataSekarang = null, $tanggal_start = null, $tanggal_end = null)
    {
        $this->db->table('pinjamangadai');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('cabang', 'cabang.kode_cabang = pinjamangadai.kode_cabang');
        $this->join('kategori_barang', 'kategori_barang.id_barang = pinjamangadai.id_barang', 'left');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('pinjamangadai.kode_cabang', $kode_cabang);
        }

        if (!empty($dataSekarang) && $dataSekarang == 'hariIni') {

            $this->where('pinjamangadai.tgl_gadai', date('Y-m-d'));
        }
        if (!empty($tanggal_start) && !empty($tanggal_end)) {
            $this->where('tgl_gadai >=', $tanggal_start);
            $this->where('tgl_gadai <=', $tanggal_end);
        }
        return $this->get()->getResultObject();
    }

    public function get_printDataGadai($kode_pinjaman)
    {
        $this->db->table('pinjamangadai');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('kategori_barang', 'kategori_barang.id_barang = pinjamangadai.id_barang', 'left');
        $this->where('pinjamangadai.kode_pinjaman', $kode_pinjaman);
        return $this->get()->getRow();
    }

    public function cek_pendapatan_peminjaman($jenis, $kode_pinjaman)
    {
        $query = $this->query("SELECT jumlah_untung, count(id_pendapatan) as total_data FROM pendapatan WHERE kode_pinjaman = '$kode_pinjaman' AND jenis = '$jenis' ");
        if ($query) {
            return $query->getRow();
        }
        return 0;
    }

    public function bungaTotal($jenis, $kode_pinjaman)
    {

        $query = $this->query("SELECT SUM(jumlah_untung) as total FROM pendapatan WHERE kode_pinjaman = '$kode_pinjaman' AND jenis = '$jenis' ");
        if ($query) {
            return $query->getRow();
        }
        return 0;
    }

    public function getDataLelang($kode_cabang = null, $dataSekarang = null)
    {
        $this->db->table('pinjamangadai');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('cabang', 'cabang.kode_cabang = pinjamangadai.kode_cabang');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('pinjamangadai.kode_cabang', $kode_cabang);
        }

        if (!empty($dataSekarang) && $dataSekarang == 'hariIni') {

            $this->where('pinjamangadai.tgl_lelang', date('Y-m-d'));
        }
        return $this->get()->getResultObject();
    }

    public function sortDate($kode_cabang = null)
    {

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $query = $this->query("SELECT count(kode_pinjaman) as total FROM pinjamangadai WHERE tgl_jatuh_tempo = date(NOW()) && kode_cabang = '$kode_cabang' AND status_bayar != 'Lunas'");
        } else {
            $query = $this->query("SELECT count(kode_pinjaman) as total FROM pinjamangadai WHERE tgl_jatuh_tempo = date(NOW()) AND status_bayar != 'Lunas' ");
        }
        $data = $query->getRow()->total;
        return $data;
    }

    public function sortDateLelang($kode_cabang = null)
    {
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $query = $this->query("SELECT count(kode_pinjaman) as total FROM pinjamangadai WHERE tgl_jatuh_tempo < date(NOW()) && kode_cabang = '$kode_cabang' AND status_bayar != 'Lunas' AND status_bayar != 'TERLELANG' ");
        } else {
            $query = $this->query("SELECT count(kode_pinjaman) as total FROM pinjamangadai WHERE tgl_jatuh_tempo < date(NOW()) AND status_bayar != 'Lunas' AND status_bayar != 'TERLELANG' ");
        }
        $data = $query->getRow()->total;
        return $data;
    }

    public function selectJatuhTempo($kode_cabang = null)
    {
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $query = $this->query("SELECT * FROM pinjamangadai WHERE tgl_jatuh_tempo = date(NOW()) && kode_cabang = '$kode_cabang' AND status_bayar != 'Lunas'");
        } else {
            $query = $this->query("SELECT * FROM pinjamangadai WHERE tgl_jatuh_tempo = date(NOW()) AND status_bayar != 'Lunas'");
        }

        $data = $query->getResultArray();
        return $data;
    }

    public function create_kode_pinjaman($cabang_kode)
    {
        $dd = $this->query("SELECT kode_toko FROM cabang where kode_cabang = '$cabang_kode' ");
        $get_kode_cabang = $dd->getResultArray();
        $kode_cabang = $get_kode_cabang[0]['kode_toko'];
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $get_cek_pinjamangadai = $this->query("SELECT count(kode_pinjaman) as total FROM pinjamangadai where kode_cabang = '$cabang_kode' AND YEAR(tgl_gadai) = $tahun_sekarang AND MONTH(tgl_gadai) = $bulan_sekarang ");
        $row_data_pinjamangadai = $get_cek_pinjamangadai->getResultArray();
        $jumlah_data_peminjam_sekarang = $row_data_pinjamangadai[0]['total'];
        $kode_pinjaman = $kode_cabang . '-' . ($jumlah_data_peminjam_sekarang + 1) . date('dmy');
        return $kode_pinjaman;
    }

    public function simpan($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('pinjamangadai');
        return $builder->insert($data);
    }

    public function simpan_nasabah_import($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('nasabah');
        $builder->insert($data);
        $user_id = $db->insertID();
        return $user_id;
    }

    public function getTotalPinjaman($kode_cabang)
    {
        $data = $this->query("SELECT sum(jumlah_pinjaman) as jumlah_pinjaman FROM `pinjamangadai` WHERE tgl_gadai = date(NOW()) && kode_cabang = '" . $kode_cabang . "'")->getResultArray();
        return $data;
    }


    public function countResultTable($kode_cabang = null, $type_data = null)
    {
        $this->selectCount('kode_pinjaman');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('pinjamangadai.kode_cabang', $kode_cabang);
        }
        if (!empty($type_data) && $type_data == "TERLELANG") {
            $this->where('pinjamangadai.tgl_jatuh_tempo <', date('Y-m-d'));
        } else {
            $this->where('pinjamangadai.tgl_jatuh_tempo >=', date('Y-m-d'));
        }
        $this->where('pinjamangadai.status_bayar !=', 'TERLELANG');
        $this->where('pinjamangadai.status_bayar !=', 'Lunas');
        $query = $this->get()->getRow()->kode_pinjaman;
        return $query;
    }

    public function count_filter($query, $kode_cabang = null, $type_data = null, $keySearch)
    {
        $this->select('count("kode_pinjaman") as qty');
        if ($keySearch != 'tgl_gadai') {
            $this->groupStart();
            $this->orLike('pinjamangadai.kode_pinjaman', $query, 'BOTH');
            $this->groupEnd();
        }

        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('perpanjangan', 'perpanjangan.kode_pinjaman = pinjamangadai.kode_pinjaman', 'left');
        $this->join('kategori_barang', 'kategori_barang.id_barang = pinjamangadai.id_barang', 'left');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('pinjamangadai.kode_cabang', $kode_cabang);
        }
        if (!empty($type_data) && $type_data == "TERLELANG") {
            $this->where('pinjamangadai.tgl_jatuh_tempo <', date('Y-m-d'));
        } else {
            $this->where('pinjamangadai.tgl_jatuh_tempo >=', date('Y-m-d'));
        }
        // filter jaatuh tempo hari ini
        if (!empty($type_data) && $type_data == "jatuh_tempo_sekarang") {
            $this->where('pinjamangadai.tgl_jatuh_tempo ', date('Y-m-d'));
        }
        // filter Akan jaatuh tempo
        if (!empty($type_data) && $type_data == "akan_jatuh_tempo") {
            $this->where('pinjamangadai.tgl_jatuh_tempo ', date('Y-m-d', strtotime("+1 day")));
        }
        // select_by date range
        if (isset($_GET['tanggal_start']) && $_GET['tanggal_start'] != "") {
            $this->where('pinjamangadai.tgl_gadai >=', $_GET['tanggal_start']);
        }
        if (isset($_GET['tanggal_end']) && $_GET['tanggal_end'] != "") {
            $this->where('pinjamangadai.tgl_gadai <=', $_GET['tanggal_end']);
        }
        $this->where('pinjamangadai.status_bayar !=', 'TERLELANG');
        $this->where('pinjamangadai.status_bayar !=', 'Lunas');
        $this->orderBy('tgl_gadai', 'desc');
        $this->orderBy('pinjamangadai.kode_pinjaman', 'desc');
        return $this->get()->getRow()->qty;
    }

    public function listDataGadai($start, $length, $query, $keysearch, $kode_cabang = null, $type_data = null)
    {
        $request = \Config\Services::request();
        $this->select('pinjamangadai.kode_pinjaman as kode_pinjaman , pinjamangadai.id_nasabah, nasabah.nama, kategori_barang.nama_barang, tgl_gadai, tgl_jatuh_tempo, tgl_lelang, jumlah_pinjaman, bunga, pinjamangadai.kode_cabang');
        if ($keysearch != 'tgl_gadai') {
            $this->groupStart();
            $this->orLike($keysearch, $query, 'BOTH');
            $this->groupEnd();
        }

        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('perpanjangan', 'perpanjangan.kode_pinjaman = pinjamangadai.kode_pinjaman', 'left');
        $this->join('kategori_barang', 'kategori_barang.id_barang = pinjamangadai.id_barang', 'left');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('pinjamangadai.kode_cabang', $kode_cabang);
        }
        if (!empty($type_data) && $type_data == "TERLELANG") {
            $this->where('pinjamangadai.tgl_jatuh_tempo <', date('Y-m-d'));
        } else {
            $this->where('pinjamangadai.tgl_jatuh_tempo >=', date('Y-m-d'));
        }
        // filter jaatuh tempo hari ini
        if (!empty($type_data) && $type_data == "jatuh_tempo_sekarang") {
            $this->where('pinjamangadai.tgl_jatuh_tempo ', date('Y-m-d'));
        }
        // filter Akan jaatuh tempo
        if (!empty($type_data) && $type_data == "akan_jatuh_tempo") {
            $this->where('pinjamangadai.tgl_jatuh_tempo ', date('Y-m-d', strtotime("+1 day")));
        }
        // select_by date range
        if (isset($_GET['tanggal_start']) && $_GET['tanggal_start'] != "") {
            $this->where('pinjamangadai.tgl_gadai >=', $_GET['tanggal_start']);
        }
        if (isset($_GET['tanggal_end']) && $_GET['tanggal_end'] != "") {
            $this->where('pinjamangadai.tgl_gadai <=', $_GET['tanggal_end']);
        }
        $this->where('pinjamangadai.status_bayar !=', 'TERLELANG');
        $this->where('pinjamangadai.status_bayar !=', 'Lunas');
        $this->orderBy('tgl_gadai', 'desc');
        $this->orderBy('pinjamangadai.kode_pinjaman', 'desc');
        return $this->get($length, $start)->getResultObject();
    }

    public function get_month_olny()
    {
        $query = $this->query("SELECT date_format(tgl_gadai, '%M') AS bulan,date_format(tgl_gadai, '%Y') AS tahun,COUNT(kode_pinjaman) as total_data from pinjamangadai group by date_format(tgl_gadai, '%M') ORDER BY tgl_gadai ASC");
        $data = $query->getResultObject();
        return $data;
    }

    public function cek_peminjaman($kode_pinjaman)
    {
        if (!empty($kode_pinjaman)) {
            $query = $this->query("SELECT jumlah_pinjaman from pinjamangadai WHERE kode_pinjaman = '" . $kode_pinjaman . "' ");
            $data = $query->getRow()->jumlah_pinjaman;
            return $data;
        }
        return null;
    }

    public function get_detail_pegadaian($kode_pinjaman)
    {
        $this->db->table('pinjamangadai');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');
        $this->join('kategori_barang', 'kategori_barang.id_barang = pinjamangadai.id_barang', 'left');
        $this->where('pinjamangadai.kode_pinjaman', $kode_pinjaman);
        return $this->get()->getRow();
    }
}

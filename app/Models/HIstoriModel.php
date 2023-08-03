<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriModel extends Model
{
    protected $table = 'histori';
    protected $primaryKey = 'id_histori';
    protected $allowedFields = ['kode_pinjaman', 'kode_cabang', 'tanggal', 'dana', 'jenis', 'keterangan'];
    protected $returnType = 'array';

    public function HistoriTebus($kode_cabang = null, $jenis = null, $tanggal = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'penebusan') {
            $this->where('histori.jenis', 'penebusan');
        }
        if (!empty($tanggal) && $tanggal = 'now') {
            $this->where('histori.tanggal', date('Y-m-d'));
        }
        return $this->get()->getResultObject();
    }

    public function HistoriPerpanjangan($kode_cabang = null, $jenis = null, $tanggal = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'perpanjangan') {
            $this->where('histori.jenis', 'perpanjangan');
        }
        if (!empty($tanggal) && $tanggal = 'now') {
            $this->where('histori.tanggal', date('Y-m-d'));
        }
        return $this->get()->getResultObject();
    }

    public function HistoriDenda($kode_cabang = null, $jenis = null, $tanggal = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'denda') {
            $this->where('histori.jenis', 'denda');
        }
        if (!empty($tanggal) && $tanggal = 'now') {
            $this->where('histori.tanggal', date('Y-m-d'));
        }
        return $this->get()->getResultObject();
    }

    public function AllHistoriTebus($kode_cabang = null, $jenis = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'penebusan') {
            $this->where('histori.jenis', 'penebusan');
        }

        return $this->get()->getResultObject();
    }

    public function AllHistoriPerpanjangan($kode_cabang = null, $jenis = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'perpanjangan') {
            $this->where('histori.jenis', 'perpanjangan');
        }
        return $this->get()->getResultObject();
    }

    public function AllHistoriDenda($kode_cabang = null, $jenis = null)
    {
        $this->db->table('histori');
        $this->join('pinjamangadai', 'pinjamangadai.kode_pinjaman = histori.kode_pinjaman');
        $this->join('nasabah', 'nasabah.id_nasabah = pinjamangadai.id_nasabah');

        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('histori.kode_cabang', $kode_cabang);
        }
        if (!empty($jenis) && $jenis = 'denda') {
            $this->where('histori.jenis', 'denda');
        }
        return $this->get()->getResultObject();
    }
}

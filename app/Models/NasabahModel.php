<?php

namespace App\Models;

use CodeIgniter\Model;

class NasabahModel extends Model
{
    protected $table = 'nasabah';
    protected $primaryKey = 'id_nasabah';
    protected $useTimestamps = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $allowedFields = ['nama', 'nik', 'alamat_nasabah', 'no_telp', 'kode_cabang', 'status'];
    protected $returnType = 'array';

    public function getDataNasabah($kode_cabang = null)
    {

        $this->join('cabang', 'cabang.kode_cabang = nasabah.kode_cabang');
        if (!empty($kode_cabang) && $kode_cabang != 'FG00') {
            $this->where('nasabah.kode_cabang', $kode_cabang);
        }
        return $this->get()->getResultObject();
    }

    public function getStatus($status = null)
    {
        $this->db->table('nasabah');
        $this->where('nasabah.status', $status);

        return $this->get()->getResultObject();
    }

    public function getStatusAll($status = null)
    {
        $names = array('Aktif', 'Tidak Aktif');
        $this->db->table('nasabah');
        $this->whereIN('status', $names);

        return $this->get()->getResultObject();
    }

    public function simpan($data)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('nasabah');
        return $builder->insert($data);
    }
}

<?php

namespace App\Controllers;

use App\Models\PegadaianModel;
use App\Models\NasabahModel;
use App\Models\CabangModel;
use App\Models\SaldoModel;
use App\Models\PembayaranModel;
use App\Models\PendapatanModel;
use App\Models\AturanModel;
use App\Models\BarangModel;
use App\Models\LelangModel;
use App\Models\PerpanjanganModel;
use App\Models\HistoriModel;

class Pegadaian extends BaseController
{
    protected $PegadaianModel;
    protected $NasabahModel;
    protected $CabangModel;
    protected $SaldoModel;
    protected $cabang;
    protected $telp;
    protected $PembayaranModel;
    protected $PendapatanModel;
    protected $AturanModel;
    protected $PerpanjanganModel;
    protected $LelangModel;
    protected $BarangModel;
    protected $HistoriModel;

    public function __construct()
    {
        $this->PegadaianModel = new PegadaianModel();
        $this->NasabahModel = new NasabahModel();
        $this->CabangModel = new CabangModel();
        $this->SaldoModel = new SaldoModel();
        $this->PembayaranModel = new PembayaranModel();
        $this->PendapatanModel = new PendapatanModel();
        $this->AturanModel = new AturanModel();
        $this->BarangModel = new BarangModel();
        $this->LelangModel = new LelangModel();
        $this->PerpanjanganModel = new PerpanjanganModel();
        $this->HistoriModel = new HistoriModel();
        helper('currency');
    }

    public function index()
    {
        $semua_data_bulanan =  $this->PegadaianModel->get_month_olny();
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $data_gadai = $this->PegadaianModel->getDataGadai($kode_cabang);
        foreach ($data_gadai as $key) {
            $cek_ = '';

            $hari_esok = date('Y-m-d', strtotime("+1 day"));
            if ($key->tgl_jatuh_tempo == date('Y-m-d')) {
                $cek_ = 'danger text-white';
            } elseif ($key->tgl_jatuh_tempo == $hari_esok) {
                $cek_ = 'warning text-white';
            } else {
                $cek_ = 'default';
            }
            $key->jatuh_tempo_now = $cek_;
        }
        $data = [
            'title' => 'Data Gadai',
            'gadai' => $data_gadai,
            'semua_data_bulanan' => $semua_data_bulanan
        ];
        return view('Pegadaian/datagadai', $data);
    }

    public function create()
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $data_nasabah = $this->NasabahModel->getDataNasabah($kode_cabang);
        $kode_pinjaman = 'Pilih Cabang terlebih dahulu';
        if (!empty($kode_cabang)) {
            $this->cabang = $kode_cabang;
            $kode_pinjaman = $this->PegadaianModel->create_kode_pinjaman($this->cabang);
        }
        $this->cabang = $kode_cabang;
        $saldo = (!empty($this->SaldoModel->getSisa($kode_cabang)[0]['sisa_kas']) ? $this->SaldoModel->getSisa($kode_cabang)[0]['sisa_kas'] : '0');
        $data = [
            'gadai' => $this->PegadaianModel->findAll(),
            'nasabah' => $data_nasabah,
            'cabang' => $this->CabangModel->findAll(),
            'saldo' => $this->SaldoModel->findAll(),
            'aturan' => $this->AturanModel->findAll(),
            'barang' => $this->BarangModel->findAll(),
            'kode_cabang' => $kode_cabang,
            'saldo_akhir' => $saldo,
            'title' => 'Form Data Gadai',
            'kode_pinjaman' => $kode_pinjaman,
            'validation' => \Config\Services::validation()
        ];

        return view('Pegadaian/formgadai', $data);
    }

    public function save()
    {

        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $data_nasabah = $this->NasabahModel->getDataNasabah($kode_cabang);
        $this->cabang = $kode_cabang;
        $kode_pinjaman = $this->PegadaianModel->create_kode_pinjaman($this->cabang);

        $jumlah_pinjaman = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('jumlah_pinjaman'));
        $bungaP = $this->request->getVar('bungaP') / 100;
        $bunga = intval($jumlah_pinjaman) * $bungaP;
        $data = [
            'kode_pinjaman' => $kode_pinjaman,
            'id_nasabah' => $this->request->getVar('id_nasabah'),
            'id_barang' => $this->request->getVar('jenis_barang'),
            'seri' => $this->request->getVar('seri'),
            'kelengkapan' => $this->request->getVar('kelengkapan'),
            'password' => $this->request->getVar('password'),
            'jumlah' => $this->request->getVar('jumlah'),
            'kondisi' => $this->request->getVar('kondisi'),
            'tgl_gadai' => $this->request->getVar('tgl_gadai'),
            'tgl_jatuh_tempo' => $this->request->getVar('tgl_jatuh_tempo'),
            'tgl_lelang' => $this->request->getVar('tgl_lelang'),
            'jumlah_pinjaman' =>  $jumlah_pinjaman,
            'bunga' => intval($bunga),
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'status_bayar' => 'Belum Lunas'
        ];
        $this->PegadaianModel->simpan($data);

        $kode_cabang = $this->request->getVar('kode_cabang');
        $get_sisa_kas =  $this->SaldoModel->getSisa($kode_cabang);
        if (!empty($this->SaldoModel->getSisa($kode_cabang))) {
            $sisa_kas = $get_sisa_kas[0]['sisa_kas'];
        } else {
            $sisa_kas = 0;
        }
        $total_kas = $sisa_kas - intval($jumlah_pinjaman);
        $this->SaldoModel->save([
            'jumlah_kas' => $jumlah_pinjaman,
            'sisa_kas' => $total_kas,
            'keterangan' => 'Transaksi Pegadaian Baru dengan kode ' . $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'jenis' => 'keluar'
        ]);

        $this->PendapatanModel->save([
            'jumlah_untung' => $bunga,
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'tgl_masuk' => date('Y-m-d'),
            'jenis' => 'Bunga'
        ]);
        $data = [
            'status'  => 'Berhasil',
            'status_text' => 'Data Berhasil Disimpan',
            'status_icon' => 'success',
            'print_url' => base_url('Pegadaian/cetaknota/' . $kode_pinjaman),
            'redirect_url' => base_url('datagadai')
        ];
        return $this->response->setJSON($data);
    }

    public function edit($kode_pinjaman)
    {
        $data = [
            'gadai'  => $this->PegadaianModel->find($kode_pinjaman),
            'cabang' => $this->CabangModel->findAll(),
            'nasabah' => $this->NasabahModel->findAll(),
            'barang' => $this->BarangModel->findAll(),
            'aturan' => $this->AturanModel->findAll(),
            'title' => 'Form Data Gadai',
            'validation' => \Config\Services::validation()
        ];

        return view('Pegadaian/edit', $data);
    }

    public function update($kode_pinjaman)
    {
        if (!$this->validate([
            'jenis_barang' => [
                'rules' => 'required',
                'errors'    => [
                    'required'  => 'Data Harus Diisi'
                ]
            ],
            'seri' => [
                'rules' => 'required',
                'errors'    => [
                    'required'  => 'Data Harus Diisi'
                ]
            ],
            'kelengkapan' => [
                'rules' => 'required',
                'errors'    => [
                    'required'  => 'Data Harus Diisi'
                ]
            ],
            'jumlah' => [
                'rules' => 'required|numeric',
                'errors'    => [
                    'required'  => 'Data Harus Diisi',
                    'numeric'  => 'Data Hanya Berisi Angka'
                ]
            ],
            'kondisi' => [
                'rules' => 'required',
                'errors'    => [
                    'required'  => 'Data Harus Diisi'
                ]
            ],
        ])) {
            session()->setFlashdata('errors', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $this->PegadaianModel->update($kode_pinjaman, [
            'id_barang' => $this->request->getVar('jenis_barang'),
            'seri' => $this->request->getVar('seri'),
            'kelengkapan' => $this->request->getVar('kelengkapan'),
            'password' => $this->request->getVar('password'),
            'jumlah' => $this->request->getVar('jumlah'),
            'kondisi' => $this->request->getVar('kondisi'),
        ]);
        session()->setFlashdata('Pesan', 'Data Berhasil Diubah');
        return redirect()->to('/datagadai');
    }

    public function delete($kode_pinjaman)
    {
        $jumlah_pinjaman = $this->PegadaianModel->cek_peminjaman($kode_pinjaman);
        if (!empty($jumlah_pinjaman) && !empty($kode_pinjaman)) {
            $cek_cabang_user = session('kode_cabang');
            $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
            $get_sisa_kas =  $this->SaldoModel->getSisa($kode_cabang);
            if (!empty($this->SaldoModel->getSisa($kode_cabang))) {
                $sisa_kas = $get_sisa_kas[0]['sisa_kas'];
            } else {
                $sisa_kas = 0;
            }
            $total_kas = $sisa_kas + intval($jumlah_pinjaman);
            $update_saldo = $this->SaldoModel->save([
                'jumlah_kas' => $jumlah_pinjaman,
                'sisa_kas' => $total_kas,
                'keterangan' => 'Pembatalan Transaksi ' . $kode_pinjaman . '. Dana Sudah dikembalikan ke saldo',
                'kode_cabang' => $kode_cabang,
                'jenis' => 'pembatalan'
            ]);

            // hapus data pendapatn
            $delete = $this->PendapatanModel->Pendapatan_batal($kode_pinjaman);
            $hapus_pinjaman = $this->PegadaianModel->delete($kode_pinjaman);

            if ($delete == true && $hapus_pinjaman == true && $update_saldo == true) {
                $data = [
                    'status'  => 'Berhasil Dihapus',
                    'status_text' => 'Data Berhasil Dihapus',
                    'status_icon' => 'success'
                ];
            }
            return $this->response->setJSON($data);
        }
    }

    public function createDenda($kode_pinjaman)
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;

        $data = [
            'gadai' => $this->PegadaianModel->find($kode_pinjaman),
            'nasabah' => $this->NasabahModel->findAll(),
            'cabang' => $this->CabangModel->findAll(),
            'saldo' => $this->SaldoModel->findAll(),
            'kode_cabang' => $kode_cabang,
            'title' => 'Form Denda',
            'validation' => \Config\Services::validation()
        ];
        return view('Pegadaian/formdenda', $data);
    }

    public function saveDenda()
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;

        $data = [
            'status'  => 'Gagal',
            'status_text' => 'Gagal Update, terjadi Kesalahan!',
            'status_icon' => 'warning',
            'redirect_url' => base_url('datalelang')
        ];
        $jumlah_pinjaman = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('jumlah_pinjaman'));
        $dendaP = $this->request->getVar('dendaP') / 100;
        $bunga = $this->request->getVar('bunga');
        $denda = floatval($bunga) + (intval($jumlah_pinjaman) * $dendaP);
        $save_pendapatan = $this->PendapatanModel->save([
            'jumlah_untung' => $denda,
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'tgl_masuk' => date('Y-m-d'),
            'jenis' => 'denda',
            'keterangan' => $this->request->getVar('keterangan'),
        ]);
        $update_tgl = $this->PegadaianModel->update($this->request->getVar('kode_pinjaman'), [
            'tgl_lelang' => $this->request->getVar('tgl_lelang'),
            'tgl_jatuh_tempo' => $this->request->getVar('tgl_jatuh_tempo')
        ]);
        $simpan_histori = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $denda,
            'jenis' => 'denda',
            'keterangan' => 'Keuntungan Denda'
        ]);

        if ($save_pendapatan == true && $update_tgl == true && $simpan_histori == true) {
            $data = [
                'status'  => 'Berhasil',
                'status_text' => 'Pinjaman telah diAktifkan kembali',
                'status_icon' => 'success',
                'redirect_url' => base_url('datalelang')
            ];
        }
        return $this->response->setJSON($data);
    }

    public function createTebus($kode_pinjaman)
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;

        $data = [
            'gadai' => $this->PegadaianModel->find($kode_pinjaman),
            'nasabah' => $this->NasabahModel->findAll(),
            'cabang' => $this->CabangModel->findAll(),
            'saldo' => $this->SaldoModel->findAll(),
            'kode_cabang' => $kode_cabang,
            'title' => 'Form Penebusan Lelang',
            'validation' => \Config\Services::validation()
        ];
        return view('Lelang/formtebus', $data);
    }

    public function saveTebus()
    {
        $data = [
            'status'  => 'Gagal',
            'status_text' => 'Gagal Update, terjadi Kesalahan!',
            'status_icon' => 'warning',
            'redirect_url' => base_url('datalelang')
        ];
        $jumlah_bayar = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('jumlah_bayar'));
        $dendaP = $this->request->getVar('dendaP') / 100;
        $save_pembayaran = $this->PembayaranModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'jumlah_bayar' => $jumlah_bayar,
            'tgl_bayar' => $this->request->getVar('tgl_bayar'),
            'keterangan' => $this->request->getVar('keterangan')
        ]);
        $kode_cabang = $this->request->getVar('kode_cabang');
        $get_sisa_kas =  $this->SaldoModel->getSisa($kode_cabang);
        if (!empty($this->SaldoModel->getSisa($kode_cabang))) {
            $sisa_kas = $get_sisa_kas[0]['sisa_kas'];
        } else {
            $sisa_kas = 0;
        }
        $total_kas = $sisa_kas + $jumlah_bayar;
        $update_saldo = $this->SaldoModel->save([
            'jumlah_kas' => $jumlah_bayar,
            'sisa_kas' => $total_kas,
            'keterangan' => $this->request->getVar('keterangan'),
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'jenis' => 'pembayaran'
        ]);
        $update_status_bayar = $this->PegadaianModel->update($this->request->getVar('kode_pinjaman'), [
            'status_bayar' => 'Lunas'
        ]);
        $untung = $jumlah_bayar * $dendaP;
        $insert_pendapatan = $this->PendapatanModel->save([
            'jumlah_untung' => $untung,
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'tgl_masuk' => date('Y-m-d'),
            'jenis' => 'denda',
            'keterangan' => $this->request->getVar('keterangan'),
        ]);
        $simpan_histori = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $jumlah_bayar,
            'jenis' => 'penebusan',
            'keterangan' => 'Jumlah Pinjaman'
        ]);
        $simpan_histori2 = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $untung,
            'jenis' => 'denda',
            'keterangan' => 'Keuntungan Tebus'
        ]);
        if ($save_pembayaran == true && $update_saldo == true && $update_status_bayar == true && $simpan_histori == true && $simpan_histori2 == true && $insert_pendapatan == true) {
            $data = [
                'status'  => 'Berhasil',
                'status_text' => 'Barang Berhasil Ditebus!',
                'status_icon' => 'success',
                'redirect_url' => base_url('datalelang')
            ];
        }
        return $this->response->setJSON($data);
    }


    public function createBayar($kode_pinjaman)
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;

        $data = [
            'gadai' => $this->PegadaianModel->find($kode_pinjaman),
            'nasabah' => $this->NasabahModel->findAll(),
            'cabang' => $this->CabangModel->findAll(),
            'saldo' => $this->SaldoModel->findAll(),
            'kode_cabang' => $kode_cabang,
            'title' => 'Form Pembayaran',
            'validation' => \Config\Services::validation()
        ];
        return view('Pegadaian/formbayar', $data);
    }


    public function saveBayar()
    {
        $data = [
            'status'  => 'Gagal',
            'status_text' => 'Gagal Update, terjadi Kesalahan!',
            'status_icon' => 'warning',
            'redirect_url' => base_url('datagadai')
        ];
        $jumlah_bayar = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('jumlah_bayar'));

        $simpan_pembayaran = $this->PembayaranModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'jumlah_bayar' => $jumlah_bayar,
            'tgl_bayar' => $this->request->getVar('tgl_bayar'),
            'keterangan' => $this->request->getVar('keterangan')
        ]);

        $kode_cabang = $this->request->getVar('kode_cabang');

        $simpan_histori = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => $this->request->getVar('tgl_bayar'),
            'dana' => $jumlah_bayar,
            'jenis' => 'penebusan',
            'keterangan' => 'Penebusan Barang'
        ]);

        $get_sisa_kas =  $this->SaldoModel->getSisa($kode_cabang);
        if (!empty($this->SaldoModel->getSisa($kode_cabang))) {
            $sisa_kas = $get_sisa_kas[0]['sisa_kas'];
        } else {
            $sisa_kas = 0;
        }
        $total_kas = $sisa_kas + $jumlah_bayar;
        $update_saldo = $this->SaldoModel->save([
            'jumlah_kas' => $jumlah_bayar,
            'sisa_kas' => $total_kas,
            'keterangan' => $this->request->getVar('keterangan'),
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'jenis' => 'pembayaran'
        ]);

        $update_status_bayar = $this->PegadaianModel->update($this->request->getVar('kode_pinjaman'), [
            'status_bayar' => 'Lunas'
        ]);
        if ($simpan_pembayaran == true && $update_saldo == true && $simpan_histori == true && $update_status_bayar == true) {
            $data = [
                'status'  => 'Berhasil',
                'status_text' => 'Barang Berhasil Ditebus!',
                'status_icon' => 'success',
                'redirect_url' => base_url('datagadai')
            ];
        }
        return $this->response->setJSON($data);
    }

    public function TerLelang()
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $data_lelang = $this->LelangModel->getDataLelang($kode_cabang);
        $data = [
            'title' => 'Data lelang',
            'lelang' => $data_lelang
        ];
        return view('Lelang/terlelang', $data);
    }

    public function AkanLelang()
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;

        $data = [
            'title' => 'Data Lelang',
        ];
        return view('Lelang/datalelang', $data);
    }


    public function createLelang($kode_pinjaman)
    {
        $data = [
            'title' => 'Form Lelang',
            'gadai'  => $this->PegadaianModel->get_detail_pegadaian($kode_pinjaman),
            'validation' => \Config\Services::validation()
        ];
        return view('Lelang/formlelang', $data);
    }

    public function saveLelang()
    {
        $jumlah_pinjaman = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('jumlah_pinjaman'));
        $hasil_lelang = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('hasil_lelang'));
        $keuntungan = $hasil_lelang - $jumlah_pinjaman;
        if ($keuntungan <= 0) {
            $keuntungan = 0;
        } else {
            $keuntungan = $hasil_lelang - $jumlah_pinjaman;
        }

        $data = [
            'id_barang' => $this->request->getVar('nama_barang'),
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'hasil_lelang' => $hasil_lelang,
            'tgl_lelang' => $this->request->getVar('tgl_lelang'),
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'keterangan' => $this->request->getVar('keterangan')
        ];

        $save_lelang = $this->LelangModel->simpan($data);
        $insert_pendapatan = $this->PendapatanModel->save([
            'jumlah_untung' => $keuntungan,
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'tgl_masuk' => date('Y-m-d'),
            'jenis' => 'Lelang',
            'keterangan' => $this->request->getVar('keterangan')
        ]);
        $kode_cabang = $this->request->getVar('kode_cabang');
        $get_sisa_kas =  $this->SaldoModel->getSisa($kode_cabang);
        if (!empty($this->SaldoModel->getSisa($kode_cabang))) {
            $sisa_kas = $get_sisa_kas[0]['sisa_kas'];
        } else {
            $sisa_kas = 0;
        }
        $total_kas = $sisa_kas + $jumlah_pinjaman;
        $update_saldo = $this->SaldoModel->save([
            'jumlah_kas' => $jumlah_pinjaman,
            'sisa_kas' => $total_kas,
            'keterangan' => 'Lelang',
            'kode_cabang' => $this->request->getVar('kode_cabang'),
            'jenis' => 'lelang'
        ]);
        $update_status_bayar = $this->PegadaianModel->update($this->request->getVar('kode_pinjaman'), [
            'status_bayar' => 'TERLELANG'
        ]);
        $simpan_histori = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $keuntungan,
            'jenis' => 'denda',
            'keterangan' => 'Keuntungan Lelang'
        ]);
        $simpan_histori2 = $this->HistoriModel->save([
            'kode_pinjaman' => $this->request->getVar('kode_pinjaman'),
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $jumlah_pinjaman,
            'jenis' => 'penebusan',
            'keterangan' => 'Jumlah Pinjaman'
        ]);
        if ($save_lelang == true && $update_saldo == true && $simpan_histori == true && $simpan_histori2 == true && $update_status_bayar == true && $insert_pendapatan == true) {
            $data = [
                'status'  => 'Berhasil',
                'status_text' => 'Barang Berhasil diLelang!',
                'status_icon' => 'success',
                'redirect_url' => base_url('datalelang')
            ];
        } else {
            $data = [
                'status'  => 'Gagal',
                'status_text' => 'Gagal Update, terjadi Kesalahan!',
                'status_icon' => 'warning',
                'redirect_url' => base_url('datalelang')
            ];
        }
        return $this->response->setJSON($data);
    }

    public function createPerpanjang($kode_pinjaman)
    {
        $data_perpanjangan = $this->PerpanjanganModel->get_jatuh_tempo($kode_pinjaman);
        $data_gadai = $this->PegadaianModel->find($kode_pinjaman);
        $data = [
            'gadai' =>  $data_gadai,
            'perpanjangan' => $this->PerpanjanganModel->findAll(),
            'perpanjang_ini' => (count($data_perpanjangan) > 0 ? $data_perpanjangan[0]['tgl_perpanjangan'] : $data_gadai['tgl_jatuh_tempo']),
            'title' => 'Form Perpanjangan',
            'validation' => \Config\Services::validation()
        ];
        return view('Pegadaian/formperpanjang', $data);
    }

    public function savePerpanjang()
    {
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $kode_pinjaman = $this->request->getVar('kode_pinjaman');
        $save_perpanjang = $this->PerpanjanganModel->save([
            'kode_pinjaman' => $kode_pinjaman,
            'tgl_perpanjangan' => $this->request->getVar('tgl_perpanjangan')
        ]);

        $update_tgl = $this->PegadaianModel->update($kode_pinjaman, [
            'tgl_jatuh_tempo' => $this->request->getVar('tgl_perpanjangan'),
            'tgl_lelang' => $this->request->getVar('tgl_lelang'),
        ]);

        $bunga = preg_replace("/[^a-zA-Z0-9\s]/", "", $this->request->getVar('bunga'));
        $update_pendapatan = $this->PendapatanModel->save([
            'jumlah_untung' => $bunga,
            'kode_pinjaman' => $kode_pinjaman,
            'keterangan' => $this->request->getVar('keterangan'),
            'jenis' => 'bunga'
        ]);

        $simpan_histori = $this->HistoriModel->save([
            'kode_pinjaman' => $kode_pinjaman,
            'kode_cabang' => $kode_cabang,
            'tanggal' => date('Y-m-d'),
            'dana' => $bunga,
            'jenis' => 'perpanjangan',
            'keterangan' => 'Bunga Perpanjangan'
        ]);

        if ($save_perpanjang == true && $update_tgl == true && $simpan_histori == true && $update_pendapatan == true) {
            $data = [
                'status'  => 'Berhasil',
                'status_text' => 'Berhasil Diperpanjang!',
                'status_icon' => 'success',
                'redirect_url' => base_url('datagadai')
            ];
        } else {
            $data = [
                'status'  => 'Gagal',
                'status_text' => 'Gagal Update, terjadi Kesalahan!',
                'status_icon' => 'warning',
                'redirect_url' => base_url('datagadai')
            ];
        }
        return $this->response->setJSON($data);
    }


    public function list()
    {
        helper('bulan');
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        if (
            isset($_GET["columns"][1]["search"]["value"]) && $_GET["columns"][1]["search"]["value"] != ""
        ) {
            $temp = explode("|", $_GET["columns"][1]["search"]["value"]);
            $_GET['tanggal_start'] = (isset($temp[1]) ? $temp[0] : date("Y-m-d", 0));
            $_GET['tanggal_end'] = (isset($temp[1]) ? $temp[1] : date("Y-m-d"));
        }
        $tgl_besok = date('Y-m-d', strtotime("+1 day"));
        $query = $this->request->getGet('search')["value"];
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $date_range_type = strtotime($query);

        $type_data = (!empty($this->request->getGet('type_data'))) ? $this->request->getGet('type_data') : '';
        $keySearch = 'kode_pinjaman';
        if (!empty($query) && $date_range_type == false) {
            $extract = explode("_", $query);
            $query = $extract[0];
            $keySearch = $extract[1];
            if (count($extract) >= 3) {
                $keySearch = $extract[1] . '_' . $extract[2];
            }
        } else {
            $keySearch = 'tgl_gadai';
        }
        $result['sEcho'] = intval($this->request->getGet('sEcho'));
        $result['iTotalRecords'] = $this->PegadaianModel->countResultTable($kode_cabang, $type_data);
        $result['iTotalDisplayRecords'] = $this->PegadaianModel->count_filter($query, $kode_cabang, $type_data, $keySearch);
        if ($length == -1) $length = $result['iTotalDisplayRecords'];
        $data_gadai = $this->PegadaianModel->listDataGadai($start, $length, $query, $keySearch, $kode_cabang, $type_data);
        //print_r($data_gadai);
        $i = $start + 1;
        foreach ($data_gadai as $key) {
            $key->no = $i;
            $key->kode_pinjaman = $key->kode_pinjaman;
            $key->tgl_gadai = bulan($key->tgl_gadai);
            $key->update_url = base_url() . '/pegadaian/edit/' . $key->kode_pinjaman;
            $key->delete_url = base_url() . '/pegadaian/delete/' . $key->kode_pinjaman;
            $key->pembayaran_url = base_url() . '/pegadaian/createBayar/' . $key->kode_pinjaman;
            $key->penebusan_url = base_url() . '/Pegadaian/createTebus/' . $key->kode_pinjaman;
            $key->perpanjangan_url = base_url() . '/pegadaian/createPerpanjang/' . $key->kode_pinjaman;
            $key->denda_url = base_url() . '/pegadaian/createDenda/' . $key->kode_pinjaman;
            $key->lelang_url = base_url() . '/pegadaian/createLelang/' . $key->kode_pinjaman;
            $key->urlNota = base_url() . '/Pegadaian/cetaknota/' . $key->kode_pinjaman;

            $key->sudah_jatuh_tempo = ($key->tgl_jatuh_tempo < date('Y-m-d')) ? true : false;
            $key->sudah_harus_lelang = ($key->tgl_lelang < date('Y-m-d')) ? true : false;
            $key->sudah_bisa_lelang = ($key->tgl_lelang == date('Y-m-d')) ? true : false;
            $key->jatuh_tempo_hari_ini = ($key->tgl_jatuh_tempo == date('Y-m-d')) ? true : false;
            $key->jatuh_tempo_besok = ($key->tgl_jatuh_tempo == $tgl_besok) ? true : false;
            $key->mark_status = ($key->jatuh_tempo_hari_ini == true || $key->jatuh_tempo_besok == true) ? true : false;
            $key->tgl_jatuh_tempo = bulan($key->tgl_jatuh_tempo);
            $key->tgl_lelang = bulan($key->tgl_lelang);
            $key->jumlah_pinjaman = rupiah($key->jumlah_pinjaman);
            $key->bunga = rupiah($key->bunga);
            $i++;
        }
        $result['aaData'] = $data_gadai;
        echo json_encode($result);
    }

    public function cetaknota($kode_pinjaman)
    {
        helper('bulan');
        $cek_cabang_user = session('kode_cabang');
        $kode_cabang = (!empty($_GET['kode_cabang'])) ? $_GET['kode_cabang'] : $cek_cabang_user;
        $data = [
            'data_cabang' => $this->CabangModel->infoCabang($kode_cabang),
            'data_nota' => $this->PegadaianModel->get_printDataGadai($kode_pinjaman)
        ];
        return view('nota', $data);
    }

    // import proses
    public function cek_kategori($kategori)
    {
        $id_categori = 0;
        switch ($kategori) {
            case 'LAPTOP':
                $id_categori = 1;
                break;
            case 'HP':
                $id_categori = 2;
                break;
            case 'MOTOR':
                $id_categori = 3;
                break;
            case 'ELEKTRONIK':
                $id_categori = 4;
                break;
            default:
                $id_categori = 0;
                break;
        }
        return $id_categori;
    }
}

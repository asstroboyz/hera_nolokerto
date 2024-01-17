<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanModel extends Model
{
    protected $table = 'pengadaan_barang';
    // protected $useTimestamps = true;
    protected $primarykey = 'id';
    protected $allowedFields = ['id', 'id_user', 'id_balasan_pengadaan', 'nama_pengaju', 'nama_barang', 'spesifikasi', 'jumlah', 'tahun_periode', 'alasan_pengadaan', 'jumlah_disetujui','catatan','tgl_pengajuan', 'tgl_proses', 'tgl_selesai', 'status', 'status_akhir'];

    public function getPengadaan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id' => $id])->first();
    }
    public function getAll()
    {
        $query = $this->table('pengadaan_barang')->query('select * from pengadaan_barang');
        return $query->getResult();
    }
}

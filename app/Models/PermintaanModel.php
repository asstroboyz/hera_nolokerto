<?php

namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    protected $table = 'permintaan_barang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'kode_barang', 'id_balasan_permintaan', 'nama_pengaju', 'perihal', 'detail', 'tanggal_pengajuan', 'tanggal_diproses','jumlah', 'tanggal_selesai', 'status', 'status_akhir'];

    public function getPermintaan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }
    public function find($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }

        return $this->asArray()
            ->where(['kode_barang' => $id])
            ->first();
    }

    public function getAll()
    {
        $query = $this->table('permintaan_barang')->query('select * from permintaan_barang');
        return $query->getResult();
    }
    public function getPermintaanWithBarang()
    {
        return $this->db->table('permintaan_barang')
            ->join('barang', 'barang.kode_barang = permintaan_barang.kode_barang')
            ->get()
            ->getResultArray();
    }
}

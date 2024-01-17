<?php

namespace App\Models;

use CodeIgniter\Model;

class InventarisModel extends Model
{
    protected $table = 'inventaris';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'kode_barang', 'nama_barang', 'kondisi', 'merk', 'tipe', 'satuan_barang', 'jumlah_barang', 'tgl_perolehan', 'qrcode', 'file', 'created_at', 'updated_at', 'deleted_at'];
    protected $db;

    // public function __construct()
    // {
    //     $this->db      = \Config\Database::connect();
    // }

    public function getDataByDateRange($tanggalMulai, $tanggalAkhir)
    {
        // Menggunakan Query Builder untuk operasi select
        $query = $this->db->table('inventaris')
            ->where('tgl_perolehan >=', $tanggalMulai)
            ->where('tgl_perolehan <=', $tanggalAkhir)
            ->get();

        // Mengembalikan hasil query sebagai array
        return $query->getResultArray();
    }
    public function getInventaris($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }

        return $this->where(['id' => $id])->first();
    }
    public function getInven()
    {
        $hasil = $this->db()->get('inventaris');
        return $hasil;
    }
    // public function save_inventaris()
    // {
    //     $data = array(
    //         'kode_barang' => $kode_barang,
    //         'nama_barang' => $nama_barang,
    //         'kondisi' => $kondisi,
    //         'merk' => $merk,
    //         'tipe' => $tipe,
    //         'satuan_barang' => $satuan_barang,
    //         'jumlah_barang' => $jumlah_barang,
    //         'tgl_perolehan' => $tgl_perolehan,
    //         'qrcode' => $qr,
    //     );
    //     $this->insert($data);
    // }

    //malam ini
    public function fetch_datas()
    {
        return $this->findAll();
    }

    public function fetch_data($id)
    {
        $this->db->where('id', $id);

        $query = $this->db->get($this->inventaris);

        return $query->row_array();
    }

    //build awal
    // function insert_data($qr)
    // {
    //     $this->insert($qr);

    //     return $this->db->affectedRows();
    // }

    public function insert_data($data)
    {
        $this->insert($data);

        return $this->db->affectedRows();
    }
    public function update_data($id, $data)
    {
        $this->update($id, $data);

        return $this->db->affectedRows();
    }
}

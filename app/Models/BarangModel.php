<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $useSoftDeletes = true;
    protected $primaryKey = 'kode_barang'; // Menggunakan 'kode_barang' sebagai primary key
    protected $allowedFields = ['kode_barang', 'nama_barang', 'satuan_barang', 'jenis_barang', 'jenis_transaksi', 'tanggal_barang_masuk', 'tanggal_barang_keluar', 'deleted_at', 'stok'];

    public function getBarang($kode_barang = false)
    {
        if ($kode_barang == false) {
            return $this->findAll();
        }

        return $this->where(['kode_barang' => $kode_barang])->first();
    }

    // public function softDeleteWithRelations($kode_barang)
    // {
    //     // Hapus soft delete di tabel Barang
    //     $this->delete($kode_barang);

    //     // Hapus record di tabel TransaksiBarang terkait
    //     $transaksiModel = new TransaksiBarangModel();
    //     $transaksiModel->where('kode_barang', $kode_barang)->delete();
    // }
    public function softDeleteWithRelations($kode_barang)
    {
        // Hapus soft delete di tabel Barang
        $this->delete($kode_barang);

        // Hapus record di tabel TransaksiBarang terkait
        $transaksiModel = new TransaksiBarangModel();
        $transaksiModel->where('kode_barang', $kode_barang)->delete();
    }

    public function transaksi()
    {
        return $this->hasMany('App\Models\TransaksiModel', 'kode_barang', 'kode_barang');
    }

    public function tambahStok($kodeBarang, $jumlah, $tanggalMasuk)
    {
        if ($this->where('kode_barang', $kodeBarang)->set('stok', "stok + $jumlah", false)->update()) {
            return true;
        } else {
            return false;
        }
    }

    public function kurangiStok($kodeBarang, $jumlah)
    {
        $builder = $this->db->table($this->table);
        $builder->set('stok', "stok - $jumlah", false);
        $builder->where('kode_barang', $kodeBarang);
        $builder->update();
    }
    public function getBarangMasuk()
    {
        return $this->where('jumlah_penambahan_stok >', 0)->findAll();
    }

    public function getBarangKeluar()
    {
        return $this->where('jumlah_pengurangan_stok >', 0)->findAll();
    }

    public function deleteBarang($kode_barang)
    {
        // Hapus barang dan transaksi yang terkait
        $barang = $this->find($kode_barang);

        if (!$barang) {
            return false; // Barang tidak ditemukan
        }

        // Hapus transaksi yang terkait
        $TransaksiBarangModel = new TransaksiBarangModel();
        $TransaksiBarangModel->where('kode_barang', $kode_barang)->delete();

        // Hapus barang
        return $this->delete($kode_barang);
    }
}

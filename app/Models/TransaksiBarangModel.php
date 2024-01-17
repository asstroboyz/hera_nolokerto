<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiBarangModel extends Model
{
    protected $table = 'transaksi_barang';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['kode_barang', 'nama_barang', 'jenis_barang', 'tanggal_barang_masuk', 'stok', 'jenis_transaksi', 'informasi_tambahan', 'jumlah_perubahan', 'jenis_perubahan', 'tanggal_barang_keluar'];

    // Fungsi untuk menambahkan transaksi barang masuk
    public function tambahBarangMasuk($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk menambahkan transaksi barang keluar
    public function tambahBarangKeluar($data)
    {
        return $this->insert($data);
    }

    // Fungsi untuk mendapatkan riwayat transaksi berdasarkan kode_barang
    public function riwayatTransaksi($kode_barang)
    {
        return $this->where('kode_barang', $kode_barang)->findAll();
    }

    // Tambahan fungsi atau logika lainnya sesuai kebutuhan
}

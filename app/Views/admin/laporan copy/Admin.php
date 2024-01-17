<?php

namespace App\Controllers;

use App\Libraries\Ciqrcode;
use App\Models\BalasanModel;
use App\Models\BarangModel;
use App\Models\InventarisModel;
use App\Models\PermintaanModel;
use App\Models\TransaksiBarangModel;
use Kenjis\CI3Compatible\Core\CI_Input;

/**
 * @property Home_model $home_model
 * @property Ciqrcode $ciqrcode
 * @property CI_Input $input
 */

class Admin extends BaseController
{
    protected $db;
    protected $builder;
    protected $BarangModel;
    protected $TransaksiBarangModel;
    public function __construct()
    {
        $this->InventarisModel = new InventarisModel();
        $this->PermintaanModel = new PermintaanModel();
        $this->BalasanModel = new BalasanModel();
        $this->BarangModel = new BarangModel();
        $this->TransaksiBarangModel = new TransaksiBarangModel();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->ciqrcode = new \App\Libraries\Ciqrcode();
    }

    public function index()
    {
        $userlogin = user()->id;
        $data = $this->db->table('inventaris');
        $query1 = $data->get()->getResult();

        $semua = count($query1);

        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Home',
            'semua' => $semua,

        ];
        return view('admin/index', $data);
    }
    public function user_list()
    {
        $data['title'] = 'User List';
        // $users = new \Myth\Auth\Models\UserModel();
        // $data['users']  = $users->findAll();

        //join tabel memanggil fungsi
        $this->builder->select('users.id as userid, username, email, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $query = $this->builder->get();

        $data['users'] = $query->getResult();
        return view('admin/user_list', $data);
    }

    public function detail($id = 0)
    {
        $data['title'] = 'User Detail';

        $this->builder->select('users.id as userid, username, email, foto, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $query = $this->builder->get();

        $data['user'] = $query->getRow();

        if (empty($data['user'])) {
            return redirect()->to('/admin');
        }

        return view('admin/detail', $data);
    }

    public function profil()
    {
        $data['title'] = 'User Profile ';
        return view('admin/profil', $data);
    }

    //Inventaris
    public function adm_inventaris()
    {
        // Ambil pesan modal dari flashdata
        $modal_message = session()->getFlashdata('modal_message');

        // Kirim pesan modal ke tampilan
        $data['modal_message'] = $modal_message;
        $this->builder = $this->db->table('inventaris');
        $this->builder->select('*');
        $this->query = $this->builder->get();
        $data['inventaris'] = $this->query->getResultArray();
        // dd(  $data['inventaris']);
        $data['title'] = 'inventaris';
        return view('admin/inventaris/index', $data);
    }

    public function tambah_inv()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Barang Inventaris',
        ];

        return view('admin/inventaris/tambah_barang', $data);
    }
    public function generate_qrcode($data_array, $existing_qrcode = null)
    {
        /* Load QR Code Library */
        // $this->load->library('ciqrcode');
        if (!isset($data_array['kode_barang']) || !isset($data_array['nama_barang'])) {
            // Tambahkan penanganan error atau kembalikan pesan kesalahan sesuai kebutuhan
            return ['error' => 'Kode barang atau nama barang tidak ditemukan dalam data'];
        }

        $combined_data = implode(',', $data_array);

        /* Data */
        // $hex_data   = bin2hex($combined_data);
        // $save_name  = $hex_data . '.png';

        /* Generate Slug from Nama Barang */
        $slug = url_title($data_array['kode_barang'] . '-' . $data_array['nama_barang'], '-', true);
        /* Generate Unique Time Stamp */
        $time_stamp = time();

        /* Combine Slug and Time Stamp for Unique Barcode */
        $unique_barcode = $slug . '_' . $time_stamp;

        /* QR Code File Directory Initialize */
        $dir = 'assets/media/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = [255, 255, 255];
        $config['white'] = [255, 255, 255];

        $this->ciqrcode->initialize($config);

        /* QR Data  */
        $params['data'] = $combined_data;
        $params['level'] = 'L';
        $params['size'] = 10;

        // Jika QR Code lama diberikan, hapus file QR Code lama
        if ($existing_qrcode) {
            unlink($existing_qrcode);
        }

        if (!$existing_qrcode) {
            // Jika tidak ada QR Code lama, atur nama baru
            $params['savename'] = FCPATH . $config['imagedir'] . $unique_barcode . '.png';
        } else {
            // Jika ada QR Code lama, gunakan nama yang sama
            $params['savename'] = $existing_qrcode;
        }
        // $params['savename'] = FCPATH . $config['imagedir'] . $unique_barcode . '.png';

        // $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->ciqrcode->generate($params);

        // Add logo to QR code
        $this->add_logo_to_qr_code($params['savename'], 'assets/media/qrcode/bps.png');
        /* Return Data */
        return [
            'unique_barcode' => $unique_barcode,
            'file' => $dir . $unique_barcode . '.png',
        ];
        return $qrcode;
    }

    public function add_logo_to_qr_code($qr_code_path, $logo_path, $logo_size_percent = 20, $logo_transparency = 100)
    {
        // Load QR code image
        $qr_code = imagecreatefrompng($qr_code_path);
        $logo = imagecreatefrompng($logo_path);

        // Calculate position for logo in the center of QR code
        $qr_code_width = imagesx($qr_code);
        $qr_code_height = imagesy($qr_code);
        $logo_width = imagesx($logo);
        $logo_height = imagesy($logo);

        // Hitung ukuran logo yang diubah
        $new_logo_width = ($qr_code_width * $logo_size_percent) / 100;
        $new_logo_height = ($new_logo_width / $logo_width) * $logo_height;

        // Tempatkan logo di tengah QR code
        $x = ($qr_code_width - $new_logo_width) / 2;
        $y = ($qr_code_height - $new_logo_height) / 2;

        // Ubah ukuran logo
        $resized_logo = imagecreatetruecolor($new_logo_width, $new_logo_height);
        imagecopyresampled($resized_logo, $logo, 0, 0, 0, 0, $new_logo_width, $new_logo_height, $logo_width, $logo_height);

        // Tambahkan logo yang diubah ke dalam QR code dengan transparansi
        imagecopymerge($qr_code, $resized_logo, $x, $y, 0, 0, $new_logo_width, $new_logo_height, $logo_transparency);

        // Simpan hasilnya
        imagepng($qr_code, $qr_code_path);
    }

    public function add_data()
    {
        // /* Generate QR Code */
        // $data = $this->request->getPost();

        // // Generate QR Code
        // $qr_data = [
        //     'kode_barang'    => $data['kode_barang'],
        //     'nama_barang'    => $data['nama_barang'],
        //     'kondisi'        => $data['kondisi'],
        //     'merk'            => $data['merk'],
        //     'tipe'            => $data['tipe'],
        //     'satuan_barang'  => $data['satuan_barang'],
        //     'jumlah_barang'  => $data['jumlah_barang'],
        //     'tgl_perolehan'  => $data['tgl_perolehan'],
        // ];

        // $qrcode_result = $this->generate_qrcode($qr_data);

        // // Combine QR Code with other form data
        // $data['qrcode'] = $qrcode_result['unique_barcode']; // Ganti dengan 'unique_barcode'

        // // Add 'file' to $data
        // $data['file'] = $qrcode_result['file'];

        // // Add Data
        // if ($this->InventarisModel->insert_data($data)) {
        //     $this->modal_feedback(
        //         'success',
        //         'Success',
        //         'Add Data Success',
        //         'OK'
        //     );
        // } else {
        //     $this->modal_feedback('error', 'Error', 'Add Data Failed', 'Try again');
        // }

        // // Retrieve and return updated data to the view
        // $data['inventaris'] = $this->InventarisModel->fetch_datas();
        // $data['title'] = 'inventaris';
        $data = $this->request->getPost();

        // Generate QR Code
        $qr_data = [
            'kode_barang' => $data['kode_barang'],
            'nama_barang' => $data['nama_barang'],
            'kondisi' => $data['kondisi'],
            'merk' => $data['merk'],
            'tipe' => $data['tipe'],
            'satuan_barang' => $data['satuan_barang'],
            'jumlah_barang' => $data['jumlah_barang'],
            'tgl_perolehan' => $data['tgl_perolehan'],
        ];

        $qrcode_result = $this->generate_qrcode($qr_data);

        // Combine QR Code with other form data
        $data['qrcode'] = $qrcode_result['unique_barcode'];
        $data['file'] = $qrcode_result['file'];

        // Add Data
        if ($this->InventarisModel->insert_data($data)) {
            session()->setFlashdata('PesanBerhasil', 'Penambahan Data Berhasil');
        } else {
            session()->setFlashdata('PesanGagal', 'Penambahan Data Gagal. Coba lagi');
        }

        // Retrieve and return updated data to the view
        $data['inventaris'] = $this->InventarisModel->fetch_datas();
        $data['title'] = 'inventaris';

        return view('admin/inventaris/index', $data);
    }

    public function ubah($id = 0)
    {

        session();
        $data = [
            'title' => "BPS Ubah Data",
            'validation' => \Config\Services::validation(),
            'inventaris' => $this->InventarisModel->getInventaris($id),
        ];
        // dd($data);
        return view('admin/inventaris/edit_barang', $data);
    }

    public function update($id)
    {

        // Mengambil data inventaris yang sudah ada
        $existingData = $this->InventarisModel->getInventaris($id);

        if (!empty($existingData['qrcode'])) {
            $this->delete_qrcode($existingData['qrcode']);
        }
        // Generate new QR Code
        $qr_data = [
            'kode_barang' => $this->request->getVar('kode_barang'),
            'nama_barang' => $this->request->getVar('nama_barang'),
            'kondisi' => $this->request->getVar('kondisi'),
            'merk' => $this->request->getVar('merk'),
            'tipe' => $this->request->getVar('tipe'),
            'satuan_barang' => $this->request->getVar('satuan_barang'),
            'jumlah_barang' => $this->request->getVar('jumlah_barang'),
            'tgl_perolehan' => $this->request->getVar('tgl_perolehan'),
        ];

        // Generate new QR Code
        $newQrCode = $this->generate_qrcode($qr_data);

        // Mengupdate data inventaris
        $this->InventarisModel->update_data($id, [
            'kode_barang' => $this->request->getVar('kode_barang'),
            'nama_barang' => $this->request->getVar('nama_barang'),
            'kondisi' => $this->request->getVar('kondisi'),
            'merk' => $this->request->getVar('merk'),
            'tipe' => $this->request->getVar('tipe'),
            'satuan_barang' => $this->request->getVar('satuan_barang'),
            'jumlah_barang' => $this->request->getVar('jumlah_barang'),
            'tgl_perolehan' => $this->request->getVar('tgl_perolehan'),
            'qrcode' => $newQrCode['unique_barcode'], // Sesuaikan dengan kembalian generate_qrcode
            'file' => $newQrCode['file'],
        ]);

        // Flashdata pesan disimpan
        session()->setFlashdata(
            'pesanBerhasil',
            'Data Berhasil Diubah'
        );

        // Redirect ke halaman index
        return redirect()->to('/inventaris/index');
    }

    public function detail_inv($id)
    {
        $data['title'] = 'Detail'; // Pindahkan ini ke atas agar tidak terjadi override
        $this->builder = $this->db->table('inventaris'); // Gunakan $this->builder untuk mengakses builder

        $this->builder->select('*');
        $this->builder->where('id', $id);
        $query = $this->builder->get();
        $data['inventaris'] = $query->getRow();

        if (empty($data['inventaris'])) {
            return redirect()->to('/admin/detailinv');
        }

        return view('admin/inventaris/detail_inv', $data);
    }
    protected function delete_qrcode($unique_barcode)
    {
        $qrcode_path = 'assets/media/qrcode/' . $unique_barcode . '.png';

        // Hapus QR Code jika ada
        if (file_exists($qrcode_path)) {
            unlink($qrcode_path);
        }
    }

    public function delete($id)
    {
        // Get data before deletion for unlinking the file
        $inventaris = $this->InventarisModel->getInventaris($id);

        // Unlink file
        $fileLocation = FCPATH . $inventaris['file'];
        if (file_exists($fileLocation)) {
            unlink($fileLocation);
        }

        $this->InventarisModel->delete($id);

        // Set flashdata berdasarkan status penghapusan
        $flashdataKey = ($this->db->affectedRows() > 0) ? 'PesanBerhasil' : 'PesanGagal';
        $flashdataMessage = ($this->db->affectedRows() > 0) ? 'Data Anda Berhasil Dihapus' : 'Gagal Menghapus Data';

        session()->setFlashdata($flashdataKey, $flashdataMessage);

        return redirect()->to('inventaris');
    }

    //Akhir Inventaris

    //ATK
    public function atk()
    {
        $data = [
            'title' => 'Tambah Permintaan',
            'barangs' => $this->BarangModel->findAll(),
        ];

        return view('admin/barang/index', $data);
    }
    public function tambahForm()
    {
        // Tampilkan form tambah stok
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Barang ',
        ];

        return view('admin/barang/tambah_barang', $data);
    }

    public function tambah()
    {
        // Validasi input form tambah barang
        $this->validation->setRules([
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            return redirect()->to('/admin/tambah')->withInput()->with('validation', $this->validation);
        }

        // Simpan data barang ke database
        $data = [

            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_barang' => $this->request->getPost('jenis_barang'),
            'satuan_barang' => $this->request->getPost('satuan_barang'),
            'stok' => $this->request->getPost('stok'),
        ];
        // Generate dan tambahkan kode_barang ke dalam data

        $this->BarangModel->save($data, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_barang' => $this->request->getPost('jenis_barang'),
            'satuan_barang' => $this->request->getPost('satuan_barang'),
            'stok' => $this->request->getPost('stok'),
        ]);

        // Tampilkan pesan sukses atau error
        session()->setFlashdata('msg', 'Data barang berhasil ditambahkan.');
        return redirect()->to('/admin/atk');
    }

    public function barangMasuk()
    {
        $barangModel = new BarangModel();

        // Ambil barang-barang yang baru masuk
        $barangMasuk = $barangModel->getBarangMasuk();

        // Kirim data ke view
        $data['title'] = 'Riawayat Stok ';
        $data = [
            'barangMasuk' => $barangMasuk,
            'title' => 'Barang',
        ];

        return view('admin/barang/barang_masuk', $data);
    }

    public function barangKeluar()
    {
        $barangModel = new BarangModel();

        // Ambil barang-barang yang baru keluar
        $barangKeluar = $barangModel->getBarangKeluar();

        // Kirim data ke view
        $data = [
            'barangKeluar' => $barangKeluar,
        ];

        return view('admin/riwayat_stok/barang_keluar', $data);
    }
    public function formTambahStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->to('/admin/atk')->with('error-msg', 'Barang tidak ditemukan.');
        }

        $data = [
            'barang' => $barang,
            'kode_barang' => $kodeBarang,
            'stok' => $barang['stok'],
            'validation' => $this->validation,
            'title' => 'Tambah Stok',
        ];

        return view('admin/barang/tambah_stok', $data);
    }

    public function tambahStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();

        // Mendapatkan data barang
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            // Tampilkan pesan kesalahan atau redirect ke halaman lain jika perlu
            return redirect()->to('/admin')->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Mendapatkan data dari form
        $jumlahPenambahanStok = (int) $this->request->getPost('jumlah_penambahan_stok');
        $tanggalBarangMasuk = $this->request->getPost('tanggal_barang_masuk');
        $namaBarang = $barang['nama_barang']; // Menggunakan nama_barang dari data barang
        $jenisBarang = $barang['jenis_barang']; // Menggunakan jenis_barang dari data barang
        $stok = $barang['stok']; // Menggunakan jenis_barang dari data barang
        $stokBaru = $barang['stok'] + $jumlahPenambahanStok;

        // Update stok pada tabel barang
        $barangModel->update($barang['kode_barang'], [
            'stok' => $stokBaru,
        ]);

        // Masukkan data ke tabel transaksi_barang
        $TransaksiBarangModel->insert([
            'kode_barang' => $kodeBarang,
            'nama_barang' => $namaBarang,
            'jenis_barang' => $jenisBarang,
            'stok' => $stok,
            'tanggal_barang_masuk' => $tanggalBarangMasuk,
            'jumlah_perubahan' => $jumlahPenambahanStok,
            'jenis_transaksi' => 'masuk',
            'informasi_tambahan' => 'Penambahan stok melalui form tambah stok.',
            'tanggal_perubahan' => $tanggalBarangMasuk,
        ]);

        // Set pesan sukses dan redirect
        session()->setFlashdata('msg', 'Stok barang berhasil ditambahkan.');
        return redirect()->to('/admin/atk');
    }

    public function formKurangStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        // Pastikan barang ditemukan sebelum melanjutkan
        if (!$barang) {
            // Tampilkan pesan kesalahan atau redirect ke halaman lain jika perlu
            return redirect()->to('/admin/atk')->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Kirim data ke view, termasuk nilai stok
        $data = [
            'barang' => $barang,
            'kodeBarang' => $kodeBarang,
            'stok' => $barang['stok'], // Inisialisasi variabel stok
            'validation' => $this->validation,
            'title' => 'Kurang Barang',
        ];

        return view('admin/barang/kurang_stok', $data);
    }

    public function kurangiStok($kodeBarang)
    {
        $barangModel = new BarangModel();
        $TransaksiBarangModel = new TransaksiBarangModel();

        // Mendapatkan data barang
        $barang = $barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            // Tampilkan pesan kesalahan atau redirect ke halaman lain jika perlu
            return redirect()->to('/admin/atk')->with('error-msg', 'Barang tidak ditemukan.');
        }

        // Mendapatkan data dari form
        $jumlahPenguranganStok = (int) $this->request->getPost('jumlah_pengurangan_stok');
        $tanggalBarangKeluar = $this->request->getPost('tanggal_barang_keluar');
        $namaBarang = $barang['nama_barang']; // Menggunakan nama_barang dari data barang
        $jenisBarang = $barang['jenis_barang']; // Menggunakan jenis_barang dari data barang
        $stok = $barang['stok']; // Menggunakan jenis_barang dari data barang
        $stokBaru = max(0, $stok - $jumlahPenguranganStok);

        // Update stok pada tabel barang
        $barangModel->update($barang['kode_barang'], [
            'stok' => $stokBaru,
        ]);

        // Masukkan data ke tabel transaksi_barang
        $TransaksiBarangModel->insert([
            'kode_barang' => $kodeBarang,
            'nama_barang' => $namaBarang,
            'jenis_barang' => $jenisBarang,
            'stok' => $stok,
            'tanggal_barang_keluar' => $tanggalBarangKeluar,
            'jumlah_perubahan' => $jumlahPenguranganStok,
            'jenis_transaksi' => 'keluar',
            'informasi_tambahan' => 'Pengurangan stok melalui form kurang stok.',
            'tanggal_perubahan' => $tanggalBarangKeluar,
        ]);

        // Set pesan sukses dan redirect
        session()->setFlashdata('msg', 'Stok barang berhasil dikurangi.');
        return redirect()->to('/admin/atk');
    }

    public function trans_masuk()
    {
        $this->builder = $this->db->table('transaksi_barang');
        $this->builder->select('*');
        $this->builder->where('jenis_transaksi', 'masuk');
        $this->query = $this->builder->get();
        $data = [
            'transaksi_barang' => $this->query->getResultArray(),
            'title' => 'Daftar Transaksi Barang Masuk',
        ];

        return view('admin/barang/barang_masuk', $data);
    }
    public function trans_keluar()
    {
        $this->builder = $this->db->table('transaksi_barang');
        $this->builder->select('*');
        $this->builder->where('jenis_transaksi', 'keluar');
        $this->query = $this->builder->get();
        $data = [
            'transaksi_barang' => $this->query->getResultArray(),
            'title' => 'Daftar Transaksi Barang Keluar',
        ];

        return view('admin/barang/barang_keluar', $data);
    }
    //Akhir ATK

    // Permintaan Barang
    public function permintaan()
    {

        $this->builder = $this->db->table('permintaan_barang');
        $this->builder->select('*');
        $this->query = $this->builder->get();
        $data['permintaan'] = $this->query->getResultArray();
        // dd(  $data['inventaris']);
        $data['title'] = 'Permintaan Barang';
        return view('admin/permintaan_barang/index', $data);
    }

    public function permintaan_masuk()
    {
        $this->builder = $this->db->table('permintaan_barang');
        $this->builder->select('*');
        $this->builder->where('status', 'belum diproses');
        $this->query = $this->builder->get();
        $data = [
            'permintaan' => $this->query->getResultArray(),
            'title' => 'Daftar permintaan - Masuk',
        ];
        return view('admin/permintaan_barang/permintaan_masuk', $data);
    }
    public function permintaan_proses()
    {
        $this->builder = $this->db->table('permintaan_barang');
        $this->builder->select('*');
        $this->builder->where('status', 'diproses');
        $this->query = $this->builder->get();
        $data = [
            'permintaan' => $this->query->getResultArray(),
            'title' => 'Daftar permintaan - Masuk',
        ];
        return view('admin/permintaan_barang/permintaan_masuk', $data);

    }
    public function permintaan_selesai()
    {
        $this->builder = $this->db->table('permintaan_barang');
        $this->builder->select('*');
        $this->builder->where('status', 'selesai');
        $this->query = $this->builder->get();
        $data = [
            'permintaan' => $this->query->getResultArray(),
            'title' => 'Daftar permintaan - Masuk',
        ];
        return view('admin/permintaan_barang/permintaan_masuk', $data);

    }
    public function prosesPermintaan($id)
    {
        $date =
        $this->PermintaanModel->update($id, [
            'tanggal_diproses' => date("Y-m-d h:i:s"),
            'status' => 'diproses',

        ]);
        session()->setFlashdata('msg', 'Status permintaan berhasil Diubah');
        return redirect()->to('admin/detailpermin/' . $id);
    }

    public function terimaPermintaan($id)
    {

        $this->PermintaanModel->update($id, [
            'tanggal_selesai' => date("Y-m-d h:i:s"),
            'status' => 'selesai',
            'status_akhir' => 'diterima',

        ]);
        session()->setFlashdata('msg', 'Status permntaan berhasil Diubah');
        return redirect()->to('admin/detailpermin/' . $id);
    }
    public function simpanBalasan($id)
    {
        $rules = [
            'kategori' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kategori  wajib diisi.',
                ],
            ],
            'balasan_permintaan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Isi Balasan wajib diisi.',

                ],
            ],

        ];

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            return redirect()->to('/admin/detail/' . $id)->withInput('validation', $validation);
        }
        $this->PermintaanModel->update($id, [
            'tanggal_selesai' => date("Y-m-d h:i:s"),
            'status' => 'selesai',
            'status_akhir' => 'ditolak',

        ]);
        $data = [
            'id_permintaan_barang' => $id,
            'kategori' => $this->request->getPost('kategori'),
            'balasan_permintaan' => $this->request->getPost('balasan_permintaan'),

        ];
        $this->BalasanModel->save($data);
        session()->setFlashdata('msg', 'Status Pengaduan berhasil Diubah');
        return redirect()->to('admin/detailpermin/' . $id);
    }
    public function detailpermin($id)
    {

        $data = $this->db->table('permintaan_barang');
        $data->select('*');
        $data->where('id', $id);
        $query = $data->get();

        $d = $this->db->table('balasan_permintaan');
        $d->select('*');
        $d->where('id_permintaan_barang', $id);
        $balasan = $d->get()->getRow();

        // dd($query1);
        $ex = [

            'detail' => $query->getRow(),
            'title' => 'Detail permintaan',
            'balasan' => $balasan,
            'validation' => \Config\Services::validation(),

        ];

        return view('admin/permintaan_barang/detail_permintaan', $ex);
    }
    public function print() // all data

    {
        $data = [
            'permintaan_barang' => $this->PermintaanModel->getAll(),
            'title' => 'Cetak Data',
        ];

        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);
        $dompdf->output();
        $dompdf->loadHtml(view('user/permintaan_barang/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ini_set('max_execution_time', 0);
        $dompdf->stream('Data.pdf', array("Attachment" => false));
    }
    //Akhir Permintaan

    //Pengadaan Barang
    // AKhir

    //Laporan

    public function lap_permintaan()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        return view('admin/laporan/permintaan_barang', $data);
    }

    public function cetakData()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');

        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('cetak'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('cetak'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $permintaanModel = new PermintaanModel();
        $data['permintaan'] = $permintaanModel->select('id_user, kode_barang, id_balasan_permintaan, nama_pengaju, perihal, detail, tanggal_pengajuan, tanggal_diproses, tanggal_selesai, status, status_akhir')
            ->where('tanggal_pengajuan >=', $tanggalMulai)
            ->where('tanggal_pengajuan <=', $tanggalAkhir)
            ->findAll();

        return view('admin/laporan/hasil', $data);
    }
    public function cetakDataPdf()
    {
        $tanggalMulai = $this->request->getGet('tanggal_mulai');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        // Validasi tanggal
        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $permintaanModel = new PermintaanModel();
        $data['permintaan'] = $permintaanModel
            ->select('id_user, kode_barang, id_balasan_permintaan, nama_pengaju, perihal, detail, tanggal_pengajuan, tanggal_diproses, tanggal_selesai, status, status_akhir')
            ->where('tanggal_pengajuan >=', $tanggalMulai . ' 00:00:00')
            ->where('tanggal_pengajuan <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();

        // Load library DomPDF
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);
        $dompdf->setOptions($options);

        // Buat halaman PDF dengan data
        $html = view('admin/laporan/hasil_pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Tampilkan atau unduh PDF
        $dompdf->stream('Data.pdf', array('Attachment' => false));
    }

    // public function cetakData()
    // {
    //     $tanggalMulai = $this->request->getPost('tanggal_mulai');
    //     $tanggalAkhir = $this->request->getPost('tanggal_akhir');

    //     // Validasi tanggal
    //     if (empty($tanggalMulai) || empty($tanggalAkhir)) {
    //         return redirect()->to(base_url('cetak'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
    //     }

    //     $dateMulai = strtotime($tanggalMulai);
    //     $dateAkhir = strtotime($tanggalAkhir);

    //     if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
    //         return redirect()->to(base_url('cetak'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
    //     }

    //     // Sesuaikan dengan field tabel Anda
    //     $permintaanModel = new PermintaanModel();
    //     $data['permintaan'] = $permintaanModel->select('id_user, kode_barang, id_balasan_permintaan, nama_pengaju, perihal, detail, tanggal_pengajuan, tanggal_diproses, tanggal_selesai, status, status_akhir')
    //         ->where('tanggal_pengajuan >=', $tanggalMulai)
    //         ->where('tanggal_pengajuan <=', $tanggalAkhir)
    //         ->findAll();

    //     // Load library DomPDF
    //     $dompdf = new \Dompdf\Dompdf();
    //     $options = new \Dompdf\Options();
    //     $options->setIsHtml5ParserEnabled(true);
    //     $options->setIsPhpEnabled(true);
    //     $dompdf->setOptions($options);

    //     // Buat halaman PDF dengan data
    //     $html = view('cetak/hasil_pdf', $data);
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render PDF
    //     $dompdf->render();

    //     // Tampilkan atau unduh PDF
    //     $dompdf->stream('Data.pdf', array('Attachment' => false));
    // }

}

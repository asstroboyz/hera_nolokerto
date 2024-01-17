<?php

namespace App\Controllers;

use App\Libraries\Ciqrcode;
use App\Models\BalasanModel;
use App\Models\BarangModel;
use App\Models\InventarisModel;
use App\Models\PengadaanModel;
use App\Models\PermintaanModel;
use App\Models\profil;
use App\Models\TransaksiBarangModel;
use Kenjis\CI3Compatible\Core\CI_Input;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;
use Fpdf\Fpdf;
use Mpdf\Mpdf;

use setasign\Fpdi\Fpdi;

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
        $this->PengadaanModel = new PengadaanModel();
        $this->BalasanModel = new BalasanModel();
        $this->profil = new profil();
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

        // Menghitung jumlah inventaris
        $dataInventaris = $this->db->table('inventaris')->get()->getResult();

        // Menghitung total untuk masing-masing status permintaan_barang
        $queryPermintaan = $this->db->table('permintaan_barang')->get()->getResult();
        $queryProsesPermintaan = $this->db->table('permintaan_barang')->where('status', 'diproses')->get()->getResult();
        $querySelesaiPermintaan = $this->db->table('permintaan_barang')->where('status', 'selesai')->get()->getResult();

        // Menghitung total untuk masing-masing status pengadaan_barang
        $queryPengadaan = $this->db->table('pengadaan_barang')->get()->getResult();
        $queryProsesPengadaan = $this->db->table('pengadaan_barang')->where('status', 'diproses')->get()->getResult();
        $querySelesaiPengadaan = $this->db->table('pengadaan_barang')->where('status', 'selesai')->get()->getResult();

        $queryBarangStokDibawah10 = $this->db->table('barang')->where('stok <', 10)->get()->getResult();

        $semua_inventaris = count($dataInventaris);
        $semua_permintaan = count($queryPengadaan);
        $semua_pengadaan = count($queryPermintaan);
        $proses_permintaan = count($queryProsesPermintaan);
        $selesai_permintaan = count($querySelesaiPermintaan);

        $proses_pengadaan = count($queryProsesPengadaan);
        $selesai_pengadaan = count($querySelesaiPengadaan);
        $stokdibawah10 = count($queryBarangStokDibawah10);

        $data = [
            'title' => 'BPS - Home',
            'stokdibawah10' => $stokdibawah10,
            'semua_inventaris' => $semua_inventaris,
            'semua_permintaan' => $semua_permintaan,
            'semua_pengadaan' => $semua_pengadaan,
            'proses_permintaan' => $proses_permintaan,
            'selesai_permintaan' => $selesai_permintaan,
            'proses_pengadaan' => $proses_pengadaan,
            'selesai_pengadaan' => $selesai_pengadaan,
        ];


        return view('admin/home/index', $data);
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
        $data['title'] = 'BPS - Detail Pengguna';

        $this->builder->select('users.id as userid, username, email, foto, name,created_at');
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
        $userlogin = user()->username;
        $userid = user()->id;
        $role = $this->db->table('auth_groups_users')->where('user_id', $userid)->get()->getRow();
        $role == '1' ? $role_echo = 'Admin' : $role_echo = 'User';

        $data = $this->db->table('permintaan_barang');
        $query1 = $data->where('id_user', $userid)->get()->getResult();
        $builder = $this->db->table('users');
        $builder->select('id,username,email,created_at,foto');
        $builder->where('username', $userlogin);
        $query = $builder->get();
        $semua = count($query1);
        $data = [
            'semua' => $semua,
            'user' => $query->getRow(),
            'title' => 'Profil - BPS',
            'role' => $role_echo,

        ];

        return view('admin/home/profil', $data);
    }

    public function simpanProfile($id)
    {
        $userlogin = user()->username;
        $builder = $this->db->table('users');
        $builder->select('*');
        $query = $builder->where('username', $userlogin)->get()->getRowArray();



        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            $this->profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
            ]);
        } else {


            $nama_foto = 'AdminFOTO' . $this->request->getPost('username') . '.' . $foto->guessExtension();
            if (!(empty($query['foto']))) {
                unlink('uploads/profile/' . $query['foto']);
            }
            $foto->move('uploads/profile', $nama_foto);

            $this->profil->update($id, [
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'foto' => $nama_foto
            ]);
        }
        session()->setFlashdata('msg', 'Profil Admin  berhasil Diubah');
        return redirect()->to(base_url('admin/profil/' . $id));

    }
    public function updatePassword($id)
    {
        $passwordLama = $this->request->getPost('passwordLama');
        $passwordbaru = $this->request->getPost('passwordBaru');
        $konfirm = $this->request->getPost('konfirm');

        if ($passwordbaru != $konfirm) {
            session()->setFlashdata('error-msg', 'Password Baru tidak sesuai');
            return redirect()->to(base_url('admin/profil/' . $id));
        }

        $builder = $this->db->table('users');
        $builder->where('id', user()->id);
        $query = $builder->get()->getRow();
        $verify_pass = password_verify(base64_encode(hash('sha384', $passwordLama, true)), $query->password_hash);

        if ($verify_pass) {
            $users = new UserModel();
            $entity = new \Myth\Auth\Entities\User();

            $entity->setPassword($passwordbaru);
            $hash = $entity->password_hash;
            $users->update($id, ['password_hash' => $hash]);
            session()->setFlashdata('msg', 'Password berhasil Diubah');
            return redirect()->to('/admin/profil/' . $id);
        } else {
            session()->setFlashdata('error-msg', 'Password Lama tidak sesuai');
            return redirect()->to(base_url('admin/profil/' . $id));
        }
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
        $data['validation'] = $this->validation;

        $data['title'] = 'Inventaris Barang';
        return view('admin/inventaris/index', $data);
    }

    // public function tambah_inv()
    // {
    //     $data = [
    //         'validation' => $this->validation,
    //         'title' => 'Tambah Barang Inventaris',
    //     ];

    //     return view('admin/inventaris/tambah_barang', $data);
    // }

    // public function generate_qrcode($data_array, $existing_qrcode = null)
    // {
    //     /* Load QR Code Library */
    //     // $this->load->library('ciqrcode');
    //     if (!isset($data_array['kode_barang']) || !isset($data_array['nama_barang'])) {
    //         // Tambahkan penanganan error atau kembalikan pesan kesalahan sesuai kebutuhan
    //         return ['error' => 'Kode barang atau nama barang tidak ditemukan dalam data'];
    //     }

    //     $combined_data = implode(',', $data_array);

    //     /* Data */
    //     // $hex_data   = bin2hex($combined_data);
    //     // $save_name  = $hex_data . '.png';

    //     /* Generate Slug from Nama Barang */
    //     $slug = url_title($data_array['kode_barang'] . '-' . $data_array['nama_barang'], '-', true);
    //     /* Generate Unique Time Stamp */
    //     $time_stamp = time();

    //     /* Combine Slug and Time Stamp for Unique Barcode */
    //     $unique_barcode = $slug . '_' . $time_stamp;

    //     /* QR Code File Directory Initialize */
    //     $dir = 'assets/media/qrcode/';
    //     if (!file_exists($dir)) {
    //         mkdir($dir, 0775, true);
    //     }

    //     /* QR Configuration  */
    //     $config['cacheable'] = true;
    //     $config['imagedir'] = $dir;
    //     $config['quality'] = true;
    //     $config['size'] = '1024';
    //     $config['black'] = [255, 255, 255];
    //     $config['white'] = [255, 255, 255];

    //     $this->ciqrcode->initialize($config);

    //     /* QR Data  */
    //     $params['data'] = $combined_data;
    //     $params['level'] = 'L';
    //     $params['size'] = 10;

    //     // Jika QR Code lama diberikan, hapus file QR Code lama
    //     if ($existing_qrcode) {
    //         unlink($existing_qrcode);
    //     }

    //     if (!$existing_qrcode) {
    //         // Jika tidak ada QR Code lama, atur nama baru
    //         $params['savename'] = FCPATH . $config['imagedir'] . $unique_barcode . '.png';
    //     } else {
    //         // Jika ada QR Code lama, gunakan nama yang sama
    //         $params['savename'] = $existing_qrcode;
    //     }
    //     // $params['savename'] = FCPATH . $config['imagedir'] . $unique_barcode . '.png';

    //     // $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

    //     $this->ciqrcode->generate($params);

    //     // Add logo to QR code
    //     $this->add_logo_to_qr_code($params['savename'], 'assets/media/qrcode/bps.png');
    //     /* Return Data */
    //     return [
    //         'unique_barcode' => $unique_barcode,
    //         'file' => $dir . $unique_barcode . '.png',
    //     ];
    //     return $qrcode;
    // }

    // public function add_logo_to_qr_code($qr_code_path, $logo_path, $logo_size_percent = 20, $logo_transparency = 100)
    // {
    //     // Load QR code image
    //     $qr_code = imagecreatefrompng($qr_code_path);
    //     $logo = imagecreatefrompng($logo_path);

    //     // Calculate position for logo in the center of QR code
    //     $qr_code_width = imagesx($qr_code);
    //     $qr_code_height = imagesy($qr_code);
    //     $logo_width = imagesx($logo);
    //     $logo_height = imagesy($logo);

    //     // Hitung ukuran logo yang diubah
    //     $new_logo_width = ($qr_code_width * $logo_size_percent) / 100;
    //     $new_logo_height = ($new_logo_width / $logo_width) * $logo_height;

    //     // Tempatkan logo di tengah QR code
    //     $x = ($qr_code_width - $new_logo_width) / 2;
    //     $y = ($qr_code_height - $new_logo_height) / 2;

    //     // Ubah ukuran logo
    //     $resized_logo = imagecreatetruecolor($new_logo_width, $new_logo_height);
    //     imagecopyresampled($resized_logo, $logo, 0, 0, 0, 0, $new_logo_width, $new_logo_height, $logo_width, $logo_height);

    //     // Tambahkan logo yang diubah ke dalam QR code dengan transparansi
    //     imagecopymerge($qr_code, $resized_logo, $x, $y, 0, 0, $new_logo_width, $new_logo_height, $logo_transparency);

    //     // Simpan hasilnya
    //     imagepng($qr_code, $qr_code_path);
    // }

    // public function add_data()
    // {

    //     $data = $this->request->getPost();

    //     // Generate QR Code
    //     $qr_data = [
    //         'kode_barang' => $data['kode_barang'],
    //         'nama_barang' => $data['nama_barang'],
    //         'kondisi' => $data['kondisi'],
    //         'merk' => $data['merk'],
    //         'tipe' => $data['tipe'],
    //         'satuan_barang' => $data['satuan_barang'],
    //         'jumlah_barang' => $data['jumlah_barang'],
    //         'tgl_perolehan' => $data['tgl_perolehan'],
    //     ];

    //     $qrcode_result = $this->generate_qrcode($qr_data);

    //     // Combine QR Code with other form data
    //     $data['qrcode'] = $qrcode_result['unique_barcode'];
    //     $data['file'] = $qrcode_result['file'];

    //     // Add Data
    //     if ($this->InventarisModel->insert_data($data)) {
    //         session()->setFlashdata('PesanBerhasil', 'Penambahan Data Berhasil');
    //     } else {
    //         session()->setFlashdata('PesanGagal', 'Penambahan Data Gagal. Coba lagi');
    //     }

    //     // Retrieve and return updated data to the view
    //     $data['inventaris'] = $this->InventarisModel->fetch_datas();
    //     $data['validation'] = $this->validation;
    //     $data['title'] = 'inventaris';
    //     return redirect()->to('/admin/adm_inventaris');
        
    // }

    public function tambah_inv()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Barang Inventaris'
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
        $config['cacheable']    = true;
        $config['imagedir']     = $dir;
        $config['quality']      = true;
        $config['size']         = '1024';
        $config['black']        = [255, 255, 255];
        $config['white']        = [255, 255, 255];

        $this->ciqrcode->initialize($config);

        /* QR Data  */
        $params['data']     = $combined_data;
        $params['level']    = 'L';
        $params['size']     = 10;

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
            'file'   => $dir . $unique_barcode . '.png',
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

        //Clear qrcode
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
        // $data['qrcode'] = $qrcode_result['unique_barcode'];
        // $data['file'] = $qrcode_result['file'];

        // // Add Data
        // if ($this->InventarisModel->insert_data($data)) {
        //     session()->setFlashdata('PesanBerhasil', 'Penambahan Data Berhasil');
        // } else {
        //     session()->setFlashdata('PesanGagal', 'Penambahan Data Gagal. Coba lagi');
        // }

        // // Retrieve and return updated data to the view
        // $data['inventaris'] = $this->InventarisModel->fetch_datas();
        // $data['title'] = 'inventaris';

        // return redirect()->to('/admin/adm_inventaris');
        //clear
        
        // Node 1: Awal fungsi
        $data = $this->request->getPost();

        // Node 2: Validasi Form
        $validation = \Config\Services::validation();
        $validation->setRules([
         'kode_barang'   => [
             'rules' => 'required',
             'errors' => [
                 'required' => 'Kode barang wajib diisi.',
             ],
         ],
         'nama_barang'   => [
             'rules' => 'required',
             'errors' => [
                 'required' => 'Nama barang wajib diisi.',
             ],
         ],
         'tgl_perolehan' => [
             'rules' => 'required|valid_date',
             'errors' => [
                 'required' => 'Tanggal perolehan wajib diisi.',
                 'valid_date' => 'Format tanggal perolehan tidak valid.',
             ],
         ],
         // ... (aturan validasi untuk field lainnya)
    ]);

        if ($validation->run($data)) {
            // Node 3: Generate QR Code
            $qr_data = [
                'kode_barang'    => $data['kode_barang'],
                'nama_barang'    => $data['nama_barang'],
                'kondisi'        => $data['kondisi'],
                'merk'            => $data['merk'],
                'tipe'            => $data['tipe'],
                'satuan_barang'  => $data['satuan_barang'],
                'jumlah_barang'  => $data['jumlah_barang'],
                'tgl_perolehan'  => $data['tgl_perolehan'],
            ];

            $qrcode_result = $this->generate_qrcode($qr_data);

            // Node 4: Gabungkan QR Code dengan data formulir lainnya
            $data['qrcode'] = $qrcode_result['unique_barcode'];
            $data['file'] = $qrcode_result['file'];

            // Node 5: Tambahkan Data
            if ($this->InventarisModel->insert_data($data)) {
                // Node 6: Pesan Berhasil
                session()->setFlashdata('PesanBerhasil', 'Penambahan Data Berhasil');

                // Node 8: Ambil dan kembalikan data yang diperbarui ke tampilan
                $data['inventaris'] = $this->InventarisModel->fetch_datas();
                $data['title'] = 'inventaris';

                // Node 9: Redirect ke /admin/adm_inventaris
                return redirect()->to('/admin/adm_inventaris');
            }
        }

        // Node 7: Redirect kembali ke formulir dengan input
        return redirect()->back()->withInput();


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
        // Node 1: Ambil data dari formulir
        $data = $this->request->getPost();

        // Node 2: Validasi Form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_barang'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode barang wajib diisi.',
                ],
            ],
            'nama_barang'   => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama barang wajib diisi.',
                ],
            ],
            'tgl_perolehan' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal perolehan wajib diisi.',
                    'valid_date' => 'Format tanggal perolehan tidak valid.',
                ],
            ],
            // ... (aturan validasi untuk field lainnya)
        ]);

        if ($validation->run($data)) {
            // Node 3: Mengambil data inventaris yang sudah ada
            $existingData = $this->InventarisModel->getInventaris($id);

            if (!empty($existingData['qrcode'])) {
                $this->delete_qrcode($existingData['qrcode']);
            }

            // Node 4: Generate new QR Code
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

            // Generate new QR Code
            $newQrCode = $this->generate_qrcode($qr_data);

            // Node 5: Mengupdate data inventaris
            $this->InventarisModel->update_data($id, [
                'kode_barang' => $data['kode_barang'],
                'nama_barang' => $data['nama_barang'],
                'kondisi' => $data['kondisi'],
                'merk' => $data['merk'],
                'tipe' => $data['tipe'],
                'satuan_barang' => $data['satuan_barang'],
                'jumlah_barang' => $data['jumlah_barang'],
                'tgl_perolehan' => $data['tgl_perolehan'],
                'qrcode' => $newQrCode['unique_barcode'], // Sesuaikan dengan kembalian generate_qrcode
                'file' => $newQrCode['file'],
            ]);

            // Node 6: Flashdata pesan disimpan
            session()->setFlashdata('pesanBerhasil', 'Data Berhasil Diubah');

            // Node 7: Redirect ke halaman index
            return redirect()->to('/admin/adm_inventaris');
        } else {
            // Node 8: Ambil pesan kesalahan
            $errors = $validation->getErrors();

            // Tampilkan pesan kesalahan (bisa juga disimpan dan ditampilkan di formulir)
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
        }

        // Node 9: Redirect kembali ke formulir dengan input
        return redirect()->back()->withInput();
    }


    // public function update($id)
    // {

    //     // Mengambil data inventaris yang sudah ada
    //     $existingData = $this->InventarisModel->getInventaris($id);

    //     if (!empty($existingData['qrcode'])) {
    //         $this->delete_qrcode($existingData['qrcode']);
    //     }
    //     // Generate new QR Code
    //     $qr_data = [
    //         'kode_barang' => $this->request->getVar('kode_barang'),
    //         'nama_barang' => $this->request->getVar('nama_barang'),
    //         'kondisi' => $this->request->getVar('kondisi'),
    //         'merk' => $this->request->getVar('merk'),
    //         'tipe' => $this->request->getVar('tipe'),
    //         'satuan_barang' => $this->request->getVar('satuan_barang'),
    //         'jumlah_barang' => $this->request->getVar('jumlah_barang'),
    //         'tgl_perolehan' => $this->request->getVar('tgl_perolehan'),
    //     ];

    //     // Generate new QR Code
    //     $newQrCode = $this->generate_qrcode($qr_data);

    //     // Mengupdate data inventaris
    //     $this->InventarisModel->update_data($id, [
    //         'kode_barang' => $this->request->getVar('kode_barang'),
    //         'nama_barang' => $this->request->getVar('nama_barang'),
    //         'kondisi' => $this->request->getVar('kondisi'),
    //         'merk' => $this->request->getVar('merk'),
    //         'tipe' => $this->request->getVar('tipe'),
    //         'satuan_barang' => $this->request->getVar('satuan_barang'),
    //         'jumlah_barang' => $this->request->getVar('jumlah_barang'),
    //         'tgl_perolehan' => $this->request->getVar('tgl_perolehan'),
    //         'qrcode' => $newQrCode['unique_barcode'], // Sesuaikan dengan kembalian generate_qrcode
    //         'file' => $newQrCode['file'],
    //     ]);

    //     // Flashdata pesan disimpan
    //     session()->setFlashdata(
    //         'pesanBerhasil',
    //         'Data Berhasil Diubah'
    //     );

    //     // Redirect ke halaman index
    //     return redirect()->to('/admin/adm_inventaris');

    // }

    public function detail_inv($id)
    {
        $data['title'] = 'Detail Barang Ienventaris'; // Pindahkan ini ke atas agar tidak terjadi override
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

        return redirect()->to('admin/adm_inventaris');
    }

    //Akhir Inventaris

    //ATK
    public function atk()
    {
        $data = [
            'title' => 'BPS - Barang',
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
            'nama_barang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama barang wajib diisi.',
                ],
            ],
            'stok' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Stok wajib diisi.',
                    'numeric' => 'Stok harus berupa angka.',
                    'greater_than' => 'Stok harus lebih besar dari 0.',
                ],
            ],
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            // Node 1: Ambil pesan kesalahan
            $errors = $this->validation->getErrors();

            // Node 2: Tampilkan pesan kesalahan sesuai dengan aturan yang telah ditentukan
            foreach ($errors as $error) {
                echo $error . '<br>';
            }

            // Node 3: Redirect kembali ke formulir dengan input
            return redirect()->to('/admin/tambahForm')->withInput();
        }

        // Simpan data barang ke database
        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jenis_barang' => $this->request->getPost('jenis_barang'),
            'satuan_barang' => $this->request->getPost('satuan_barang'),
            'stok' => $this->request->getPost('stok'),
            'tanggal_barang_masuk' => date('Y-m-d H:i:s'), // Tambahkan waktu saat ini
        ];

        // Generate dan tambahkan kode_barang ke dalam data
        $this->BarangModel->save($data);

        // Dapatkan kode_barang yang baru saja disimpan
        $kodeBarang = $this->BarangModel->getInsertID();

        // Masukkan data ke tabel transaksi_barang
        $this->TransaksiBarangModel->insert([
            'kode_barang' => $kodeBarang,
            'nama_barang' => $data['nama_barang'],
            'jenis_barang' => $data['jenis_barang'],
            'stok' => $data['stok'],
            'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
            'jumlah_perubahan' => $data['stok'],
            'jenis_transaksi' => 'masuk',
            'informasi_tambahan' => 'Penambahan stok melalui form tambah stok.',
            'tanggal_perubahan' => $data['tanggal_barang_masuk'],
        ]);

        // Tampilkan pesan sukses atau error
        session()->setFlashdata('msg', 'Data barang berhasil ditambahkan.');
        return redirect()->to('/admin/atk');

        // // Validasi input form tambah barang
        // $this->validation->setRules([
        //     'nama_barang' => 'required',
        //     'stok' => 'required|numeric',
        // ]);

        // if (!$this->validation->withRequest($this->request)->run()) {
        //     return redirect()->to('/admin/tambah')->withInput()->with('validation', $this->validation);
        // }

        // // Simpan data barang ke database
        // $data = [
        //     'nama_barang' => $this->request->getPost('nama_barang'),
        //     'jenis_barang' => $this->request->getPost('jenis_barang'),
        //     'satuan_barang' => $this->request->getPost('satuan_barang'),
        //     'stok' => $this->request->getPost('stok'),
        //     'tanggal_barang_masuk' => date('Y-m-d H:i:s'), // Tambahkan waktu saat ini
        // ];

        // // Generate dan tambahkan kode_barang ke dalam data
        // $this->BarangModel->save($data);

        // // Dapatkan kode_barang yang baru saja disimpan
        // $kodeBarang = $this->BarangModel->getInsertID();

        // // Masukkan data ke tabel transaksi_barang
        // $this->TransaksiBarangModel->insert([
        //     'kode_barang' => $kodeBarang,
        //     'nama_barang' => $data['nama_barang'],
        //     'jenis_barang' => $data['jenis_barang'],
        //     'stok' => $data['stok'],
        //     'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
        //     'jumlah_perubahan' => $data['stok'],
        //     'jenis_transaksi' => 'masuk',
        //     'informasi_tambahan' => 'Penambahan stok melalui form tambah stok.',
        //     'tanggal_perubahan' => $data['tanggal_barang_masuk'],
        // ]);


        // // Tampilkan pesan sukses atau error
        // session()->setFlashdata('msg', 'Data barang berhasil ditambahkan.');
        // return redirect()->to('/admin/atk');
    }

    // Di dalam metode deleteBarang di controller
    public function softDelete($kode_barang)
    {
        $barangModel = new BarangModel();

        // Cek apakah barang dengan kode_barang tertentu ada
        $barang = $barangModel->find($kode_barang);

        if ($barang) {
            // Lakukan soft delete dengan menghapus record di tabel Barang dan TransaksiBarang
            $barangModel->softDeleteWithRelations($kode_barang);

            return redirect()->to('/admin/atk')->with('success', 'Data berhasil dihapus secara soft delete.');
        } else {
            return redirect()->to('/admin/atk')->with('error', 'Data tidak ditemukan.');
        }
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

    public function lap_permintaan_barang()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        return view('admin/laporan/home_permintaan', $data);
    }

    public function cetakDataMasuk()
    {

        $tanggalMulai = $this->request->getGet('tanggal_mulai');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $transaksiBarangModel = new TransaksiBarangModel();
        $data['atk'] = $transaksiBarangModel
            ->select('kode_barang, nama_barang, jenis_barang, tanggal_barang_masuk, stok, jenis_transaksi, informasi_tambahan, jumlah_perubahan, ')
            ->where('tanggal_barang_masuk >=', $tanggalMulai . ' 00:00:00')
            ->where('tanggal_barang_masuk <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();
        $data['tanggalMulai'] = $tanggalMulai; // Add this line
        $data['tanggalAkhir'] = $tanggalAkhir;

        // $dompdf = new \Dompdf\Dompdf();
        // $options = new \Dompdf\Options();
        // $options->setIsHtml5ParserEnabled(true);
        // $options->setIsPhpEnabled(true);
        // $dompdf->setOptions($options);

        // // Buat halaman PDF dengan data
        // $html = view('admin/laporan/lap_barangMasuk', $data); // Sesuaikan dengan view yang kamu miliki untuk laporan ATK
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');

        // // Render PDF
        // $dompdf->render();

        // // Tampilkan atau unduh PDF
        // $dompdf->stream('Data_ATK.pdf', array('Attachment' => false));

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_barangMasuk', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);


        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap Barang Masuk Inventaris Barang.pdf', 'I');
    }

    public function cetakDataKeluar()
    {

        $tanggalMulai = $this->request->getGet('tanggal_mulai');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir');

        if (empty($tanggalMulai) || empty($tanggalAkhir)) {
            return redirect()->to(base_url('admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
        }

        $dateMulai = strtotime($tanggalMulai);
        $dateAkhir = strtotime($tanggalAkhir);

        if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
            return redirect()->to(base_url('admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
        }

        $transaksiBarangModel = new TransaksiBarangModel();

        $data['atkKeluar'] = $transaksiBarangModel
            ->select('kode_barang, nama_barang, jenis_barang, tanggal_barang_keluar, stok, jenis_transaksi, informasi_tambahan, jumlah_perubahan')
            ->where('tanggal_barang_keluar >=', $tanggalMulai . ' 00:00:00')
            ->where('tanggal_barang_keluar <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();
        $data['tanggalMulai'] = $tanggalMulai; // Add this line
        $data['tanggalAkhir'] = $tanggalAkhir;
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_barangKeluar', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);


        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap Barang Keluar Barang.pdf', 'I');
        // $dompdf = new \Dompdf\Dompdf();
        // $options = new \Dompdf\Options();
        // $options->setIsHtml5ParserEnabled(true);
        // $options->setIsPhpEnabled(true);
        // $dompdf->setOptions($options);

        // // Buat halaman PDF dengan data
        // $html = view('admin/laporan/lap_barangKeluar', $data); // Sesuaikan dengan view yang kamu miliki untuk laporan ATK
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');

        // // Render PDF
        // $dompdf->render();

        // // Tampilkan atau unduh PDF
        // $dompdf->stream('Data_ATK.pdf', array('Attachment' => false));

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

    public function cetakDataPdf() //permintaan
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
        $html = view('admin/laporan/lap_permintaan', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Tampilkan atau unduh PDF
        $dompdf->stream('Data.pdf', array('Attachment' => false));
    }
    // public function print() // all data
    // {
    //     $data = [
    //         'permintaan_barang' => $this->PermintaanModel->getAll(),
    //         'title' => 'Cetak Data',
    //     ];

    //     $dompdf = new \Dompdf\Dompdf();
    //     $options = new \Dompdf\Options();
    //     $options->setIsRemoteEnabled(true);

    //     $dompdf->setOptions($options);
    //     $dompdf->output();
    //     $dompdf->loadHtml(view('user/permintaan_barang/print', $data));
    //     $dompdf->setPaper('A4', 'landscape');
    //     $dompdf->render();
    //     ini_set('max_execution_time', 0);
    //     $dompdf->stream('Data.pdf', array("Attachment" => false));
    // }
    //Akhir Permintaan

    //Pengadaan Barang
    public function pengadaan()
    {

        $this->builder = $this->db->table('pengadaan_barang');
        $this->builder->select('*');
        $this->builder->where('id_user', user()->id);
        $this->query = $this->builder->get();
        $data['pengadaan'] = $this->query->getResultArray();
        // dd(  $data['inventaris']);
        $data['title'] = 'Pengadaan Barang';

        return view('admin/pengadaan/index', $data);
    }

    public function tambah_pengadaan()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Pengadaan Barang',

        ];
        return view('admin/pengadaan/tambah_pengadaan', $data);
    }
    public function simpanPengadaan()
    {
        $rules = [
            'nama_barang' => 'required',
            'jumlah' => 'required|numeric',
            'spesifikasi' => 'required',
            'tahun_periode' => 'required',
            'alasan_pengadaan' => 'required',
        ];

        $errors = [
            'nama_barang' => [
                'required' => 'Nama Barang wajib di isi',
            ],
            'jumlah' => [
                'required' => 'Jumlah wajib di isi',
                'numeric' => 'Jumlah harus berupa angka',
            ],
            'spesifikasi' => [
                'required' => 'Spesifikasi wajib di isi',
            ],
            'tahun_periode' => [
                'required' => 'Tahun Periode wajib di isi',
            ],
            'alasan_pengadaan' => [
                'required' => 'Alasan wajib di isi',
            ],
        ];

        // Update validation messages
        if (!$this->validate($rules, $errors)) {
            return redirect()->to('/admin/tambah_pengadaan')->withInput();
        }

        // Prepare data for saving
        $dataPengadaan = [
            'id_user' => user()->id,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jumlah' => $this->request->getPost('jumlah'),
            'spesifikasi' => $this->request->getPost('spesifikasi'),
            'tahun_periode' => $this->request->getPost('tahun_periode'),
            'alasan_pengadaan' => $this->request->getPost('alasan_pengadaan'),
            'nama_pengaju' => user()->username,
            'tgl_pengajuan' => date("Y/m/d h:i:s"),
            'status' => 'belum diproses',
        ];

        // Save data to the database
        $this->PengadaanModel->save($dataPengadaan);

        // Flashdata pesan disimpan
        session()->setFlashdata('pesanBerhasil', 'Data Pengadaan Berhasil Ditambahkan');
        return redirect()->to('/admin/pengadaan');
    }



    public function editPengadaan($id)
    {
        $validation = \Config\Services::validation();

        $data['pengadaan'] = $this->PengadaanModel->find($id);
        $data['validation'] = $validation; // Pass the validation service to the view
        $data['title'] = 'Ubah Pengadaan'; // Pass the validation service to the view

        // Cek apakah pengadaan dengan id tersebut ditemukan
        if (!$data['pengadaan']) {
            // Redirect atau tampilkan pesan error jika tidak ditemukan
            return redirect()->to('/admin/pengadaan')->with('pesanError', 'Pengadaan tidak ditemukan');
        }

        // Tampilkan formulir edit dengan data pengadaan
        return view('admin/pengadaan/edit_pengadaan', $data);
    }

    public function updatePengadaan($id)
    {
        // Validasi input
        if (!$this->validate([
            'alasan_pengadaan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'alasan_pengadaan wajib di isi',
                ],
            ],

        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to("/user/editPengadaan/$id")->withInput()->with('validation', $validation);
        }

        // Dapatkan data pengadaan dari database
        $pengadaan = $this->PengadaanModel->find($id);

        // Cek apakah pengadaan dengan id tersebut ditemukan
        if (!$pengadaan) {
            // Redirect atau tampilkan pesan error jika tidak ditemukan
            return redirect()->to('/user/pengadaan')->with('pesanError', 'Pengadaan tidak ditemukan');
        }

        // Persiapkan data untuk disimpan
        $dataPengadaan = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jumlah' => $this->request->getPost('jumlah'),
            'spesifikasi' => $this->request->getPost('spesifikasi'),
            'tahun_periode' => $this->request->getPost('tahun_periode'),
            'alasan_pengadaan' => $this->request->getPost('alasan_pengadaan'),
            'nama_pengaju' => user()->username,
        ];

        // Update data ke database
        $this->PengadaanModel->update($id, $dataPengadaan);

        // Flashdata pesan berhasil diupdate
        session()->setFlashdata('pesanBerhasil', 'Data Pengadaan Berhasil Diupdate');
        return redirect()->to('/admin/pengadaan');
    }

    public function detailPengadaan($id)
    {

        $data = $this->db->table('pengadaan_barang');
        $data->select('*');
        $data->where('id', $id);
        $query = $data->get();

        $d = $this->db->table('balasan_pengadaan');
        $d->select('*');
        $d->where('id_pengadaan', $id);
        $balasan = $d->get()->getRow();

        // dd($query1);
        $ex = [

            'detail' => $query->getRow(),
            'title' => 'Detail Pengadaan Barang',
            'balasan' => $balasan,

        ];

        return view('admin/pengadaan/detail_pengadaan', $ex);
    }
    public function deletePengadaan($id)
    {
        $data = [
            'validation' => \Config\Services::validation(),
            'pengadaan' => $this->PengadaanModel->getpengadaan($id),
        ];

        $this->PengadaanModel->delete($id);
        session()->setFlashdata('pesanBerhasil', 'Data Berhasil Dihapus');

        // Redirect ke halaman index
        return redirect()->to('/admin/pengadaan');
    }

    public function printPB() // all data
    {
        $data = [
            'pengadaan' => $this->PengadaanModel->getAll(),
            'title' => 'Cetak Data',
        ];

        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);
        $dompdf->output();
        $dompdf->loadHtml(view('user/pengadaan/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ini_set('max_execution_time', 0);
        $dompdf->stream('Data.pdf', array("Attachment" => false));
    }
    public function eksporPB($id) //detail permintaan
    {
        // $aduan = $this->pengaduan->where(['id' => $id])->first();
        // $id = $id;
        // $data['detail']   = $aduan;
        $data['title'] = 'cetak';
        $data['detail'] = $this->PengadaanModel->where(['id' => $id])->first();

        //Cetak dengan dompdf
        $dompdf = new \Dompdf\Dompdf();
        ini_set('max_execution_time', 0);
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);
        $dompdf->output();
        $dompdf->loadHtml(view('user/pengadaan/cetakid', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Detail Pengadaan.pdf', array("Attachment" => false));
    }
    //Akhir Pengadaan
    // AKhir

    //Laporan

    public function lap_permintaan()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        return view('admin/laporan/index', $data);
    }

    public function lap_masuk()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        return view('admin/laporan/home_transaksimasuk', $data);
    }
    public function lap_keluar()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan',

        ];

        return view('admin/laporan/home_transaksikeluar', $data);
    }
    //Pengadaan
    public function lap_pengadaan()
    {
        $data = [
            // 'user'=> $query->getResult(),
            'title' => 'BPS - Laporan Pengadaan Barang',

        ];

        return view('admin/laporan/home_pengadaan', $data);
    }

    public function cetakDataPengadaan()
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

        $pengadaanModel = new PengadaanModel();
        $data['pengadaan'] = $pengadaanModel
            ->select('id, id_user, id_balasan_pengadaan, nama_pengaju, nama_barang, spesifikasi, jumlah, tahun_periode, alasan_pengadaan, tgl_pengajuan, tgl_proses, tgl_selesai, status, status_akhir')
            ->where('tgl_pengajuan >=', $tanggalMulai . ' 00:00:00')
            ->where('tgl_pengajuan <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();

        

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_pengadaan', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);


        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap Pengadaan Barang.pdf', 'I');
    }
    // Pengadaan

    //laporan_inventaris
    public function lap_inventaris()
    {
        $data = [
            'title' => 'BPS - Laporan Inventaris',
        ];

        return view('admin/laporan/home_inventaris', $data);
    }
    public function lap_qr()
    {
        $data = [
            'title' => 'BPS - Cetak QR Inventaris',
        ];

        return view('admin/laporan/home_qr', $data);
    }

    // public function cetakDataInventaris()
    // {
    //     //     $tanggalMulai = $this->request->getGet('tanggal_mulai');
    //     //     $tanggalAkhir = $this->request->getGet('tanggal_akhir');

    //     //     // Validasi tanggal
    //     //     if (empty($tanggalMulai) || empty($tanggalAkhir)) {
    //     //         return redirect()->to(base_url('admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
    //     //     }

    //     //     $dateMulai = strtotime($tanggalMulai);
    //     //     $dateAkhir = strtotime($tanggalAkhir);

    //     //     if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
    //     //         return redirect()->to(base_url('admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
    //     //     }

    //     //     $inventarisModel = new InventarisModel(); // Change to your actual model name
    //     //     $data['inventaris'] = $inventarisModel
    //     //         ->select('id, kode_barang, nama_barang, kondisi, merk, tipe, satuan_barang, jumlah_barang, tgl_perolehan, qrcode, file, created_at, updated_at, deleted_at')
    //     //         ->where('tgl_perolehan >=', $tanggalMulai . ' 00:00:00')
    //     //         ->where('tgl_perolehan <=', $tanggalAkhir . ' 23:59:59')
    //     //         ->findAll();

    //     //     // Load library DomPDF
    //     //     $dompdf = new \Dompdf\Dompdf();
    //     //     $options = new \Dompdf\Options();
    //     //     $options->setIsHtml5ParserEnabled(true);
    //     //     $options->setIsPhpEnabled(true);
    //     //     // $options->setIsRemoteEnabled(true);
    //     //     $dompdf->setOptions($options);

    //     //     // Buat halaman PDF dengan data
    //     //     return view('admin/laporan/lap_inventaris', $data); // Update view path accordingly
    //     //     $dompdf->loadHtml($html);
    //     //     $dompdf->setPaper('A4', 'landscape');

    //     //     // Render PDF
    //     //     $dompdf->render();

    //     //     // Tampilkan atau unduh PDF
    //     //     $dompdf->stream('Data_Inventaris.pdf', array('Attachment' => true));

    //     // }

    //     $tanggalMulai = $this->request->getGet('tanggal_mulai');
    //     $tanggalAkhir = $this->request->getGet('tanggal_akhir');

    //     // Validasi tanggal
    //     if (empty($tanggalMulai) || empty($tanggalAkhir)) {
    //         return redirect()->to(base_url('admin'))->with('error', 'Tanggal mulai dan tanggal akhir harus diisi.');
    //     }

    //     $dateMulai = strtotime($tanggalMulai);
    //     $dateAkhir = strtotime($tanggalAkhir);

    //     if ($dateMulai === false || $dateAkhir === false || $dateMulai > $dateAkhir) {
    //         return redirect()->to(base_url('admin'))->with('error', 'Format tanggal tidak valid atau tanggal mulai melebihi tanggal akhir.');
    //     }

    //     $inventarisModel = new InventarisModel();
    //     $inventaris = $inventarisModel
    //         ->select('id, kode_barang, nama_barang, kondisi, merk, tipe, satuan_barang, jumlah_barang, tgl_perolehan, qrcode, file, created_at, updated_at, deleted_at')
    //         ->where('tgl_perolehan >=', $tanggalMulai . ' 00:00:00')
    //         ->where('tgl_perolehan <=', $tanggalAkhir . ' 23:59:59')
    //         ->findAll();

    //     // Load library FPDF
    //     require_once ROOTPATH . 'vendor/setasign/fpdf/fpdf.php';

    //     $pdf = new \FPDF();

    //     // Buat halaman PDF dengan data
    //     $pdf->AddPage();
    //     $pdf->SetFont('Arial', 'B', 16);

    //     // Tambahkan header
    //     $pdf->Ln(10);
    //     $pdf->Cell(20, 10, 'No', 1);
    //     $pdf->Cell(40, 10, 'Gambar', 1);
    //     $pdf->Cell(40, 10, 'Created At', 1);
    //     $pdf->Cell(40, 10, 'Updated At', 1);
    //     $pdf->Cell(40, 10, 'Deleted At', 1);

    //     // Tambahkan data inventaris ke PDF
    //     // $x_awal = 0;

    //     // foreach ($inventaris as $row) {
    //     //     $pdf->Ln();
    //     //     $pdf->Cell(20, 10, $row['id'], 1);
    //     //     // $n= $pdf->Image($row['file'], $x_awal, $x_awal, -300);

    //     //     // $pdf->Cell(40, 10, $n, 1); // Placeholder for image, adjust as needed
    //     //     $x_awal = $x_awal+300;
    //     //     // Tambahkan gambar ke PDF jika ada
    //     //     if (!empty($row['file'])) {
    //     //         $gambarPath = FCPATH  . $row['file'];
    //     //         if (file_exists($gambarPath)) {
    //     //             $pdf->Image($gambarPath, $pdf->GetX() + 1, $pdf->GetY() + 20, 38, 38);
    //     //         }
    //     //     } else {
    //     //         $pdf->Cell(40, 10, 'Gambar tidak tersedia', 1);
    //     //     }

    //     //     $pdf->Cell(40, 10, $row['created_at'], 1);
    //     //     $pdf->Cell(40, 10, $row['updated_at'], 1);
    //     //     $pdf->Cell(40, 10, $row['deleted_at'], 1);
    //     // }
    //     $x_awal = 0;

    //     foreach ($inventaris as $row) {
    //         $pdf->Ln();
    //         $pdf->Cell(20, 10, $row['id'], 1);
    //         $x_awal = $x_awal + 300;

    //         // Tambahkan gambar ke PDF jika ada
    //         if (!empty($row['file'])) {
    //             $gambarPath = FCPATH . $row['file'];
    //             if (file_exists($gambarPath)) {
    //                 $pdf->Image($gambarPath, $pdf->GetX() + 1, $pdf->GetY() + 20, 38, 38);
    //             }
    //         } else {
    //             $pdf->Cell(40, 10, 'Gambar tidak tersedia', 1);
    //         }

    //         $pdf->Cell(40, 10, $row['created_at'], 1);
    //         $pdf->Cell(40, 10, $row['updated_at'], 1);
    //         $pdf->Cell(40, 10, $row['deleted_at'], 1);
    //     }


    //     // Simpan atau keluarkan PDF
    //     $pdf->Output('Data_Inventaris.pdf', 'I');
    //     exit;


    // }
    
    public function cetak_qr()
    {
        ini_set('max_execution_time', 0);
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

        $inventarisModel = new InventarisModel(); // Ganti dengan nama model yang sesuai
        $data['inventaris'] = $inventarisModel
            ->select('id, kode_barang, nama_barang, kondisi, merk, tipe, satuan_barang, jumlah_barang, tgl_perolehan, qrcode, file, created_at, updated_at, deleted_at')
            ->where('tgl_perolehan >=', $tanggalMulai . ' 00:00:00')
            ->where('tgl_perolehan <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_qr', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'P'] + $options);

        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap_QR_ Inventaris Barang.pdf', 'I');
    }
    
    public function cetak_qr_id($id)
    {
        ini_set('max_execution_time', 0);

        $data['title'] = 'cetak';
        $data['inventaris'] = $this->InventarisModel->where(['id' => $id])->first();

        if (empty($data['inventaris'])) {
            // Handle the case when no data is found for the given kode_barang
            return redirect()->to(base_url('admin'))->with('error', 'Data not found for kode_barang: ' . $id);
        }

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/kode_qr', $data);
        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'P'] + $options);
        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap_QR_Inventaris_Barang.pdf', 'I');

    }


    public function cetak_lap_inv()
    {
        ini_set('max_execution_time', 0);
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

        $inventarisModel = new InventarisModel(); // Ganti dengan nama model yang sesuai
        $data['inventaris'] = $inventarisModel
            ->select('id, kode_barang, nama_barang, kondisi, merk, tipe, satuan_barang, jumlah_barang, tgl_perolehan, qrcode, file, created_at, updated_at, deleted_at')
            ->where('tgl_perolehan >=', $tanggalMulai . ' 00:00:00')
            ->where('tgl_perolehan <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_inventaris', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);


        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap Inventaris Barang.pdf', 'I');
    }


    //laporan inventaris

    //Laporan Barang
    public function lap_barang()
    {
        $data = [
            'title' => 'BPS - Laporan Barang',
        ];

        return view('admin/laporan/home_barang', $data);
    }

    public function cetakDataBarang()
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
        $barangModel = new BarangModel();
        $data['barang'] = $barangModel
            ->select('kode_barang, nama_barang, satuan_barang, jenis_barang, jenis_transaksi, tanggal_barang_masuk, stok')
            ->where('tanggal_barang_masuk >=', $tanggalMulai . ' 00:00:00')
            ->where('tanggal_barang_masuk <=', $tanggalAkhir . ' 23:59:59')
            ->findAll();
        $data['tanggalMulai'] = $tanggalMulai; // Add this line
        $data['tanggalAkhir'] = $tanggalAkhir;
        // Load library DomPDF
        // $dompdf = new \Dompdf\Dompdf();
        // $options = new \Dompdf\Options();
        // $options->setIsHtml5ParserEnabled(true);
        // $options->setIsPhpEnabled(true);
        // $dompdf->setOptions($options);

        // // Buat halaman PDF dengan data
        // $html = view('admin/laporan/lap_barang', $data); // Sesuaikan dengan view yang Anda miliki untuk laporan barang
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');

        // // Render PDF
        // $dompdf->render();

        // // Tampilkan atau unduh PDF
        // $dompdf->stream('Data_Barang.pdf', array('Attachment' => false));

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $html = view('admin/laporan/lap_barang', $data);

        $mpdf->setAutoPageBreak(true);

        $options = [
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ],
        ];

        $mpdf->AddPageByArray(['orientation' => 'L'] + $options);


        $mpdf->WriteHtml($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Lap Data Barang Barang.pdf', 'I');
    }

    // tambah user
    public function kelola_user()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();

        $groupModel = new GroupModel();

        foreach ($data['users'] as $row) {
            $dataRow['group'] = $groupModel->getGroupsForUser($row->id);
            $dataRow['row'] = $row;
            $data['row' . $row->id] = view('admin/user/row', $dataRow);
        }
        $data['groups'] = $groupModel->findAll();
        $data['title'] = 'Daftar Pengguna';
        return view('admin/user/index', $data);
    }

    public function tambah_user()
    {

        $data = [
            'title' => 'BPS - Tambah Users',
        ];
        return view('/admin/user/tambah', $data);
    }

    public function changeGroup()
    {
        $userId = $this->request->getVar('id');
        $groupId = $this->request->getVar('group');
        $groupModel = new GroupModel();
        $groupModel->removeUserFromAllGroups(intval($userId));
        $groupModel->addUserToGroup(intval($userId), intval($groupId));
        return redirect()->to(base_url('/admin/kelola_user'));

    }

    public function changePassword()
    {
        $userId = $this->request->getVar('user_id');

        $password_baru = $this->request->getVar('password_baru');
        $userModel = new \App\Models\User();
        $user = $userModel->getUsers($userId);
        // $dataUser->update($userId, ['password_hash' => password_hash($password_baru, PASSWORD_DEFAULT)]);
        $userEntity = new User($user);
        $userEntity->password = $password_baru;
        $userModel->save($userEntity);
        return redirect()->to(base_url('admin/kelola_user'));
    }

    public function activateUser($id, $active)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if ($user) {
            $userModel->update($id, ['active' => $active]);
            return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }
    }
    //Laporan Barang
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
}

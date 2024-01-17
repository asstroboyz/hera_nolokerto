<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\InventarisModel;
use App\Models\PengadaanModel;
use App\Models\PermintaanModel;
use App\Models\profil;
use Dompdf\Dompdf;
use Dompdf\Options;
use Myth\Auth\Models\UserModel;

class Pegawai extends BaseController
{
    protected $db;
    protected $builder;
    protected $BarangModel;
    protected $PengadaanModel;
    public function __construct()
    {

        $this->profil = new profil;
        $this->PengadaanModel = new PengadaanModel();
        $this->BarangModel = new BarangModel();
        $this->InventarisModel = new InventarisModel();
        $this->PermintaanModel = new PermintaanModel();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // $data['title'] = 'User Profile ';
        $userlogin = user()->id;

        $data = $this->db->table('permintaan_barang');
        // $builder->select('id,username,email,created_at,foto');

        $query1 = $data->where('id_user', $userlogin)->get()->getResult();
        $query2 = $data->where('id_user', $userlogin)->where('status', 'diproses')->get()->getResult();
        $query3 = $data->where('id_user', $userlogin)->where('status', 'selesai')->get()->getResult();
        // $query = $builder->get();
        // $query1 = $builder->where('status', 'diproses')->get()->getResult();
        $semua = count($query1);

        $data = [
            'semua' => $semua,
            'proses' => count($query2),
            'selesai' => count($query3),
            'title' => 'Home',
        ];
        return view('pegawai/profil/home', $data);
    }

    public function profil()
    {
        // $data['title'] = 'User Profile ';
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
            'title' => 'Home',
            'role' => $role_echo,

        ];
        return view('pegawai/profil/index', $data);

    }

    public function updatePassword($id)
    {
        $passwordLama = $this->request->getPost('passwordLama');
        $passwordbaru = $this->request->getPost('passwordBaru');
        $konfirm = $this->request->getPost('konfirm');

        if ($passwordbaru != $konfirm) {
            session()->setFlashdata('error-msg', 'Password Baru tidak sesuai');
            return redirect()->to(base_url('pegawai/profil/' . $id));
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
            return redirect()->to('/pegawai/profil/' . $id);
        } else {
            session()->setFlashdata('error-msg', 'Password Lama tidak sesuai');
            return redirect()->to(base_url('pegawai/profil/' . $id));
        }
    }

    public function profile($id)
    {
        $userlogin = user()->username;
        $builder = $this->db->table('users');
        $builder->select('username,email,created_at');
        $query = $builder->where('username', $userlogin)->get()->getRowArray();
        $data = [

            'user' => $query,
            'validation' => $this->validation,
            'title' => 'Update Profile',
        ];
        // dd($data['user']);

        return view('pegawai/profil/ubah_profil', $data);
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


            $nama_foto = 'pegawaiFOTO' . $this->request->getPost('username') . '.' . $foto->guessExtension();
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
        session()->setFlashdata('msg', 'Profil Pegawai  berhasil Diubah');
        return redirect()->to(base_url('pegawai/profil/' . $id));

    }

    public function register()
    {
        return view('auth/register');
    }
    public function user()
    {
        return view('user/index');
    }

    // Permintaan Barang
    public function permintaan()
    {
        $model = new PermintaanModel();
        // $data['pengaduan'] = $query;
        $this->builder = $this->db->table('permintaan_barang');
        $this->builder->select('*');
        $this->builder->where('id_user', user()->id);
        $this->query = $this->builder->get();
        $data['permintaan'] = $this->query->getResultArray();
        // dd(  $data['permintaan']);
        $data['permintaan'] = $model->getPermintaanWithBarang();
        $data['title'] = 'Permintaan Barang';
        return view('pegawai/permintaan_barang/index', $data);
    }

    public function tambah_permintaan()
    {
        $data = [
            'validation' => $this->validation,
            'title' => 'Tambah Permintaan',
            'barangList' => $this->BarangModel->findAll(), // Ambil daftar barang
        ];

        return view('pegawai/permintaan_barang/tambah_permintaan', $data);
    }

    public function simpanPermin()
    {
        if (!$this->validate([
            'perihal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal wajib di isi',
                ],
            ],
            'kode_barang' => 'required', // Add validation for kode_barang
            // Add other validations as needed
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/pegawai/tambah_permintaan')->withInput()->with('validation', $validation);
        }

    
        // Retrieve data barang based on selected kode_barang
        $barang = $this->BarangModel->find($this->request->getPost('kode_barang'));

        if (!$barang) {
            // Handle if barang is not found, maybe redirect with an error message
        }

        // Prepare data for saving
        $dataPermintaan = [
            'id_user' => user()->id,
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $barang['nama_barang'], // Menambahkan data nama_barang
            'jumlah' => $this->request->getPost('jumlah'),
            'perihal' => $this->request->getPost('perihal'),
            'detail' => $this->request->getPost('detail'),
            'nama_pengaju' => user()->username,
            'tanggal_pengajuan' => date("Y/m/d h:i:s"),
            'status' => 'belum diproses',
        ];

        // Save data to the database
        $this->PermintaanModel->save($dataPermintaan);

        // Flashdata pesan disimpan
        session()->setFlashdata('pesanBerhasil', 'Data Permintaan Berhasil Ditambahkan');

        return redirect()->to('/pegawai/permintaan');
    }

    public function ubah($id)
    {
        // Mengambil data permintaan berdasarkan ID
        $permintaan = $this->PermintaanModel->find($id);

        // Mengambil daftar barang
        $barangList = $this->BarangModel->findAll();

        // Menyiapkan data untuk dikirim ke view
        $data = [
            'validation' => $this->validation,
            'title' => 'Edit Permintaan',
            'permintaan' => $permintaan,
            'barangList' => $barangList, // Tambahkan daftar barang ke data yang dikirim ke view
        ];

        // Menampilkan view untuk form edit
        return view('pegawai/permintaan_barang/edit_permintaan', $data);
    }

    public function update($id)
    {
        // Validasi input
        if (!$this->validate([
            'perihal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Perihal wajib di isi',
                ],
            ],
            // Tambahkan validasi lain sesuai kebutuhan
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/pegawai/edit_permintaan/' . $id)->withInput()->with('validation', $validation);
        }

        // Mengupdate data permintaan berdasarkan ID
        $this->PermintaanModel->update($id, [
            'jumlah' => $this->request->getPost('jumlah'),
            'perihal' => $this->request->getPost('perihal'),
            'detail' => $this->request->getPost('detail'),
            'kode_barang' => $this->request->getPost('kode_barang'), // Tambahkan baris ini untuk memperbarui kode_barang
            // Tambahkan kolom-kolom lain yang ingin di-update
        ]);

        // Flashdata pesan disimpan
        session()->setFlashdata('pesanBerhasil', 'Data Permintaan Berhasil Diupdate');

        // Redirect ke halaman daftar permintaan
        return redirect()->to('/pegawai/permintaan');
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

        ];

        return view('pegawai/permintaan_barang/detail_permintaan', $ex);
    }

    public function delete($id)
    {
        $data = [
            'validation' => \Config\Services::validation(),
            'permintaan' => $this->PermintaanModel->getPermintaan($id),
        ];

        $this->PermintaanModel->delete($id);
        session()->setFlashdata('pesanBerhasil', 'Data Berhasil Dihapus');

        // Redirect ke halaman index
        return redirect()->to('/pegawai/permintaan');
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
        $dompdf->loadHtml(view('pegawai/permintaan_barang/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ini_set('max_execution_time', 0);
        $dompdf->stream('Data.pdf', array("Attachment" => false));
    }
    public function ekspor($id) //detail permintaan
    {
        // $aduan = $this->pengaduan->where(['id' => $id])->first();
        // $id = $id;
        // $data['detail']   = $aduan;
        $data['title'] = 'cetak';
        $data['detail'] = $this->PermintaanModel->where(['id' => $id])->first();

        //Cetak dengan dompdf
        $dompdf = new \Dompdf\Dompdf();
        ini_set('max_execution_time', 0);
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);
        $dompdf->output();
        $dompdf->loadHtml(view('pegawai/permintaan_barang/cetakid', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Detail Permintaan.pdf', array("Attachment" => false));
    }

    // akhir permintaan

    // Inventaris
    public function inventaris()
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
        return view('pegawai/inventaris/index', $data);
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
            return redirect()->to('/pegawai/detail_inv');
        }

        return view('pegawai/inventaris/detail_inv', $data);
    }

    // ATK
    public function ATK()
    {
        $data = [
            'title' => 'Barang ATK',
            'barangs' => $this->BarangModel->findAll(),
        ];

        return view('pegawai/atk/index', $data);
    }
    //Akhir ATK

    // //Pengadaan Barang
    // public function pengadaan()
    // {
    //     // $model = new PermintaanModel();
    //     // // $data['pengaduan'] = $query;
    //     // $this->builder = $this->db->table('permintaan_barang');
    //     // $this->builder->select('*');
    //     // $this->builder->where('id_user', user()->id);
    //     // $this->query = $this->builder->get();
    //     // $data['permintaan'] = $this->query->getResultArray();
    //     // // dd(  $data['permintaan']);
    //     // $data['permintaan'] = $model->getPermintaanWithBarang();
    //     // $data['title'] = 'Permintaan Barang';
    //     $this->builder = $this->db->table('pengadaan_barang');
    //     $this->builder->select('*');
    //     $this->builder->where('id_user', user()->id);
    //     $this->query = $this->builder->get();
    //     $data['pengadaan'] = $this->query->getResultArray();
    //     // dd(  $data['inventaris']);
    //     $data['title'] = 'Pengadaan Barang';

    //     return view('user/pengadaan/index', $data);
    // }

    // public function tambah_pengadaan()
    // {
    //     $data = [
    //         'validation' => $this->validation,
    //         'title' => 'Tambah Pengadaan Barang',

    //     ];
    //     return view('user/pengadaan/tambah_pengadaan', $data);
    // }

    // public function simpanPengadaan()
    // {
    //     if (!$this->validate([
    //         'alasan_pengadaan' => [
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => 'Perihal wajib di isi',
    //             ],
    //         ],

    //     ])) {
    //         $validation = \Config\Services::validation();
    //         return redirect()->to('/user/tambah_pengadaan')->withInput()->with('validation', $validation);
    //     }

    //     // Determine the value for 'nama_pengaju'
       

    //     // Prepare data for saving
    //     $dataPengadaan = [
    //         'id_user' => user()->id,
    //         'nama_barang' => $this->request->getPost('nama_barang'),
    //         'jumlah' => $this->request->getPost('jumlah'),
    //         'spesifikasi' => $this->request->getPost('spesifikasi'),
    //         'tahun_periode' => $this->request->getPost('tahun_periode'),
    //         'alasan_pengadaan' => $this->request->getPost('alasan_pengadaan'),
    //         'nama_pengaju' => user()->username,
    //         'tgl_pengajuan' => date("Y/m/d h:i:s"),
    //         'status' => 'belum diproses',
    //     ];

    //     // Save data to the database
    //     $this->PengadaanModel->save($dataPengadaan);

    //     // Flashdata pesan disimpan
    //     session()->setFlashdata('pesanBerhasil', 'Data Pengadaan Berhasil Ditambahkan');
    //     return redirect()->to('/user/pengadaan');
    // }
    // public function editPengadaan($id)
    // {
    //     $validation = \Config\Services::validation();

    //     $data['pengadaan'] = $this->PengadaanModel->find($id);
    //     $data['validation'] = $validation; // Pass the validation service to the view
    //     $data['title'] = 'Ubah Pengadaan'; // Pass the validation service to the view

    //     // Cek apakah pengadaan dengan id tersebut ditemukan
    //     if (!$data['pengadaan']) {
    //         // Redirect atau tampilkan pesan error jika tidak ditemukan
    //         return redirect()->to('/user/pengadaan')->with('pesanError', 'Pengadaan tidak ditemukan');
    //     }

    //     // Tampilkan formulir edit dengan data pengadaan
    //     return view('user/pengadaan/edit_pengadaan', $data);
    // }

    // public function updatePengadaan($id)
    // {
    //     // Validasi input
    //     if (!$this->validate([
    //         'alasan_pengadaan' => [
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => 'Perihal wajib di isi',
    //             ],
    //         ],

    //     ])) {
    //         $validation = \Config\Services::validation();
    //         return redirect()->to("/user/editPengadaan/$id")->withInput()->with('validation', $validation);
    //     }

    //     // Dapatkan data pengadaan dari database
    //     $pengadaan = $this->PengadaanModel->find($id);

    //     // Cek apakah pengadaan dengan id tersebut ditemukan
    //     if (!$pengadaan) {
    //         // Redirect atau tampilkan pesan error jika tidak ditemukan
    //         return redirect()->to('/user/pengadaan')->with('pesanError', 'Pengadaan tidak ditemukan');
    //     }

    //     // Tentukan nilai untuk 'nama_pengaju'
    //     $nama_pengaju = $this->request->getPost('nama_pengaju') == 'anonym' ? 'anonym' : $this->request->getPost('nama_pengaju');

    //     // Persiapkan data untuk disimpan
    //     $dataPengadaan = [
    //         'nama_barang' => $this->request->getPost('nama_barang'),
    //         'jumlah' => $this->request->getPost('jumlah'),
    //         'spesifikasi' => $this->request->getPost('spesifikasi'),
    //         'tahun_periode' => $this->request->getPost('tahun_periode'),
    //         'alasan_pengadaan' => $this->request->getPost('alasan_pengadaan'),
    //         'nama_pengaju' => $nama_pengaju,
    //     ];

    //     // Update data ke database
    //     $this->PengadaanModel->update($id, $dataPengadaan);

    //     // Flashdata pesan berhasil diupdate
    //     session()->setFlashdata('pesanBerhasil', 'Data Pengadaan Berhasil Diupdate');
    //     return redirect()->to('/user/pengadaan');
    // }

    // public function detailPengadaan($id)
    // {

    //     $data = $this->db->table('pengadaan_barang');
    //     $data->select('*');
    //     $data->where('id', $id);
    //     $query = $data->get();

    //     $d = $this->db->table('balasan_pengadaan');
    //     $d->select('*');
    //     $d->where('id_pengadaan', $id);
    //     $balasan = $d->get()->getRow();

    //     // dd($query1);
    //     $ex = [

    //         'detail' => $query->getRow(),
    //         'title' => 'Detail Pengadaan Barang',
    //         'balasan' => $balasan,

    //     ];

    //     return view('user/pengadaan/detail_pengadaan', $ex);
    // }
    // public function deletePengadaan($id)
    // {
    //     $data = [
    //         'validation' => \Config\Services::validation(),
    //         'pengadaan' => $this->PengadaanModel->getpengadaan($id),
    //     ];

    //     $this->PengadaanModel->delete($id);
    //     session()->setFlashdata('pesanBerhasil', 'Data Berhasil Dihapus');

    //     // Redirect ke halaman index
    //     return redirect()->to('/user/pengadaan');
    // }

    // public function printPB() // all data
    // {
    //     $data = [
    //         'pengadaan' => $this->PengadaanModel->getAll(),
    //         'title' => 'Cetak Data',
    //     ];

    //     $dompdf = new \Dompdf\Dompdf();
    //     $options = new \Dompdf\Options();
    //     $options->setIsRemoteEnabled(true);

    //     $dompdf->setOptions($options);
    //     $dompdf->output();
    //     $dompdf->loadHtml(view('user/pengadaan/print', $data));
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();
    //     ini_set('max_execution_time', 0);
    //     $dompdf->stream('Data.pdf', array("Attachment" => false));
    // }
    // public function eksporPB($id) //detail permintaan
    // {
    //     // $aduan = $this->pengaduan->where(['id' => $id])->first();
    //     // $id = $id;
    //     // $data['detail']   = $aduan;
    //     $data['title'] = 'cetak';
    //     $data['detail'] = $this->PengadaanModel->where(['id' => $id])->first();

    //     //Cetak dengan dompdf
    //     $dompdf = new \Dompdf\Dompdf();
    //     ini_set('max_execution_time', 0);
    //     $options = new \Dompdf\Options();
    //     $options->setIsRemoteEnabled(true);

    //     $dompdf->setOptions($options);
    //     $dompdf->output();
    //     $dompdf->loadHtml(view('user/pengadaan/cetakid', $data));
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();
    //     $dompdf->stream('Detail Pengadaan.pdf', array("Attachment" => false));
    // }
    //Akhir Pengadaan
}

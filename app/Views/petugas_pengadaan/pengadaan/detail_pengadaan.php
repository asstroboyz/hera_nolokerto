<?= $this->extend('petugas_pengadaan/templates/index') ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">
    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show fade" role="alert">
                <div class="alert-body">
                    <button class="close" data-dismiss>x</button>
                    <b><i class="fa fa-check"></i></b>
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow card-detail">
                <div class="card-header">
                    <a href="/petugas_pengadaan/pengadaan" class="btn ml-n3 text-primary font-weight-bold"><i
                            class="fas fa-chevron-left"></i> Kembali ke daftar pengadaan</a>
                    <button class="btn btn-primary float-right ml-2" type="button" data-toggle="collapse"
                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa fa-eye rounded-cyrcle"></i> Timeline
                    </button>
                    <?php if ($detail->status == 'belum diproses') : ?>
                    <a href="/petugas_pengadaan/prosesPengadaan/<?= $detail->id ?>"
                        class="text-light btn btn-warning font-weight-bold float-right"><i class="fa fa-clipboard"></i>
                        Proses Laporan</a>
                    <?php elseif ($detail->status == 'diproses') : ?>
                    <div class="btn-group float-right dropleft">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Selesaikan Pengaduan
                        </button>
                        <div class="dropdown-menu">
                            <!-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalPengaduan">
                                Terima
                            </a> -->
                            <button class="dropdown-item " onclick="tampilkanForm()">Terima</button>
                            <button class="dropdown-item tolak" onclick="tampilkanBalasan()">Tolak</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Kolom pertama -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="row">
                                <div class="col-md-4">Nama Barang Yang Diajukan</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->nama_barang?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">Jumlah Pengajuan</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->jumlah?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">Spesifikasi</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->spesifikasi?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">Alasan Pengadaan</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->alasan_pengadaan?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">Diajukan Untuk Tahun Periode </div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->tahun_periode?>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom kedua -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="row">
                                <div class="col-md-4">Nama Barang Yang Diajukan</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->nama_barang?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">Jumlah yang disetujui</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div
                                    class="col-md-6 <?=($detail->jumlah_disetujui == 0 || $detail->jumlah_disetujui < $detail->jumlah || empty($detail->jumlah_disetujui)) ? 'text-danger' : ''?>">
                                    <?=$detail->jumlah_disetujui?>
                                </div>
                            </div>

                            <hr>
                            <!-- <div class="row">
                                <div class="col-md-4">Spesifikasi</div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-6">
                                    <?=$detail->spesifikasi?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">Alasan Pengadaan</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-6">
                            <?=$detail->alasan_pengadaan?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">Diajukan Untuk Tahun Periode </div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-6">
                            <?=$detail->tahun_periode?>
                        </div>
                    </div> -->
                </div>


                <hr>

            </div>

            <hr>
            <div class="accordion" id="accordionExample">
                <div class="">
                    <div class="" id="headingOne">
                        <h5 class="mb-0">

                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse " aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <h1> Tracking Permintaan Barang</h1>
                            <ul class="sessions">
                                <li class="li-diajukan">
                                    <div class="time">
                                        <?=$detail->tgl_pengajuan?>
                                    </div>
                                    <p>Permintaan Diajukan</p>
                                </li>
                                <?php if ($detail->tgl_proses != '0000-00-00 00:00:00') {?>
                                <li class="li-diproses">
                                    <div class="time">
                                        <?=$detail->tgl_proses?>
                                    </div>
                                    <p>Permintaan Diproses </p>
                                </li>
                                <?php }?>
                                <?php if ($detail->tgl_selesai != '0000-00-00 00:00:00') {?>
                                <li class="li-selesai">
                                    <div class="time">09:30 AM</div>
                                    <p>Permintaan Selesai</p>
                                    <p>
                                        Dengan Status:
                                        <?=$detail->status_akhir?>
                                    </p>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php if ($detail->status_akhir == 'ditolak') : ?>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow card-detail">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="btn font-weight-bold display-1 text-dark ml-n3 ">Balasan Permintaan
                            Ditolak
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Kategori</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8 ml-n5">
                            <?= $balasan->kategori ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-3">Balasan</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8 ml-n5">
                            <?= $balasan->balasan_pengadaan; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row balasan mt-2">
            <div class="col-12">
                <div class="card shadow card-detail">
                    <form
                        action="<?= base_url('/petugas_pengadaan/balasPengadaan/' . $detail->id) ?>"
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="btn font-weight-bold display-1 text-dark ml-n3 ">Balasan
                                    Pengadaan
                                    Ditolak
                                </div>
                                <button class="btn btn-primary float-right ml-2 text-white font-weight-bold"><i
                                        class="fa fa-paper-plane rounded-cyrcle"></i> Kirim Balasan</button>
                                <button type="button" class="btn btn-danger float-right" onclick="hideBalasan()"><i
                                        class="fas fa-times-circle"></i> Batal</button>

                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2"> <label for="kategori">Kategori </label></div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-5">
                                    <input type="text" name="kategori" id="kategori"
                                        class="form-control ml-n5 <?= $validation->hasError('kategori') ? 'is-invalid' : ''; ?>"
                                        value="<?= old('kategori'); ?>">
                                    <div class="invalid-feedback ml-n5">
                                        <?= $validation->getError('kategori'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2"> <label for="balasan_pengadaan">Jelaskan lebih
                                        rinci</label>
                                </div>
                                <div class="col-md-1 d-none d-md-block">:</div>
                                <div class="col-md-5">
                                    <textarea name="balasan_pengadaan" id="isi" cols="30" rows="13"
                                        class="form-control ml-n5 <?= $validation->hasError('balasan_pengadaan') ? 'is-invalid' : ''; ?>"><?= old('balasan_pengadaan'); ?></textarea>
                                    <div class="invalid-feedback ml-n5">
                                        <?= $validation->getError('balasan_pengadaan'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row terima mt-2">
            <div class="col-12">
                <div class="card shadow card-detail">
                    <form
                        action="/petugas_pengadaan/accPengadaan/<?= $detail->id ?>"
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="btn font-weight-bold display-1 text-dark ml-n3 ">Balasan
                                    Pengadaan
                                    Diterima</div>
                                <button class="btn btn-primary float-right ml-2 text-white font-weight-bold"><i
                                        class="fa fa-paper-plane rounded-cyrcle"></i> Kirim Balasan</button>
                                <button type="button" class="btn btn-danger float-right" onclick="hideTerima()"><i
                                        class="fas fa-times-circle"></i> Batal</button>

                            </div>

                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="row mb-3">
                                        <div class="col-md-3"> <label for="nama_barang">Nama Barang:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="nama_barang" id="nama_barang"
                                                class="form-control <?= $validation->hasError('nama_barang') ? 'is-invalid' : ''; ?>"
                                                value="<?= $detail->nama_barang ?? ''; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"> <label for="jumlah">Jumlah Pengajuan
                                                Barang:</label></div>
                                        <div class="col-md-9">
                                            <input type="text" name="jumlah" id="jumlah"
                                                class="form-control <?= $validation->hasError('jumlah') ? 'is-invalid' : ''; ?>"
                                                value="<?= $detail->jumlah ?? ''; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="row mb-3">
                                        <div class="col-md-3"> <label for="nama_barang">Nama Barang:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="nama_barang" id="nama_barang"
                                                class="form-control <?= $validation->hasError('nama_barang') ? 'is-invalid' : ''; ?>"
                                                value="<?= $detail->nama_barang ?? ''; ?>"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3"> <label for="jumlah_disetujui">Jumlah Yang Di
                                                setujui:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="number" name="jumlah_disetujui" id="jumlah_disetujui"
                                                class="form-control <?= $validation->hasError('jumlah_disetujui') ? 'is-invalid' : ''; ?>"
                                                value="<?= old('jumlah_disetujui'); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3"> <label for="catatan">Catatan:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea name="catatan" id="catatan"
                                                class="form-control <?= $validation->hasError('catatan') ? 'is-invalid' : ''; ?>"
                                                rows="4"><?= old('catatan'); ?></textarea>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- Modal untuk menanggapi permintaan yang ditolak -->
        <!-- Modal untuk menerima permintaan -->
        <div class="modal fade" id="modalTerima" tabindex="-1" role="dialog" aria-labelledby="modalTerimaLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTerimaLabel">Terima Permintaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form untuk menerima permintaan -->
                        <form action="#" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="mb-3">
                                <label for="kategori">Kategori</label>
                                <input type="text" name="kategori" id="kategori"
                                    class="form-control <?= $validation->hasError('kategori') ? 'is-invalid' : ''; ?>"
                                    value="<?= old('kategori'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('kategori'); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="balasan_pengadaan">Jelaskan lebih rinci</label>
                                <textarea name="balasan_pengadaan" id="isi" cols="30" rows="8"
                                    class="form-control <?= $validation->hasError('balasan_pengadaan') ? 'is-invalid' : ''; ?>"><?= old('balasan_pengadaan'); ?></textarea>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('balasan_pengadaan'); ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="modalPengaduan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Status Laporan</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Tekan "Terima" jika akan mengubah status laporan menjadi
                        diterima
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-success" href="#">Terima</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>

<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert")
            .fadeTo(500, 0)
            .slideUp(500, function() {
                $(this).remove();
            });
    }, 3000);

    $(".balasan").hide();

    function tampilkanBalasan() {
        $(".balasan").show("slow");
        $('html,body').animate({
            scrollTop: document.body.scrollHeight
        }, "slow");
        document.getElementById("kategori").focus();
    };

    function hideBalasan() {
        $(".balasan").hide("slow");
    };

    $(".terima").hide();

    function tampilkanForm() {
        $(".terima").show("slow");
        $('html,body').animate({
            scrollTop: document.body.scrollHeight
        }, "slow");
        document.getElementById("kategori").focus();
    };

    function hideTerima() {
        $(".terima").hide("slow");
    };
    document.addEventListener('DOMContentLoaded', function() {
        var jumlahInput = document.getElementById('jumlah');
        var jumlahDisetujuiInput = document.getElementById('jumlah_disetujui');

        // Mengatur batasan maksimal jumlah_disetujui
        jumlahDisetujuiInput.setAttribute('max', jumlahInput.value);

        // Mendengarkan perubahan pada input jumlah
        jumlahInput.addEventListener('input', function() {
            jumlahDisetujuiInput.setAttribute('max', jumlahInput.value);

            // Memastikan nilai jumlah_disetujui tidak lebih besar dari jumlah
            if (parseInt(jumlahDisetujuiInput.value) > parseInt(jumlahInput.value)) {
                jumlahDisetujuiInput.value = jumlahInput.value;
            }

            // Memastikan nilai jumlah_disetujui tidak 0
            if (parseInt(jumlahDisetujuiInput.value) === 0) {
                jumlahDisetujuiInput.value = 1;
            }
        });

        // Mendengarkan perubahan pada input jumlah_disetujui
        jumlahDisetujuiInput.addEventListener('input', function() {
            // Memastikan nilai jumlah_disetujui tidak lebih besar dari jumlah
            if (parseInt(jumlahDisetujuiInput.value) > parseInt(jumlahInput.value)) {
                jumlahDisetujuiInput.value = jumlahInput.value;
            }

            // Memastikan nilai jumlah_disetujui tidak 0
            if (parseInt(jumlahDisetujuiInput.value) === 0) {
                jumlahDisetujuiInput.value = 1;
            }
        });
    });
</script>

<?= $this->endSection(); ?>
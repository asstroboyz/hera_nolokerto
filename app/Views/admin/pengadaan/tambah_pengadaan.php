<?=$this->extend('admin/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Pengadaan Barang</h1>

    <?php if (session()->getFlashdata('msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?=session()->getFlashdata('msg');?>
            </div>
        </div>
    </div>

    <?php endif;?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header">
                    <a href="/admin/pengadaan">&laquo; Kembali ke daftar pengadaan barang</a>
                </div>
                <div class="card-body">
                    <?= helper('form'); ?>
                    <form
                        action="<?= base_url('/admin/simpanPengadaan') ?> "
                        method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group ">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input name="nama_barang" type="text"
                                        class="form-control form-control-user <?= ($validation->hasError('nama_barang')) ? 'is-invalid' : ''; ?>"
                                        id="input-nama_barang" placeholder="Masukkan Nama Barang"
                                        value="<?= old('nama_barang'); ?>" />
                                    <div id="nama_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_barang'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="spesifikasi">spesifikasi</label>
                                    <textarea name="spesifikasi"
                                        class="form-control form-control-user <?= ($validation->hasError('spesifikasi')) ? 'is-invalid' : ''; ?>"
                                        id="input-spesifikasi"
                                        placeholder="Masukkan spesifikasi Barang"><?= old('spesifikasi'); ?></textarea>
                                    <div id="spesifikasiFeedback" class="invalid-feedback">
                                        <?= $validation->getError('spesifikasi'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="jumlah">Jumlah Barang</label>
                                    <input name="jumlah" type="number"
                                        class="form-control form-control-user <?= ($validation->hasError('jumlah')) ? 'is-invalid' : ''; ?>"
                                        id="input-jumlah" placeholder="Masukkan Jumlah Barang"
                                        value="<?= old('jumlah'); ?>" />
                                    <div id="jumlahFeedback" class="invalid-feedback">
                                        <?= $validation->getError('jumlah'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tahun_periode">Pilih Tahun</label>
                                    <select name="tahun_periode"
                                        class="form-control form-control-user <?= ($validation->hasError('tahun_periode')) ? 'is-invalid' : ''; ?>"
                                        id="input-tahun_periode">
                                        <option value="" disabled selected>Pilih Tahun</option>
                                        <?php
                                    $startYear = date('Y'); // Tahun sekarang
$numOfYears = 15; // Jumlah tahun yang ingin ditampilkan

for ($i = 0; $i < $numOfYears; $i++) {
    $year = $startYear + $i;
    echo "<option value=\"$year\">" . $year . "</option>";
}
?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('tahun_periode'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group ">
                                    <label for="alasan_pengadaan">alasan pengadaan Barang</label>
                                    <textarea name="alasan_pengadaan"
                                        class="form-control form-control-user <?= ($validation->hasError('alasan_pengadaan')) ? 'is-invalid' : ''; ?>"
                                        id="input-alasan_pengadaan" cols="30" rows="13"
                                        placeholder="Masukkan alasan pengadaan Barang"><?= old('alasan_pengadaan'); ?></textarea>
                                    <div id="alasan_pengadaanFeedback" class="invalid-feedback">
                                        <?= $validation->getError('alasan_pengadaan'); ?>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $($this).remove();
        })

    }, 3000);
    // Saat radio button berubah
    $('input[type=radio]').click(function() {
        if ($(this).hasClass('anonym')) {
            $('.nama_pengaju').val('anonym').hide();
        } else {
            $('.nama_pengaju').show();
        }
    });
</script>
<?=$this->endSection();?>
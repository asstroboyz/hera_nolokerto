<?= $this->extend('templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Barang</h1>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header">
                    <a href="/inventaris">&laquo; Kembali ke daftar barang</a>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('/inventaris/simpanqr') ?> " method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group ">
                                    <label for="kode_barang">Kode Barang</label>
                                    <input name="kode_barang" type="text" class="form-control form-control-user <?= ($validation->hasError('kode_barang')) ? 'is-invalid' : ''; ?>" id="input-kode_barang" placeholder="Kode Barang" value="<?= old('kode_barang'); ?>" />
                                    <div id="kode_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('kode_barang'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input name="nama_barang" type="text" class="form-control form-control-user <?= ($validation->hasError('nama_barang')) ? 'is-invalid' : ''; ?>" id="input-nama_barang" placeholder="Nama Barang" value="<?= old('nama_barang'); ?>" />
                                    <div id="nama_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('nama_barang'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="merk">Merk</label>
                                    <input name="merk" type="text" class="form-control form-control-user <?= ($validation->hasError('merk')) ? 'is-invalid' : ''; ?>" id="input-merk" placeholder="Merk Barang" value="<?= old('merk'); ?>" />
                                    <div id="merkFeedback" class="invalid-feedback">
                                        <?= $validation->getError('merk'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="satuan_barang">satuan Barang</label>
                                    <input name="satuan_barang" type="text" class="form-control form-control-user <?= ($validation->hasError('satuan_barang')) ? 'is-invalid' : ''; ?>" id="input-satuan_barang" placeholder="satuan_barang" value="<?= old('satuan_barang'); ?>" />
                                    <div id="tipeFeedback" class="invalid-feedback">
                                        <?= $validation->getError('satuan_barang'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                                <div class="form-group ">
                                    <label for="tipe">Tipe Barang</label>
                                    <input name="tipe" type="text" class="form-control form-control-user <?= ($validation->hasError('tipe')) ? 'is-invalid' : ''; ?>" id="input-tipe" placeholder="Tipe" value="<?= old('tipe'); ?>" />
                                    <div id="tipeFeedback" class="invalid-feedback">
                                        <?= $validation->getError('tipe'); ?>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="jumlah_barang">jumlah Barang</label>
                                    <input name="jumlah_barang" type="text" class="form-control form-control-user <?= ($validation->hasError('jumlah_barang')) ? 'is-invalid' : ''; ?>" id="input-jumlah_barang" placeholder="jumlah_barang" value="<?= old('jumlah_barang'); ?>" />
                                    <div id="jumlah_barangFeedback" class="invalid-feedback">
                                        <?= $validation->getError('jumlah_barang'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="kondisi">Kondisi Barang</label>
                                    <input name="kondisi" type="text" class="form-control form-control-user <?= ($validation->hasError('kondisi')) ? 'is-invalid' : ''; ?>" id="input-kondisi" placeholder="Kondisi" value="<?= old('kondisi'); ?>" />
                                    <div id="kondisiFeedback" class="invalid-feedback">
                                        <?= $validation->getError('kondisi'); ?>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="tgl_perolehan">Tanggal Perolehan</label>
                                    <input name="tgl_perolehan" type="date" class="form-control form-control-user <?= ($validation->hasError('tgl_perolehan')) ? 'is-invalid' : ''; ?>" id="input-tgl_perolehan" value="<?= old('tgl_perolehan'); ?>" />
                                    <div id="tgl_perolehanFeedback" class="invalid-feedback">
                                        <?= $validation->getError('tgl_perolehan'); ?>
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

<?= $this->endSection(); ?>
<?= $this->section('additional-js'); ?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $($this).remove();
        })

    }, 3000);
</script>
<?= $this->endSection(); ?>
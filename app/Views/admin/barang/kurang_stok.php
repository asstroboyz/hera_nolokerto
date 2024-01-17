<?= $this->extend('admin/templates/index'); ?>

<?= $this->section('page-content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Kurang Sok Barang</h1>

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
                    <a href="/admin/atk">&laquo; Kembali ke daftar barang</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?= base_url('/admin/kurangiStok/' . $kodeBarang) ?>"
                        method="post">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_barang_kurang">Kode Barang</label>
                                    <input name="kode_barang_kurang" type="text" class="form-control"
                                        id="kode_barang_kurang"
                                        value="<?= $kodeBarang; ?>"
                                        readonly />
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok Barang Yang Tersedia</label>
                                    <input name="stok" type="text" class="form-control" id="stok"
                                        value="<?= $stok; ?>"
                                        readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_barang_keluar">Tanggal Barang Keluar</label>
                                    <input name="tanggal_barang_keluar" type="date" class="form-control"
                                        id="tanggal_barang_keluar" required />
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_pengurangan_stok">Jumlah Pengurangan Stok</label>
                                    <input name="jumlah_pengurangan_stok" type="number" class="form-control"
                                        id="jumlah_pengurangan_stok" placeholder="Jumlah Pengurangan Stok" />
                                </div>
                            </div>

                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-block">Kurangi Stok</button>
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
            $(this).remove();
        });
    }, 3000);
</script>
<?= $this->endSection(); ?>
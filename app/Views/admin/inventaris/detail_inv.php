<?= $this->extend('admin/templates/index'); ?>

<?= $this->section('page-content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Detail Barang Inventaris</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow p-3">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <img class="card-img-top p-2 border"
                            src="<?= base_url($inventaris->file); ?>"
                            alt="Image profile" height="290">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <!-- <a href="/admin/adm_inventaris" class="btn btn-secondary">&laquo; Kembali ke daftar barang
                            inventaris</a> -->
                        <a href="<?= base_url('admin/cetak_qr_id/' . $inventaris->id); ?>"
                            class="btn btn-success font-weight-bold float-right" target="_blank"><i
                                class="fa fa-print"></i>
                            Print</a>
                        <br>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Nama Barang:</strong>
                                <?= $inventaris->nama_barang; ?>
                            </li>
                            <li class="list-group-item"><strong>Tanggal Perolehan Barang:</strong>
                                <?= $inventaris->tgl_perolehan; ?>
                            </li>
                            <li class="list-group-item"><strong>Merk Barang:</strong>
                                <?= $inventaris->merk; ?>
                            </li>
                            <li class="list-group-item"><strong>Kondisi Barang:</strong>
                                <?= $inventaris->kondisi; ?>
                            </li>
                        </ul>
                        <br>
                        <a href="/admin/adm_inventaris" class="btn btn-secondary">&laquo; Kembali ke daftar barang
                            inventaris</a>
                        <!-- <a href="<?= base_url('admin/cetak_qr_id/' . $inventaris->id); ?>"
                        class="btn btn-success font-weight-bold float-right" target="_blank"><i class="fa fa-print"></i>
                        Print</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('page-content'); ?>
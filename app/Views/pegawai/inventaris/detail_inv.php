<?=$this->extend('pegawai/templates/index');?>


<?=$this->section('page-content');?>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Detail Barang Inventaris</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow px-5 py-4">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <img class="card-img-top p-2"
                            src="<?=base_url($inventaris->file)?>"
                            alt="Image profile" height="290">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-user mr-2"></i> Nama Barang:
                                <?=$inventaris->nama_barang;?>
                            </li>
                            <li class="list-group-item"><i class="fa fa-user mr-2"></i> Tanggal Perolehan Barang:
                                <?=$inventaris->tgl_perolehan;?>
                            </li>
                            <li class="list-group-item"><i class="fa fa-user mr-2"></i> Merk Barang:
                                <?=$inventaris->merk;?>
                            </li>
                            <li class="list-group-item"><i class="fa fa-user mr-2"></i> Kondisi Barang:
                                <?=$inventaris->kondisi;?>
                            </li>
                            <br>
                            <a href="/pegawai/inventaris" class="btn-kembali">&laquo; Kembali ke daftar barang
                                inventaris</a>

                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->endSection('page-content');?>
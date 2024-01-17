<?=$this->extend('admin/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h1 class="h3 mb-4 text-gray-900"><?=$title;?>
                    </h1>
                    <a href="<?php echo base_url('admin/lap_masuk/'); ?>"
                        class="btn btn-success" target="blank"><i class="fa fa-print"></i> Cetak </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>

                                    <th>Nama Barang</th>

                                    <th>Stok Awal</th>
                                    <th>Tanggal Barang Masuk</th>
                                    <th>Jumlah Penambahan</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
foreach ($transaksi_barang as $transaksi): ?>
                                <tr>
                                    <td><?=$i++;?></td>

                                    <td><?=$transaksi['nama_barang'];?>
                                    </td>

                                    <td><?=$transaksi['stok'];?>
                                    </td>
                                    <td><?=$transaksi['tanggal_barang_masuk'];?>
                                    </td>
                                    <td><?=$transaksi['jumlah_perubahan'];?>
                                    </td>

                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?=$this->endSection();?>
<?=$this->extend('pegawai/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->has('pesanBerhasil')): ?>
    <div class="alert alert-success" role="alert">
        <?=session('pesanBerhasil')?>
    </div>
    <?php endif;?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Permintaan Barang </h3>
                    <a href="/pegawai/tambah_permintaan" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                        Permintaan</a>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>

                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Perihal</th>
                                    <th>Satuan</th>
                                    <th>Status</th>
                                    <th>opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                <?php if ($permintaan) {?>
                                <?php foreach ($permintaan as $num => $data) {?>


                                <tr>
                                    <td><?=$num + 1;?></td>
                                    <td><?php $date = date_create($data['tanggal_pengajuan']);?>
                                        <?=date_format($date, "d M Y");?>
                                    </td>
                                    <td><?=$data['nama_barang'];?>
                                    </td>
                                    <td><?=$data['perihal'];?>
                                    </td>

                                    <td><?=$data['satuan_barang'];?>
                                    </td>
                                    <td>
                                        <span
                                            class="btn <?= $data['status'] == 'belum diproses' ? 'btn-danger' : ($data['status'] == 'diproses' ? 'btn-warning' : 'btn-success') ?> text-white">
                                            <?= $data['status']; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <!-- <div class="dropdown show">
                                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </a> -->

                                        <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> -->
                                        <a href="/pegawai/detailpermin/<?=$data['id']?>"
                                            class="  btn btn-primary"><i class="fas fa-eye"></i></a>
                                        <!-- <a href="/user/ubah/<?=$data['id']?>"
                                        class=" btn btn-warning"><i class="fas fa-edit"></i></a> -->



                                        <?php if ($data['status'] == 'belum diproses') {?>



                                        <a href="/pegawai/delete/<?=$data['id']?>"
                                            class="  btn btn-danger"><i class="fas fa-trash"></i></a>

                                        <?php } else {?>

                                        <button class="  btn btn-secondary"><i class="fa fa-trash"></i> </button>
                                        <?php }?>

                                        <!-- </div> -->
                                        <!-- </div> -->

                                    </td>
                                </tr>
                                <?php }?>
                                <!-- end of foreach                -->
                                <?php } else {?>
                                <tr>
                                    <td colspan="4">
                                        <h3 class="text-gray-900 text-center">Data belum ada.</h3>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
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
</script>
<?=$this->endSection();?>
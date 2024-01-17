<?=$this->extend('admin/templates/index');?>

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
                    <h3>Daftar Pengadaan Barang </h3>
                    <a href="/admin/tambah_pengadaan" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                        Pengadaan</a>
                    <!-- <a href="<?php echo base_url('admin/printPB/'); ?>"
                    class="btn btn-success" target="blank"><i class="fa fa-print"></i> Cetak </a> -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tahun Periode</th>
                                    <th>Status</th>
                                    <th>opsi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tahun Periode</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                <?php if ($pengadaan) {?>
                                <?php foreach ($pengadaan as $num => $data) {?>


                                <tr>
                                    <td><?=$num + 1;?></td>
                                    <td><?php $date = date_create($data['tgl_pengajuan']);?>
                                        <?=date_format($date, "d M Y");?>
                                    </td>
                                    <td><?=$data['nama_barang'];?>
                                    </td>
                                    <td><?=$data['jumlah'];?>
                                    </td>

                                    <td><?=$data['tahun_periode'];?>
                                    </td>
                                    <td><span
                                            class="btn <?= $data['status'] === 'belum diproses' ? 'btn-danger' : ($data['status'] === 'diproses' ? 'btn-warning' : 'btn-success') ?> text-white"><?= $data['status']; ?></span>

                                    </td>
                                    <td>
                                        <!-- <div class="dropdown show">
                                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </a> -->

                                        <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> -->
                                        <a href="/admin/detailPengadaan/<?=$data['id']?>"
                                            class="  btn btn-primary"><i class="fas fa-eye"></i></a>
                                        <?php if ($data['status'] == 'belum diproses') {?>

                                        <a href="/admin/editPengadaan/<?=$data['id']?>"
                                            class="  btn btn-success"><i class="fa fa-edit"></i> </a>

                                        <a href="#" class="btn btn-danger" data-toggle="modal"
                                            data-target="#modalKonfirmasiDelete"
                                            data-idpengadaan="<?= $data['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>


                                        <?php } else {?>
                                        <button class="  btn btn-secondary"><i class="fa fa-edit"></i> </button>
                                        <button class="  btn btn-secondary"><i class="fa fa-trash"></i> </button>
                                        <?php }?>
                                        <!-- 
                                                <a href="/admin/deletePengadaan/<?=$data['id']?>"
                                        class=" btn btn-danger"><i class="fas fa-trash"></i></a> -->



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
<div class="modal fade" id="modalKonfirmasiDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengadaan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a id="deleteLink" href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        })
    }, 3000);

    $(document).ready(function() {
        // Mengatur href pada tombol Hapus di modal
        $('#modalKonfirmasiDelete').on('show.bs.modal', function(e) {
            var idPengadaan = $(e.relatedTarget).data('idpengadaan');
            $('#deleteLink').attr('href', '/admin/deletePengadaan/' + idPengadaan);
        });
    });
</script>
<?=$this->endSection();?>
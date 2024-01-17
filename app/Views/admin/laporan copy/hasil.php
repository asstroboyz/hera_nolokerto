<?=$this->extend('admin/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900"></h1>

    <?php if (session()->getFlashdata('error-msg')): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <?=session()->getFlashdata('error-msg');?>
                </div>
            </div>
        </div>
    <?php endif;?>

    <?php if (session()->getFlashdata('msg')): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible show fade" role="alert">

                    <div class="alert-body">
                        <button class="close" data-dismiss>x</button>
                        <b><i class="fa fa-check"></i></b>
                        <?=session()->getFlashdata('msg');?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>

    <div class="row">
        <div class="col-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h3>Daftar Permintaan Barang </h3>

                    <a href="<?php echo base_url('admin/print/'); ?>" class="btn btn-success" target="blank"><i class="fa fa-print"></i> Cetak </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                  <th>ID User</th>
                <th>Kode Barang</th>
                <th>ID Balasan Permintaan</th>
                <th>Nama Pengaju</th>
                <th>Perihal</th>
                <th>Detail</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Diproses</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Status Akhir</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                  <th>ID User</th>
                <th>Kode Barang</th>
                <th>ID Balasan Permintaan</th>
                <th>Nama Pengaju</th>
                <th>Perihal</th>
                <th>Detail</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Diproses</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Status Akhir</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                  <?php foreach ($permintaan as $row): ?>
                <tr>
                    <td><?=$row['id_user'];?></td>
                    <td><?=$row['kode_barang'];?></td>
                    <td><?=$row['id_balasan_permintaan'];?></td>
                    <td><?=$row['nama_pengaju'];?></td>
                    <td><?=$row['perihal'];?></td>
                    <td><?=$row['detail'];?></td>
                    <td><?=$row['tanggal_pengajuan'];?></td>
                    <td><?=$row['tanggal_diproses'];?></td>
                    <td><?=$row['tanggal_selesai'];?></td>
                    <td><?=$row['status'];?></td>
                    <td><?=$row['status_akhir'];?></td>
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
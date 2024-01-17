<?=$this->extend('pegawai/templates/index');?>

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
                    <h3>Daftar Barang </h3>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>

                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan Barang</th>
                                    <th>Stok Barang</th>

                                </tr>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>

                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan Barang</th>
                                    <th>Stok Barang</th>

                                </tr>
                            </tfoot>
                            <tbody>

                                <?php if ($barangs) {?>
                                <?php foreach ($barangs as $num => $data) {?>


                                <tr>

                                    <td><?=$data['kode_barang'];?>
                                    </td>
                                    <td><?=$data['nama_barang'];?>
                                    </td>

                                    <td><?=$data['satuan_barang'];?>
                                    </td>
                                    <?php if ($data['stok'] < 10): ?>
                                    <td style="text-align : center">
                                        <span class="btn btn-danger text-white">
                                            <?=$data['stok'];?>
                                        </span>
                                    </td>
                                    <?php else: ?>
                                    <td style="text-align : center">
                                        <?=$data['stok'];?>
                                    </td>
                                    <?php endif;?>


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
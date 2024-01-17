<?=$this->extend('user/templates/index');?>
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
                    <a href="/user/permintaan" class=" btn ml-n3 text-primary font-weight-bold "><i class="fas fa-chevron-left"></i> Kembali ke daftar Pengadaan Barang</a>
                    <button class="btn btn-primary float-right ml-2 " type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa fa-eye rounded-cyrcle"></i> Timeline
                    </button>
                    <a href="<?php echo base_url('user/eksporPB/' . $detail->id); ?>" class="text-light btn btn-success font-weight-bold float-right" target="blank"><i class="fa fa-print"></i> print</a>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3">Nama Pengaju</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8">
                            <?=$detail->nama_pengaju?>
                        </div>
                    </div>
                    <hr>
                    <div class="row  ">
                        <div class="col-md-3">Status Pengajuan</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8">
                            <?=$detail->status?>

                        </div>

                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-3">Tanggal Pengajuan</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8">
                            <?=$detail->tgl_pengajuan?>
                        </div>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-3">Spesifikasi</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8">
                            <?=$detail->spesifikasi?>
                        </div>
                    </div>
                    <hr>
                    <div class="row ">
                        <div class="col-md-3">Perihal</div>
                        <div class="col-md-1 d-none d-md-block">:</div>
                        <div class="col-md-8">
                            <?=$detail->alasan_pengadaan?>
                        </div>
                    </div>
                    <hr>


                    <div class="accordion" id="accordionExample">
                        <div class="">
                            <div class="" id="headingOne">
                                <h5 class="mb-0">

                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <h1> Tracking Pengadaan Barang</h1>
                                    <ul class="sessions">
                                        <li class="li-diajukan">
                                            <div class="time"> <?=$detail->tgl_pengajuan?></div>
                                            <p>Pengadaan Diajukan</p>
                                        </li>
                                        <?php if ($detail->tgl_proses != '0000-00-00 00:00:00') {?>
                                            <li class="li-diproses">
                                                <div class="time"> <?=$detail->tgl_proses?></div>
                                                <p>Pengadaan Diproses </p>
                                            </li>
                                        <?php }?>
                                        <?php if ($detail->tgl_selesai != '0000-00-00 00:00:00') {?>
                                            <li class="li-selesai">
                                                <div class="time">09:30 AM</div>
                                                <p>Pengadaan Selesai</p>
                                                <p>
                                                    Dengan Status:
                                                    <?=$detail->status_akhir?>
                                                </p>
                                            </li>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php if ($detail->status_akhir == 'ditolak') {?>
        <div class="row   mt-2 ">
            <div class="col-12">

                <div class="card shadow card-detail">


                    <div class="card-body">
                        <div class="mb-3">
                            <div class="btn font-weight-bold display-1  text-dark ml-n3 ">Balasan Pengadaan Ditolak </div>



                        </div>

                        <div class="row">
                            <div class="col-md-3">Kategori</div>
                            <div class="col-md-1 d-none d-md-block">:</div>
                            <div class="col-md-8 ml-n5"><?=$balasan->kategori?></div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-3">balasan</div>
                            <div class="col-md-1 d-none d-md-block">:</div>
                            <div class="col-md-8 ml-n5"><?=$balasan->balasan_permintaan;?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php }?>
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
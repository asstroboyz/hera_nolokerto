<?=$this->extend('pegawai/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Edit Barang</h1>

    <?php if (session()->getFlashdata('msg')): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <?=session()->getFlashdata('msg');?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <a href="/user/permintaan">&laquo; Kembali ke daftar permintaan barang</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?=base_url('/user/update/' . $permintaan['id'])?>"
                        method="post" enctype="multipart/form-data">
                        <?=csrf_field();?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="kode_barang">Pilih Barang</label>
                                    <select name="kode_barang" id="kode_barang" class="form-control">
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barangList as $barang): ?>
                                        <?php $selected = ($barang['kode_barang'] == $permintaan['kode_barang']) ? 'selected' : '';?>
                                        <option
                                            value="<?=$barang['kode_barang'];?>"
                                            <?=$selected;?>>
                                            <?=$barang['nama_barang'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="perihal">Perihal</label>
                                    <input type="text" name="perihal" id="perihal"
                                        class="form-control <?=$validation->hasError('perihal') ? 'is-invalid' : '';?>"
                                        value="<?=$permintaan['perihal'] ?? '';?>"
                                        autofocus>
                                    <div class="invalid-feedback">
                                        <?=$validation->getError('perihal');?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="detail">Jelaskan lebih rinci</label>
                                    <textarea name="detail" id="detail" cols="30" rows="13"
                                        class="form-control <?=$validation->hasError('detail') ? 'is-invalid' : '';?>"><?=$permintaan['detail'] ?? '';?></textarea>
                                    <div class="invalid-feedback">
                                        <?=$validation->getError('detail');?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nama_pengaju">Nama Pengaju Permintaan Barang</label>
                                    <div class="form-check">
                                        <input class="form-check-input anonym" type="radio" name="nama_pengaju"
                                            id="nama_pengaju1" value="anonym"
                                            <?=$permintaan['nama_pengaju'] == 'anonym' ? 'checked' : '';?>>
                                        <label class="form-check-label" for="nama_pengaju1">
                                            <span class="text-gray-800">Samarkan (anonym)</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="nama_pengaju"
                                            id="nama_pengaju2"
                                            value="<?=user()->username;?>"
                                            <?=$permintaan['nama_pengaju'] != 'anonym' ? 'checked' : '';?>>
                                        <label class="form-check-label" for="nama_pengaju2">
                                            <span class="text-gray-800">Gunakan nama sendiri</span>
                                        </label>
                                    </div>
                                    <input type="text" class="form-control nama_pengaju" name="nama_pengaju"
                                        value="<?=$permintaan['nama_pengaju'] ?? '';?>"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-block btn-primary">Update Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    $('.nama_pengaju').hide();
    $('input[type=radio]').click(function() {
        if ($(this).hasClass('anonym')) {
            $('.nama_pengaju').hide()
        } else {
            $('.nama_pengaju').show()
        }
    })
</script>
<?=$this->endSection();?>
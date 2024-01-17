<?=$this->extend('admin/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Barang</h1>

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
        <div class="col-lg-6">

            <div class="card shadow">
                <div class="card-header">
                    <a href="/admin/atk">&laquo; Kembali ke daftar barang</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?=base_url('/admin/tambah')?> "
                        method="post" enctype="multipart/form-data">
                        <?=csrf_field();?>
                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input name="nama_barang" type="text"
                                        class="form-control form-control-user <?=($validation->hasError('nama_barang')) ? 'is-invalid' : '';?>"
                                        id="input-nama_barang" placeholder="Nama Barang"
                                        value="<?=old('nama_barang');?>" />
                                    <div id="nama_barangFeedback" class="invalid-feedback">
                                        <?=$validation->getError('nama_barang');?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php
$jenis_barang_value = old('jenis_barang') ?: 'ATK';
$hidden_attribute = empty(old('jenis_barang')) ? 'hidden' : '';
?>

                                    <input name="jenis_barang" type="text"
                                        class="form-control form-control-user <?=($validation->hasError('jenis_barang')) ? 'is-invalid' : '';?>"
                                        id="input-jenis_barang" placeholder="Jenis Barang"
                                        value="<?=$jenis_barang_value;?>"
                                        <?=$hidden_attribute;?> />
                                    <div id="jenis_barangFeedback" class="invalid-feedback">
                                        <?=$validation->getError('jenis_barang');?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="satuan_barang">Satuan Barang</label>
                                    <select name="satuan_barang"
                                        class="form-control form-control-user <?=($validation->hasError('satuan_barang')) ? 'is-invalid' : '';?>"
                                        id="input-satuan_barang">
                                        <option value="PCS" <?=(old('satuan_barang') == 'PCS') ? 'selected' : '';?>>PCS
                                        </option>
                                        <option value="UNIT" <?=(old('satuan_barang') == 'UNIT') ? 'selected' : '';?>>UNIT
                                        </option>
                                        <option value="BUAH" <?=(old('satuan_barang') == 'BUAH') ? 'selected' : '';?>>BUAH
                                        </option>
                                        <option value="PACK" <?=(old('satuan_barang') == 'PACK') ? 'selected' : '';?>>PACK
                                        </option>
                                        <option value="RIM" <?=(old('satuan_barang') == 'RIM') ? 'selected' : '';?>>RIM
                                        </option>
                                        <option value="LUSIN" <?=(old('satuan_barang') == 'LUSIN') ? 'selected' : '';?>>LUSIN
                                        </option>
                                        <option value="KODI" <?=(old('satuan_barang') == 'KODI') ? 'selected' : '';?>>KODI
                                        </option>
                                    </select>
                                    <div id="satuan_barangFeedback" class="invalid-feedback">
                                        <?=$validation->getError('satuan_barang');?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input name="stok" type="number"
                                        class="form-control form-control-user <?=($validation->hasError('stok')) ? 'is-invalid' : '';?>"
                                        id="input-stok" placeholder="Stok Barang"
                                        value="<?=old('stok');?>" />
                                    <div id="stokFeedback" class="invalid-feedback">
                                        <?=$validation->getError('stok');?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-block btn-primary">Tambah Data</button>
                            </div>
                        </div>
                    </form>
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
            $(this).remove();
        });
    }, 3000);
</script>
<?=$this->endSection();?>
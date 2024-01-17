<?=$this->extend('pegawai/templates/index');?>

<?=$this->section('page-content');?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-900">Form Tambah Permintaan Barang</h1>

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
                    <a href="/pegawai/permintaan">&laquo; Kembali ke daftar barang</a>
                </div>
                <div class="card-body">
                    <form
                        action="<?=base_url('/pegawai/simpanPermin')?> "
                        method="post" enctype="multipart/form-data">
                        <?=csrf_field();?>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="kode_barang">Pilih Barang</label>
                                    <select name="kode_barang" id="kode_barang" class="form-control">
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($barangList as $barang): ?>
                                        <option
                                            value="<?=$barang['kode_barang'];?>"
                                            data-satuan="<?=$barang['satuan_barang'];?>">
                                            <?=$barang['nama_barang'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <p>Satuan: <span id="satuan_barang"></span></p>
                                <div class="form-group">
                                    <label for="jumlah">jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah"
                                        class="form-control  <?=$validation->hasError('jumlah') ? 'is-invalid' : '';?>"
                                        value="<?=old('jumlah');?>"
                                        autofocus>
                                    <div class="invalid-feedback">
                                        <?=$validation->getError('jumlah');?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="perihal">Perihal</label>
                                    <input type="text" name="perihal" id="perihal"
                                        class="form-control  <?=$validation->hasError('perihal') ? 'is-invalid' : '';?>"
                                        value="<?=old('perihal');?>"
                                        autofocus>
                                    <div class="invalid-feedback">
                                        <?=$validation->getError('perihal');?>
                                    </div>
                                </div>


                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="detail">Jelaskan lebih rinci</label>
                                    <textarea name="detail" id="detail" cols="30" rows="13"
                                        class="form-control <?=$validation->hasError('detail') ? 'is-invalid' : '';?>"><?=old('detail');?></textarea>
                                    <div class="invalid-feedback">
                                        <?=$validation->getError('detail');?>
                                    </div>
                                </div>


                            </div>
                            <button class="btn btn-block btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

</div>

<?=$this->endSection();?>
<?=$this->section('additional-js');?>
<script>
    $(document).ready(function() {
        // Saat dropdown 'kode_barang' berubah
        $('#kode_barang').change(function() {
            // Mendapatkan satuan dari data-satuan yang disimpan di opsi terpilih
            var selectedSatuan = $('option:selected', this).data('satuan');

            // Menampilkan satuan di elemen dengan ID 'satuan_barang'
            $('#satuan_barang').text(selectedSatuan);
        });

        // Saat radio button berubah
        $('input[type=radio]').click(function() {
            if ($(this).hasClass('anonym')) {
                $('.nama_pengaju').val('anonym').hide();
            } else {
                $('.nama_pengaju').show();
            }
        });
    });
</script>


<?=$this->endSection();?>
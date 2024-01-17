<?=$this->extend('admin/templates/index');?>


<?=$this->section('page-content');?>

<div class="container-fluid">

    <div class="row">
        <div class="col-12">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-900"></h1>

            <div class="card shadow px-5 py-4">

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <img class="card-img-top p-2"
                            src="<?=empty($user->foto) ? '/img/default.jpg/' : '/img/' . $user->foto;?>"
                            alt="Image profile" height="290">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-user mr-2 icon-success"></i>
                                <span
                                    class="badge badge-<?=($user->name == 'admin') ? 'success' : 'warning';?>">
                                    <?=$user->name;?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-user mr-2 icon-info"></i>
                                <?=$user->username;?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-envelope mr-1 icon-info"></i>
                                <?=$user->email?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-calendar mr-1 mb-3 icon-info"></i>
                                Terdaftar sejak. <?php $date = date_create($user->created_at);
echo (date_format($date, "d F Y H:i:s"))?>
                            </li>
                            <br>
                            <a href="<?=base_url('/admin/kelola_user');?>"
                                class="card-link">&laquo; Back To User List</a>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<?=$this->endSection('page-content');?>
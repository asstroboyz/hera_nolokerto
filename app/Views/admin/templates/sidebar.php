    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
            href="<?=base_url();?>">
            <img src="<?php echo base_url() ?>/assets/img/11.png"
                width="75px" height="75px">
            <div class="sidebar-brand-text mx-3">BPS KOTA
                PEKALONGAN

            </div>
        </a>
        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="<?=base_url();?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link"
                href="<?=base_url('admin/kelola_user');?>">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Daftar Pengguna</span></a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Interface
        </div>



        <!-- Master Data -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Master Data</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Component Data :</h6>

                    <a class="collapse-item"
                        href="<?=base_url('admin/adm_inventaris');?>">Barang
                        Inventaris</a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/atk');?>">Barang
                        ATK</a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/trans_masuk');?>">Barang
                        ATK
                        Masuk</a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/trans_keluar');?>">Barang
                        ATK
                        Keluar</a>
                </div>
            </div>
        </li>

        <!-- Barang-->



        <!-- Divider -->
        <hr class="sidebar-divider">



        <!-- Heading -->
        <div class="sidebar-heading">
            Kelola
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#barang" aria-expanded="true"
                aria-controls="barang">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Permintaan Barang</span>
            </a>
            <div id="barang" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Utilities:</h6>
                    <a class="collapse-item"
                        href="<?=base_url('admin/permintaan');?>">Permintaan
                        Barang </a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/permintaan_masuk');?>">Permintaan
                        Barang Masuk</a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/permintaan_proses');?>">Permintaan
                        Barang Diproses</a>
                    <a class="collapse-item"
                        href="<?=base_url('admin/permintaan_selesai');?>">Permintaan
                        Barang Selesai</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                href="<?=base_url('admin/pengadaan');?>">
                <i class="fas fa-fw fa-table"></i>
                <span>Pengadaan Barang</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
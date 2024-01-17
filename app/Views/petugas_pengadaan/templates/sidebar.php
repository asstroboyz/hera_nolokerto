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
            <a class="nav-link" href="<?= base_url(); ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                href="<?= base_url('petugas_pengadaan/pengadaan'); ?>">
                <i class="fas fa-pen"></i>
                <span>Pengadaan Barang</span></a>
        </li>
        <!-- Pengadaan Barang -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Pengadaan Barang</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Utilities:</h6>
                    <a class="collapse-item"
                        href="<?= base_url('petugas_pengadaan/pengadaan'); ?>">Semua
        PengadaanBarang</a>
        <a class="collapse-item"
            href="<?= base_url('admin/trans_masuk'); ?>">Pengadaan
            Barang Masuk</a>
        <a class="collapse-item"
            href="<?= base_url('admin/trans_keluar'); ?>">Pengadaan
            Barang Diproses</a>
        <a class="collapse-item"
            href="<?= base_url('admin/trans_keluar'); ?>">Pengadaan
            Barang Selesai</a>
        </div>
        </div>
        </li> -->
        <!-- 
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#barang" aria-expanded="true"
                aria-controls="barang">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Laporan Pengadaan Barang</span>
            </a>
            <div id="barang" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Utilities:</h6>
                    <a class="collapse-item"
                        href="<?= base_url('admin/permintaan'); ?>">Permintaan
        Barang </a>
        <a class="collapse-item"
            href="<?= base_url('admin/permintaan_masuk'); ?>">Permintaan
            Barang Masuk</a>
        <a class="collapse-item"
            href="<?= base_url('admin/permintaan_proses'); ?>">Permintaan
            Barang Diproses</a>
        <a class="collapse-item"
            href="<?= base_url('admin/permintaan_selesai'); ?>">Permintaan
            Barang Selesai</a>
        </div>
        </div>
        </li> -->




        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
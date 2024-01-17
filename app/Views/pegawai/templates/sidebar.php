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
        <a class="nav-link"
            href="<?= base_url('pegawai'); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>


    <!-- Laporan-->


    <!-- Pengadaan Barang -->
    <li class="nav-item">
        <a class="nav-link"
            href="<?= base_url('pegawai/inventaris'); ?>">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Inventaris</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="<?= base_url('pegawai/atk'); ?>">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Barang</span></a>
    </li>


    <!-- Permintaan Barang -->
    <li class="nav-item">
        <a class="nav-link"
            href="<?= base_url('pegawai/permintaan'); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Permintaan Barang</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
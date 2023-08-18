<a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
</a>
<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a href="<?= site_url('admin') ?>"><?= $_app_name; ?></a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-header">
            <div class="user-pic">
                <img class="img-responsive img-rounded" src="<?= base_url('assets/img/user-icon.png')?>" alt="User picture">
            </div>
            <div class="user-info">
                <span class="user-name"><?= ucwords($this->session->userdata('nama')); ?></span>
                <span class="user-role"><?= $this->session->userdata('is_admin') == TRUE ? 'admin' : 'user'; ?></span>
                <span class="user-status">
                    <i class="fa fa-circle"></i>
                    <span>Online</span>
                </span>
            </div>
        </div>
        <!-- sidebar-header  -->
        <div class="sidebar-search">
            <div>
                <div class="input-group">
                    <input type="text" class="form-control search-menu" placeholder="Search...">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
            <ul>
                <li class="header-menu">
                    <span>General</span>
                </li>
                <li>
                    <a href="<?= site_url('admin/dashboard') ?>">
                        <i class="fa fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="fa fa-book"></i>
                        <span>Ujian</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a href="<?= site_url('admin/mata-uji') ?>">Mata Uji</a></li>
                            <li><a href="<?= site_url('admin/bank-soal') ?>">Bank Soal</a></li>
                            <li><a href="<?= site_url('admin/ujian') ?>">Jadwal Ujian</a></li>
                            <li><a href="<?= site_url('admin/hasil-ujian') ?>">Hasil Ujian</a></li>
                        </ul>
                    </div>
                </li>
                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="far fa-user"></i>
                        <span>Pengguna</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li><a class="dropdown-item" href="<?= site_url('admin/data-peserta-ujian') ?>">Peserta Ujian</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('admin/data-admin') ?>">Admin</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
        <a href="<?= base_url('admin/akun') ?>">
            <i class="fa fa-users"></i> Pengaturan Akun
        </a>
        <a href="<?= base_url('logout') ?>">
            <i class="fa fa-power-off"></i> Logout
        </a>
    </div>
</nav>

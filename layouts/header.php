<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #ff4d4d;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars" style="color: white;"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search" style="color: white;"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit" style="background-color: white;">
                                <i class="fas fa-search" style="color: #ff4d4d;"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search" style="background-color: white;">
                                <i class="fas fa-times" style="color: #ff4d4d;"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt" style="color: white;"></i>
            </a>
        </li>
        <!-- Dropdown logout -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user" style="color: white;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/destroy.php" class="dropdown-item">
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-danger elevation-4" style="background-color: #ff4d4d;">
    <!-- Sidebar -->
    <div class="sidebar" style="background-color: #fff;">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block" style="color: #ff4d4d;"><?= ucwords($_SESSION['username']) ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Menu options based on user role -->
                <?php if ($_SESSION['akses'] == 'admin') : ?>
                    <li class="nav-item">
                        <a href="<?= $base_admin ?>" class="nav-link">
                            <i class="nav-icon fas fa-th" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_admin . '/dokter' ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-md" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Dokter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_admin . '/pasien' ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-injured" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_admin . '/poli' ?>" class="nav-link">
                            <i class="nav-icon fas fa-hospital" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Poli</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_admin . '/obat' ?>" class="nav-link">
                            <i class="nav-icon fas fa-pills" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Obat</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/destroy.php" class="nav-link">
                            <p style="color: #ff4d4d;">Logout</p>
                        </a>
                    </li>
                <?php elseif ($_SESSION['akses'] == 'dokter') : ?>
                    <li class="nav-item">
                        <a href="<?= $base_dokter ?>" class="nav-link">
                            <i class="nav-icon fas fa-th" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_dokter . '/jadwal_periksa' ?>" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Jadwal Periksa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_dokter . '/memeriksa_pasien' ?>" class="nav-link">
                            <i class="nav-icon fas fa-stethoscope" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Memeriksa Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_dokter . '/riwayat_pasien' ?>" class="nav-link">
                            <i class="nav-icon fas fa-notes-medical" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Riwayat Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_dokter . '/profil' ?>" class="nav-link">
                            <i class="nav-icon fas fa-user" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Profil</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/destroy.php" class="nav-link">
                            <p style="color: #ff4d4d;">Logout</p>
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a href="<?= $base_pasien ?>" class="nav-link">
                            <i class="nav-icon fas fa-th" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= $base_pasien . '/poli' ?>" class="nav-link">
                            <i class="nav-icon fas fa-hospital" style="color: #ff4d4d;"></i>
                            <p style="color: #ff4d4d;">Poli</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/destroy.php" class="nav-link">
                            <p style="color: #ff4d4d;">Logout</p>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

$active = 'dashboard';
// Memastikan bahwa $_GET['active'] aman dari XSS dengan htmlspecialchars dan validasinya dengan in_array
if (isset($_GET['active']) && in_array($_GET['active'], ['admin', 'user', 'lapangan', 'pemesanan', 'pembayaran', 'ulasan', 'buku'])) {
    $active = htmlspecialchars($_GET['active']);
}
?>

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="dashboard.php" class="logo">
                <img
                    src="assets/img/logoFutsal.png"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="70" />
                <p class="h5 fw-bold text-decoration-none text-white">Futsal SK 13</p>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item <?= $active == 'dashboard' ? 'active' : '' ?>">
                    <a href="dashboard.php?active=dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item <?= $active == 'admin' ? 'active' : '' ?>">
                    <a href="admin.php?active=admin">
                        <i class="fas fa-user"></i>
                        <p>Admin</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'user' ? 'active' : '' ?>">
                    <a href="user.php?active=user">
                        <i class="fas fa-users"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'lapangan' ? 'active' : '' ?>">
                    <a href="lapangan.php?active=lapangan">
                        <i class="fas fa-futbol"></i>
                        <p>Daftar Lapangan Bola</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'pemesanan' ? 'active' : '' ?>">
                    <a href="pemesanan.php?active=pemesanan">
                        <i class="fas fa-address-book"></i>
                        <p>Daftar Pemesanan</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'pembayaran' ? 'active' : '' ?>">
                    <a href="daftarPembayaran.php?active=pembayaran">
                        <i class="fas fa-calculator"></i>
                        <p>Daftar Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'ulasan' ? 'active' : '' ?>">
                    <a href="ulasan.php?active=ulasan">
                        <i class="fas fa-inbox"></i>
                        <p>Daftar Ulasan</p>
                    </a>
                </li>
                <li class="nav-item <?= $active == 'buku' ? 'active' : '' ?>">
                    <a href="buku.php?active=buku">
                        <i class="fas fa-book"></i>
                        <p>Buku</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
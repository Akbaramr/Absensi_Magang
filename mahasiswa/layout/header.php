<?php 
global $judul;
require_once ('../../config.php') ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?= $judul ?></title>
     <!-- CSS files -->
     <link href="<?php echo base_url('assets/css/tabler.min.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/css/tabler-vendors.min.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('assets/css/demo.min.css'); ?>" rel="stylesheet"/>
      <!-- FONT AWESOME -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
      }

      .navbar-toggler .close-icon {
  display: none; /* Sembunyikan ikon close secara default */
}

.navbar-toggler.active .navbar-toggler-icon {
  display: none; /* Sembunyikan ikon hamburger */
}

.navbar-toggler.active .close-icon {
  display: inline; /* Tampilkan ikon close */
  font-size: 30px;
  line-height: 0;
}

.nav-icon {
  margin-right: 8px;
  font-size: 18px;
  vertical-align: middle;
}

/* Memastikan profil tetap tampil */
.nav-item.dropdown {
  display: flex;
  align-items: center;
}



    </style>
  </head>
  <body >
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">

      
 <!-- Bootstrap 5 Navbar -->
 <header class="navbar navbar-expand-md navbar-light bg-light d-print-none">
  <div class="container-xl">
    <!-- Tombol Toggle (Mobile) -->
    <button class="navbar-toggler d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
      aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      <span class="close-icon" style="font-size: 30px; display: none;">&times;</span>
    </button>

    <!-- Logo di Desktop -->
    <a class="navbar-brand d-none d-md-block" href=".">
      <img src="<?= base_url('assets/img/PRISMA.png') ?>" width="110" height="32" alt="Logo">
    </a>

    <!-- Logo di Mobile -->
    <a class="navbar-brand mx-auto d-md-none" href=".">
      <img src="<?= base_url('assets/img/PRISMA.png') ?>" width="90" height="28" alt="Logo">
    </a>

    <!-- Profil User (Tetap Muncul di Header) -->
    <div class="nav-item dropdown order-md-last">
      <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown">
        <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
        <div class="d-none d-md-block ps-2">
          <div><?= $_SESSION['nama'] ?></div>
          <div class="mt-1 small text-secondary"><?= $_SESSION['divisi'] ?></div>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <a href="<?= base_url('mahasiswa/fitur_lainnya/profile.php') ?>" class="dropdown-item">Profile</a>
        <a href="<?= base_url('mahasiswa/fitur_lainnya/ubah_password.php') ?>" class="dropdown-item">Ubah Password</a>
        <a href="<?= base_url('auth/logout.php') ?>" class="dropdown-item text-danger">Logout</a>
      </div>
    </div>

    <!-- Navbar Items -->
    <div class="collapse navbar-collapse" id="navbar-menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('mahasiswa/home/home.php') ?>">
            <i class="bi bi-house-door nav-icon"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('mahasiswa/presensi/rekap_presensi.php') ?>">
            <i class="bi bi-clipboard-data nav-icon"></i> Rekap Presensi
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('mahasiswa/ketidakhadiran/ketidakhadiran.php') ?>">
            <i class="bi bi-x-circle nav-icon"></i> Ketidakhadiran
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="<?= base_url('auth/logout.php') ?>">
            <i class="bi bi-box-arrow-right nav-icon"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</header>




<!-- Tambahkan Bootstrap JavaScript -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var navbarToggler = document.querySelector(".navbar-toggler");
    var navbarIcon = navbarToggler.querySelector(".navbar-toggler-icon");
    var closeIcon = navbarToggler.querySelector(".close-icon");

    navbarToggler.addEventListener("click", function () {
      setTimeout(() => { // Tunggu transisi selesai
        var isExpanded = navbarToggler.getAttribute("aria-expanded") === "true";

        if (isExpanded) {
          navbarToggler.classList.add("active");
          closeIcon.style.display = "inline"; // Tampilkan ikon close
          navbarIcon.style.display = "none"; // Sembunyikan ikon hamburger
        } else {
          navbarToggler.classList.remove("active");
          closeIcon.style.display = "none"; // Sembunyikan ikon close
          navbarIcon.style.display = "inline"; // Tampilkan ikon hamburger
        }
      }, 200);
    });
  });
</script>




<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">








      
      
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  <?= $judul ?>
                </h2>
              </div>
            </div>
          </div>
        </div>
<?php
include_once("../../config/conn.php");
session_start();

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= getenv('APP_NAME') ?> | Dashboard</title>

  <!-- Google Font: Poppins -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <style>
    .card {
      font-family: 'Poppins', sans-serif;
      border-radius: 10px;
    }
  </style>

  <!-- Preloader -->
  
  <?php include "../../layouts/header.php"?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="fw-bold">Halo, <?= htmlspecialchars($nama) ?>!</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><b>Selamat Datang di Poliklinik Nusantara</b></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <p>Poliklinik Nusantara hadir untuk memberikan layanan kesehatan terbaik bagi Anda dan keluarga. Dengan dukungan tenaga medis yang profesional serta fasilitas yang lengkap, kami siap melayani kebutuhan kesehatan Anda dengan sepenuh hati.</p>
                <p>Untuk mendaftar layanan di Poliklinik Nusantara, silakan gunakan menu "Poli" yang tersedia di sidebar. Ikuti langkah-langkah pendaftaran yang sederhana dan mudah. Kami berkomitmen untuk memastikan kenyamanan dan kepuasan Anda selama berada di poliklinik kami.</p>
                <p>Terima kasih telah mempercayakan kesehatan Anda kepada Poliklinik Nusantara. Kami berharap dapat selalu menjadi mitra kesehatan terbaik bagi Anda.</p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  <?php include "../../layouts/footer.php"; ?>
</div>
<!-- ./wrapper -->
<?php include "../../layouts/pluginsexport.php"; ?>
</body>
</html>
<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id = $_SESSION['id'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
// mengambil data dokter dari database berdasarkan dokter yang login
$dokter = query("SELECT * FROM dokter WHERE id = $id")[0];

if (isset($_POST["submit"])) {
  if (ubahDokter($_POST) > 0) {
    $_SESSION['username'] = $_POST['nama'];
    echo "
        <script>
            alert('Data berhasil diubah');
            document.location.href = '../profil';
        </script>
    ";
    session_write_close();
    header("Refresh:0");
    exit;
  } else {
    echo "
        <script>
            alert('Data Gagal diubah');
            document.location.href = '../profil';
        </script>
    ";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Tampilan Header dan Layout -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= getenv('APP_NAME') ?> | Profil</title>

  <?php include "../../../layouts/plugin_header.php" ?>
  
  <style>
    /* Warna merah tua untuk header */
    .card-primary {
      border-color: #ff4c4c !important; /* Border merah tua */
    }

    .card-primary .card-header {
      background-color: #ff4c4c !important; /* Header merah tua */
      color: white !important; /* Teks putih */
      border-bottom: 1px solid #ff4c4c !important;
    }

    /* Tombol warna merah tua */
    .btn-primary {
      background-color: #ff4c4c !important;
      border-color: #ff4c4c !important;
    }

    .btn-primary:hover {
      background-color: #e04343 !important;
      border-color: #e04343 !important;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include "../../../layouts/header.php" ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Profil <?= ucwords($_SESSION['akses']) ?></h1>
            </div>
          </div>
        </div>
      </div>
      <!-- Form Edit Profil Dokter -->
      <section class="content">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Data Dokter</h3>
          </div>
          <form id="editForm" action="" method="POST">
            <input type="hidden" name="id" value="<?= $dokter["id"]; ?>">
            <div class="card-body">
              <div class="form-group">
                <label for="nama">Nama Dokter</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $dokter['nama']; ?>">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat Dokter</label>
                <input type="text" id="alamat" name="alamat" class="form-control" value="<?= $dokter['alamat']; ?>">
              </div>
              <div class="form-group">
                <label for="no_hp">Telepon Dokter</label>
                <input type="number" id="no_hp" name="no_hp" class="form-control" value="<?= $dokter['no_hp']; ?>">
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" name="submit" id="submitButton" class="btn btn-primary" disabled>Simpan Perubahan</button>
              </div>
            </div>
          </form>
        </div>
      </section>

      <script>
        const form = document.getElementById('editForm');
        const inputs = form.querySelectorAll('input');

        const checkChanges = () => {
          let changes = false;
          inputs.forEach(input => {
            if (input.defaultValue !== input.value) {
              changes = true;
            }
          });
          return changes;
        };

        const toggleSubmit = () => {
          const submitButton = document.getElementById('submitButton');
          if (checkChanges()) {
            submitButton.disabled = false;
          } else {
            submitButton.disabled = true;
          }
        };

        inputs.forEach(input => {
          input.addEventListener('input', toggleSubmit);
        });
      </script>
    </div>
    <?php include "../../../layouts/footer.php"; ?>
  </div>
  <?php include "../../../layouts/pluginsexport.php"; ?>
</body>

</html>

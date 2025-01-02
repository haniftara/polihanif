<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['signup']) || isset($_SESSION['login'])) {
  $_SESSION['signup'] = true;
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}

if (isset($_POST['klik'])) {

  if ($_POST['id_jadwal'] == "900") {
    echo "
        <script>
            alert('Jadwal tidak boleh kosong!');
        </script>
    ";
    echo "<meta http-equiv='refresh' content='0>";
  }

  if (daftarPoli($_POST) > 0) {
    echo "
        <script>
            alert('Berhasil mendaftar poli');
        </script>
    ";
  } else {
    echo "
        <script>
            alert('Gagal mendaftar poli');
        </script>
    ";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <title>Daftar Poli | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .action-button {
      min-width: 80px;
    }

    .main-sidebar {
      background-color: #ffffff !important;
      color: #000000 !important;
    }

    .main-sidebar .nav-link,
    .main-sidebar .brand-link {
      color: #000000 !important;
    }

    .main-sidebar .nav-link:hover {
      background-color: #f2f2f2 !important;
    }

    .table th,
    .table td {
      vertical-align: middle;
      text-align: center;
      padding: 12px;
    }

    .form-card {
      max-width: 350px;
      margin: 20px auto;
    }

    .content-wrapper {
      display: flex;
      flex-direction: column;
      padding: 20px;
    }

    .row {
      display: flex;
      gap: 20px;
      justify-content: space-between;
    }

    .col-lg-4,
    .col-lg-4-right {
      flex: 1;
    }

    .col-lg-4-right {
      min-width: 350px;
    }

    /* Card adjustments */
    .card {
      margin-bottom: 20px;
    }

    .card-header.bg-warning {
      background-color: #006bb3 !important;
      color: rgb(255, 255, 255);
    }

    .card-header {
      color: white !important;
    }

    .table {
      margin-top: 20px;
      border-collapse: collapse;
      width: 100%;
    }

    /* Styling header tabel dengan warna latar belakang yang lebih terang */
    .table th {
      background-color: #f8f9fa;
      font-weight: bold;
      color: #6c757d;
    }

    /* Mengatur tampilan baris tabel untuk interaksi lebih baik */
    .table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    /* Responsivitas tabel */
    @media (max-width: 768px) {

      .table th,
      .table td {
        padding: 8px;
        font-size: 14px;
      }

      .table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
    }

    .breadcrumb {
      background: none;
    }

    /* Styling agar Riwayat Pemeriksaan Poli berada di bawah History Poli */
    .riwayat-section {
      display: flex;
      flex-direction: column;
    }

    .riwayat-section .card {
      margin-top: 20px;
    }

    .alert-warning {
      background-color: rgb(255, 0, 0) !important;
      /* Ganti dengan warna yang diinginkan */
      color: white !important;
      /* Opsional: Ganti warna teks */
      border-color: #ddd !important;
      /* Opsional: Ganti warna border */
    }


    /* Menyesuaikan layout ketika lebar layar kecil */
    @media (max-width: 992px) {
      .row {
        flex-direction: column;
      }

      .col-lg-4,
      .col-lg-4-right {
        width: 100%;
      }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <?php include "../../../layouts/header.php"; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Daftar Poli</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Daftar Poli</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            
            <!-- Form Daftar Poli -->
            <div class="col-lg-4">
              <div class="card form-card">
                <h5 class="card-header bg-warning">Daftar Poli</h5>
                <div class="card-body">
                  <form action="" method="POST">
                    <input type="hidden" value="<?= $id_pasien ?>" name="id_pasien">
                    <div class="mb-3">
                      <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                      <input type="text" class="form-control" id="no_rm" value="<?= $no_rm ?>" disabled>
                    </div>
                    <div class="mb-3">
                      <label for="inputPoli" class="form-label">Pilih Poli</label>
                      <select id="inputPoli" class="form-control">
                        <option>Pilih Poli</option>
                        <?php
                        $data = $pdo->prepare("SELECT * FROM poli");
                        $data->execute();
                        if ($data->rowCount() == 0) {
                          echo "<option>Tidak ada poli</option>";
                        } else {
                          while ($d = $data->fetch()) {
                            echo "<option value='{$d['id']}'>{$d['nama_poli']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="inputJadwal" class="form-label">Pilih Jadwal</label>
                      <select id="inputJadwal" class="form-control" name="id_jadwal">
                        <option value="900">Pilih Jadwal</option>
                        <?php
                        $jadwal = $pdo->prepare("SELECT * FROM jadwal_periksa");
                        $jadwal->execute();
                        if ($jadwal->rowCount() == 0) {
                          echo "<option>Tidak ada jadwal</option>";
                        } else {
                          while ($j = $jadwal->fetch()) {
                            echo "<option value='{$j['id']}'>{$j['hari']}, {$j['jam_mulai']} - {$j['jam_selesai']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="keluhan" class="form-label">Keluhan</label>
                      <textarea class="form-control" id="keluhan" rows="3" name="keluhan"></textarea>
                    </div>
                    <button type="submit" name="klik" class="btn btn-primary w-100">Daftar</button>
                  </form>
                </div>
              </div>
            </div>

            <!-- History Poli & Riwayat Pemeriksaan Poli -->
            <div class="col-lg-4-right">
              <div class="card">
                <h5 class="card-header bg-warning">History Poli</h5>
                <div class="card-body">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Keluhan</th>
                        <th>Antrian</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama, c.nama as dokter_nama, b.hari as jadwal_hari,
                        b.jam_mulai as jadwal_mulai, b.jam_selesai as jadwal_selesai, a.keluhan as keluhan, a.no_antrian as antrian, a.id as poli_id
                        FROM daftar_poli as a
                        INNER JOIN jadwal_periksa as b ON a.id_jadwal = b.id
                        INNER JOIN dokter as c ON b.id_dokter = c.id
                        INNER JOIN poli as d ON c.id_poli = d.id
                        WHERE a.id_pasien = $id_pasien
                        ORDER BY a.id DESC");
                      $poli->execute();
                      $no = 1;
                      if ($poli->rowCount() == 0) {
                        echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
                      } else {
                        while ($row = $poli->fetch()) {
                          echo "
                          <tr>
                            <td>{$no}</td>
                            <td>{$row['poli_nama']}</td>
                            <td>{$row['dokter_nama']}</td>
                            <td>{$row['jadwal_hari']}</td>
                            <td>{$row['jadwal_mulai']}</td>
                            <td>{$row['jadwal_selesai']}</td>
                            <td>{$row['keluhan']}</td>
                            <td>{$row['antrian']}</td>
                          </tr>";
                          $no++;
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Riwayat Pemeriksaan Poli -->
              <div class="card">
                <h5 class="card-header bg-warning">Riwayat Pemeriksaan Poli Pasien</h5>
                <div class="card-body">
                  <?php
                  try {
                    $query = $pdo->prepare("SELECT 
                                              pr.tgl_periksa,
                                              pr.catatan,
                                              pr.biaya_periksa,
                                              d.nama AS nama_dokter,
                                              dpo.keluhan,
                                              GROUP_CONCAT(o.nama_obat SEPARATOR ', ') AS obat
                                          FROM periksa pr
                                          LEFT JOIN daftar_poli dpo ON pr.id_daftar_poli = dpo.id
                                          LEFT JOIN jadwal_periksa jp ON dpo.id_jadwal = jp.id
                                          LEFT JOIN dokter d ON jp.id_dokter = d.id
                                          LEFT JOIN pasien p ON dpo.id_pasien = p.id
                                          LEFT JOIN detail_periksa dp ON pr.id = dp.id_periksa
                                          LEFT JOIN obat o ON dp.id_obat = o.id
                                          WHERE dpo.id_pasien = :id_pasien
                                          GROUP BY pr.id
                                          ORDER BY pr.tgl_periksa DESC");
                    $query->bindParam(':id_pasien', $id_pasien, PDO::PARAM_INT);
                    $query->execute();

                    if ($query->rowCount() == 0) {
                      echo "<div class='alert alert-warning mt-4 text-center'>Tidak ada riwayat pemeriksaan.</div>";
                    } else {
                      echo '<table class="table table-bordered table-hover text-center">';
                      echo '<thead>
                                  <tr>
                                    <th>No</th>
                                    <th>Dokter</th>
                                    <th>Keluhan</th>
                                    <th>Catatan</th>
                                    <th>Obat</th>
                                    <th>Biaya Periksa</th>
                                    <th>Tanggal Pemeriksaan</th>
                                  </tr>
                                </thead>';
                      echo '<tbody>';
                      $no = 1;
                      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                      <td>{$no}</td>
                                      <td>" . htmlspecialchars($row['nama_dokter']) . "</td>
                                      <td>" . htmlspecialchars($row['keluhan']) . "</td>
                                      <td>" . htmlspecialchars($row['catatan']) . "</td>
                                      <td>" . htmlspecialchars($row['obat']) . "</td>
                                      <td>Rp " . number_format($row['biaya_periksa'], 0, ',', '.') . "</td>
                                      <td>" . htmlspecialchars($row['tgl_periksa']) . "</td>
                                    </tr>";
                        $no++;
                      }
                      echo '</tbody>';
                      echo '</table>';
                    }
                  } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include "../../../layouts/footer.php"; ?>
  </div>

  <script>
  // Ketika dropdown Poli berubah, ambil jadwal berdasarkan poli yang dipilih
  document.getElementById('inputPoli').addEventListener('change', function () {
    var poliId = this.value; // Ambil ID poli yang dipilih
    loadJadwal(poliId); // Panggil fungsi untuk memuat jadwal
  });

  // Fungsi untuk memuat jadwal berdasarkan poli ID
  function loadJadwal(poliId) {
    // Buat permintaan AJAX menggunakan XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_jadwal.php?poli_id=' + poliId, true); // Kirim parameter poli_id
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Masukkan data jadwal ke dalam dropdown inputJadwal
        document.getElementById('inputJadwal').innerHTML = xhr.responseText;
      } else {
        console.error('Gagal memuat jadwal:', xhr.statusText); // Debug jika ada error
      }
    };
    xhr.onerror = function () {
      console.error('Terjadi kesalahan jaringan'); // Debug jika ada masalah jaringan
    };
    xhr.send(); // Kirim permintaan
  }
</script>

</body>

</html>
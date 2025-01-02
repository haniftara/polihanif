<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'dokter') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<?php
$title = 'Poliklinik | Riwayat Pasien';
// Breadcrumb section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
  <li class="breadcrumb-item active">Riwayat Pasien</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Riwayat Pasien
<?php
$main_title = ob_get_clean();
ob_flush();

// Membuat tabel dengan kolom: Nomor, Nama Pasien, Alamat, No. KTP, Telepon, No. RM, dan Aksi
ob_start();
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title fw-bold">Daftar Riwayat Pasien</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Pasien</th>
          <th>Alamat</th>
          <th>No. KTP</th>
          <th>No. Telepon</th>
          <th>No. RM</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Mengambil Data Pasien yang hanya memiliki riwayat periksa
        $index = 1;
        $data = $pdo->query("
  SELECT DISTINCT p.id, p.nama, p.alamat, p.no_ktp, p.no_hp, p.no_rm 
  FROM pasien p
  INNER JOIN daftar_poli dpo ON p.id = dpo.id_pasien
  INNER JOIN periksa pr ON dpo.id = pr.id_daftar_poli
  WHERE dpo.status_periksa = 1
");


        if ($data->rowCount() == 0) {
          echo "<tr><td colspan='7' align='center'>Tidak ada data</td></tr>";
        } else {
          while ($d = $data->fetch()) {
        ?>
             <tr>
              <td><?= $index++; ?></td>
              <td><?= $d['nama']; ?></td>
              <td><?= $d['alamat']; ?></td>
              <td><?= $d['no_ktp']; ?></td>
              <td><?= $d['no_hp']; ?></td>
              <td><?= $d['no_rm']; ?></td>
              <td>
              <a href="<?= $base_dokter; ?>/riwayat_pasien/detailriwayat.php?pasien_id=<?= $d['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-external-link"></i>Detail Pasien</a>
              </td>
            </tr>
        <?php }
        } ?>
      </tbody>
    </table>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>

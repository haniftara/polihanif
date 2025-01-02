<?php
include_once("../../../config/conn.php");
session_start();

//Mengecek apakah sesi login telah diatur
if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
  die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

//Hanya pengguna dengan akses admin 
if ($akses != 'admin') {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}
?>
<?php
$title = 'Poliklinik | Obat';
// Breadcrumb section / Pembuatan Breadcrumb dan Judul
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
  <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Tambah / Edit Dokter
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
  <?php
  $nama = '';
  $alamat = '';
  $no_hp = '';
  $id_poli = 0;
  //Pre-populasi Form: Jika ada parameter id, data dokter akan diambil dari database
  if (isset($_GET['id'])) {
    try {
      $stmt = $pdo->prepare("SELECT * FROM dokter WHERE id = :id");
      $stmt->bindParam(':id', $_GET['id']);
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nama = $row['nama'];
        $alamat = $row['alamat'];
        $no_hp = $row['no_hp'];
        $id_poli = $row['id_poli'];
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  ?>
    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
  <?php
  }
  ?>

  <div class="row mt-3">
    <label for="nama" class="form-label fw-bold">
      Nama Dokter
    </label>
    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Dokter" value="<?php echo $nama ?>">
  </div>
  <div class="row mt-3">
    <label for="alamat" class="form-label fw-bold">
      Alamat
    </label>
    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?php echo $alamat ?>">
  </div>
  <div class="row mt-3">
    <label for="no_hp" class="form-label fw-bold">
      No HP
    </label>
    <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No HP" value="<?php echo $no_hp ?>">
  </div>

  <div class="row mt-3">
    <label for="id_poli" class="form-label fw-bold">
      Poli
    </label>
    <select class="form-control" name="id_poli" id="id_poli">
      <?php
      $stmt = $pdo->query("SELECT * FROM poli");
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $selected = ($id_poli == $row['id']) ? 'selected' : '';
        echo "<option value='" . $row['id'] . "' $selected>" . $row['nama_poli'] . "</option>";
      }
      ?>
    </select>
  </div>

  <div class="row d-flex mt-3 mb-3">
    <button type="submit" class="btn btn-primary" style="width: 3cm;" name="simpan">Simpan</button>
  </div>
</form>

<div class="row d-flex mt-3 mb-3">
  <a href="<?= $base_admin . '/dokter' ?>">
    <button class="btn btn-secondary ml-2" style="width: 3cm;">Reset</button>
  </a>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Dokter</h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-striped">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama</th>
          <th scope="col">Alamat</th>
          <th scope="col">No. Hp</th>
          <th scope="col">Poli</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        //Menampilkan Data Dokter
        $result = $pdo->query("SELECT * FROM dokter");
        $no = 1; // Inisialisasi variabel nomor
        while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td><?php echo $no++; ?></td> <!-- Increment setelah ditampilkan -->
            <td><?php echo $data['nama']; ?></td>
            <td><?php echo $data['alamat']; ?></td>
            <td><?php echo $data['no_hp']; ?></td>
            <td>
              <?php
              // Relasi Poli: Nama poli diambil dari tabel poli menggunakan ID poli yang ada di tabel dokter
              $id_poli = $data['id_poli'];
              $poli = $pdo->query("SELECT * FROM poli WHERE id = $id_poli");
              while ($data_poli = $poli->fetch(PDO::FETCH_ASSOC)) {
                echo $data_poli['nama_poli'];
              }
              ?>
            </td>
            <td>
              <a class='btn btn-success rounded-pill px-3' href='?page=dokter&id=<?= $data['id']; ?>'>Ubah</a>
              <a class='btn btn-danger rounded-pill px-3' href='?page=dokter&id=<?= $data['id']; ?>&aksi=hapus' onclick='return confirm("Yakin ingin menghapus data ini?")'>Hapus</a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <?php

    // Proses Simpan Data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
      try {
        if (!empty($_POST['id'])) {
          // Update
          $stmt = $pdo->prepare("UPDATE dokter SET nama = :nama, alamat = :alamat, no_hp = :no_hp, id_poli = :id_poli WHERE id = :id");
          $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
          // Insert
          $stmt = $pdo->prepare("INSERT INTO dokter (nama, alamat, no_hp, id_poli) VALUES (:nama, :alamat, :no_hp, :id_poli)");
        }

        $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
        $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
        $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_STR);
        $stmt->bindParam(':id_poli', $_POST['id_poli'], PDO::PARAM_INT);

        if ($stmt->execute()) {
          echo "<script>alert('Data berhasil disimpan.');</script>";
          echo "<meta http-equiv='refresh' content='0; url=index.php?page=dokter'>";
        }
      } catch (PDOException $e) {
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
      }
    }

    // Proses Hapus Data
    if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
      try {
        $stmt = $pdo->prepare("DELETE FROM dokter WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
          echo "<script>alert('Data berhasil dihapus.');</script>";
          echo "<meta http-equiv='refresh' content='0; url=index.php?page=dokter'>";
        }
      } catch (PDOException $e) {
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
      }
    }

    ?>
  </div>
</div>
<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>
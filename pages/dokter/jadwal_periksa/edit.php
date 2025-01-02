<?php
include_once "../../../config/conn.php";
session_start();

// Validasi session login
if (isset($_SESSION['login'])) {
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];
$id_dokter = $_SESSION['id'];

// Validasi akses dokter
if ($akses != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}

// Mendapatkan ID jadwal dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan'); document.location.href = '../jadwal_periksa';</script>";
    die();
}

// Mendapatkan data jadwal periksa berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM jadwal_periksa WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$jadwal = $stmt->fetch();

if (!$jadwal) {
    echo "<script>alert('Data jadwal tidak ditemukan'); document.location.href = '../jadwal_periksa';</script>";
    die();
}

// Proses update data ke database
if (isset($_POST["submit"])) {
    if (empty($_POST["hari"]) || empty($_POST["jam_mulai"]) || empty($_POST["jam_selesai"])) {
        echo "
          <script>
              alert('Data tidak boleh kosong');
              document.location.href = '../jadwal_periksa/edit.php?id=$id';
          </script>";
    } else {
        // Proses pembaruan data jadwal
        $hari = htmlspecialchars($_POST['hari']);
        $jam_mulai = htmlspecialchars($_POST['jam_mulai']);
        $jam_selesai = htmlspecialchars($_POST['jam_selesai']);
        $aktif = htmlspecialchars($_POST['aktif']);

        $stmt = $pdo->prepare("UPDATE jadwal_periksa SET 
                                hari = :hari, 
                                jam_mulai = :jam_mulai, 
                                jam_selesai = :jam_selesai, 
                                aktif = :aktif 
                                WHERE id = :id");
        $stmt->bindParam(':hari', $hari);
        $stmt->bindParam(':jam_mulai', $jam_mulai);
        $stmt->bindParam(':jam_selesai', $jam_selesai);
        $stmt->bindParam(':aktif', $aktif);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "
              <script>
                  alert('Data berhasil diubah');
                  document.location.href = '../jadwal_periksa';
              </script>";
        } else {
            echo "
              <script>
                  alert('Data gagal diubah');
                  document.location.href = '../jadwal_periksa/edit.php?id=$id';
              </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poliklinik | Edit Jadwal Periksa</title>
  
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h3 class="card-title">Edit Jadwal Periksa</h3>
    </div>
    <div class="card-body">
      <form action="" id="editJadwal" method="POST">
        <input type="hidden" name="id_dokter" value="<?= $id_dokter ?>">

        <!-- Input Hari -->
        <div class="form-group">
          <label for="hari">Hari</label>
          <select name="hari" id="hari" class="form-control">
            <option hidden>-- Pilih Hari --</option>
            <?php
              $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
              foreach ($hari as $h): ?>
              <option value="<?= $h ?>" <?= $h == $jadwal['hari'] ? 'selected' : '' ?>><?= $h ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Input Jam Mulai -->
        <div class="form-group">
          <label for="jam_mulai">Jam Mulai</label>
          <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>">
        </div>

        <!-- Input Jam Selesai -->
        <div class="form-group">
          <label for="jam_selesai">Jam Selesai</label>
          <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>">
        </div>

        <!-- Input Status -->
        <div class="form-group">
          <label for="aktif">Status</label>
          <div class="form-check">
            <input type="radio" id="aktif1" class="form-check-input" name="aktif" value="Y" <?= $jadwal['aktif'] == "Y" ? "checked" : "" ?>>
            <label for="aktif1" class="form-check-label">Aktif</label>
          </div>
          <div class="form-check">
            <input type="radio" id="tidak-aktif" class="form-check-input" name="aktif" value="T" <?= $jadwal['aktif'] == "T" ? "checked" : "" ?>>
            <label for="tidak-aktif" class="form-check-label">Tidak Aktif</label>
          </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="d-flex justify-content-end">
          <button type="submit" name="submit" id="submitButton" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  let jam_mulai = $('#jam_mulai');
  let jam_selesai = $('#jam_selesai');

  $('#editJadwal').submit(function (e) {
    if (jam_mulai.val() >= jam_selesai.val()) {
      e.preventDefault();
      alert('Jam mulai tidak boleh lebih dari atau sama dengan jam selesai');
    }
  });
</script>
</body>
</html>

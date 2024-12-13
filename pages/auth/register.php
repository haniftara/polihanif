<?php
session_start();
include_once("../../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = htmlspecialchars($_POST['nama']);
  $alamat = htmlspecialchars($_POST['alamat']);
  $no_ktp = htmlspecialchars($_POST['no_ktp']);
  $no_hp = htmlspecialchars($_POST['no_hp']);

  // Cek apakah pasien sudah terdaftar berdasarkan nomor KTP menggunakan prepared statements
  $check_pasien = $conn->prepare("SELECT id, nama, no_rm FROM pasien WHERE no_ktp = ?");
  $check_pasien->bind_param("s", $no_ktp);
  $check_pasien->execute();
  $result_check_pasien = $check_pasien->get_result();

  if ($result_check_pasien->num_rows > 0) {
    $row = $result_check_pasien->fetch_assoc();
    if ($row['nama'] != $nama) {
      echo "<script>alert('Nama pasien tidak sesuai dengan nomor KTP yang terdaftar.');</script>";
      echo "<meta http-equiv='refresh' content='0; url=register.php'>";
      die();
    }
    $_SESSION['signup'] = true;
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $nama;
    $_SESSION['no_rm'] = $row['no_rm'];
    $_SESSION['akses'] = 'pasien';

    echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
    die();
  }

  // Mendapatkan nomor pasien terakhir
  $get_rm = $conn->prepare("SELECT MAX(SUBSTRING(no_rm, 8)) AS last_queue_number FROM pasien");
  $get_rm->execute();
  $result_rm = $get_rm->get_result();

  if ($result_rm->num_rows > 0) {
    $row_rm = $result_rm->fetch_assoc();
    $lastQueueNumber = $row_rm['last_queue_number'] ? $row_rm['last_queue_number'] : 0;
  } else {
    $lastQueueNumber = 0;
  }
  $tahun_bulan = date("Ym");
  $newQueueNumber = $lastQueueNumber + 1;
  $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);

  $insert = $conn->prepare("INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (?, ?, ?, ?, ?)");
  $insert->bind_param("sssss", $nama, $alamat, $no_ktp, $no_hp, $no_rm);

  if ($insert->execute()) {
    $_SESSION['signup'] = true;
    $_SESSION['id'] = $insert->insert_id;
    $_SESSION['username'] = $nama;
    $_SESSION['no_rm'] = $no_rm;
    $_SESSION['akses'] = 'pasien';

    echo "<meta http-equiv='refresh' content='0; url=../pasien'>";
    die();
  } else {
    echo "Error: " . $insert->error;
  }

  $insert->close();
  $check_pasien->close();
  $get_rm->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id-ID">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Registrasi</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(to right, #ffcccc, #ffeeee);
      animation: backgroundAnimation 6s infinite alternate;
    }

    @keyframes backgroundAnimation {
      0% { background: linear-gradient(to right, #ffcccc, #ffeeee); }
      50% { background: linear-gradient(to right, #ffe6e6, #ffcccc); }
      100% { background: linear-gradient(to right, #ffcccc, #ffeeee); }
    }

    .container {
      display: flex;
      width: 850px;
      height: 650px;
      max-width: 100%;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      animation: fadeIn 1.5s ease-in;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .left-box {
      flex: 1;
      background-color: #d32f2f;
      color: white;
      text-align: center;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .right-box {
      flex: 1;
      background-color: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 50px;
    }

    .form-control {
      width: 100%;
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid #d32f2f;
      border-radius: 50px;
      box-sizing: border-box;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
      border-color: #b71c1c;
      box-shadow: 0 0 10px rgba(183, 28, 28, 0.5);
    }

    .btn-primary {
      background-color: #d32f2f;
      color: white;
      padding: 15px;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      transition: background-color 0.3s, transform 0.3s;
    }

    .btn-primary:hover {
      background-color: #b71c1c;
      transform: translateY(-3px);
    }

    .form-check {
      font-size: 14px;
      color: #555;
      margin-bottom: 15px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        height: auto;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="left-box">
    <h1>Selamat Datang</h1>
    <p>Poliklinik Registrasi Pasien</p>
  </div>
  <div class="right-box">
    <h2>Registrasi</h2>
    <form action="" method="post">
      <input type="text" class="form-control" required placeholder="Nama Lengkap" id="nama" name="nama">
      <input type="text" class="form-control" required placeholder="Alamat" id="alamat" name="alamat">
      <input type="number" class="form-control" required placeholder="No KTP" id="no_ktp" name="no_ktp">
      <input type="number" class="form-control" required placeholder="No HP" id="no_hp" name="no_hp">
      <div class="form-check">
        <input type="checkbox" required id="agreeTerms" name="terms" value="agree">
        <label for="agreeTerms">Saya setuju dengan <a href="#" style="color: #d32f2f;">Syarat & Ketentuan</a></label>
      </div>
      <div class="form-check">
        <label>Sudah punya akun? 
          <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/login-pasien.php" style="color: #d32f2f;">Login</a>
        </label>
      </div>
      <button type="submit" class="btn-primary">Daftar</button>
    </form>
  </div>
</div>
</body>
</html>

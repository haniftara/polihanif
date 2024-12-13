<?php
session_start();
include_once("../../config/conn.php");

if (isset($_SESSION['login'])) {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}

if (isset($_POST['klik'])) {
  $username = stripslashes($_POST['nama']);
  $password = $_POST['alamat'];

  $cek_username = $pdo->prepare("SELECT * FROM pasien WHERE nama = :nama");
  $cek_username->bindParam(':nama', $username, PDO::PARAM_STR);
  try {
    $cek_username->execute();
    if ($cek_username->rowCount() == 1) {
      $baris = $cek_username->fetch(PDO::FETCH_ASSOC);
      if ($password == $baris['alamat']) {
        $_SESSION['login'] = true;
        $_SESSION['id'] = $baris['id'];
        $_SESSION['username'] = $baris['nama'];
        $_SESSION['no_rm'] = $baris['no_rm'];
        $_SESSION['akses'] = 'pasien';
        echo "<meta http-equiv='refresh' content='0; url=../pasien/index.php'>";
        die();
      }
    }
  } catch (PDOException $e) {
    $_SESSION['error'] = $e->getMessage();
    echo "<meta http-equiv='refresh' content='0;'>";
    die();
  }
  $_SESSION['error'] = 'Nama dan Password Tidak Cocok';
  echo "<meta http-equiv='refresh' content='0;'>";
  die();
}
?>
<!DOCTYPE html>
<html lang="id-ID">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Login</title>
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
      height: 550px;
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
      animation: slideInLeft 1.5s ease-in;
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .right-box {
      flex: 1;
      background-color: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 50px;
      animation: slideInRight 1.5s ease-in;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
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

    .alert {
      background-color: #ffebee;
      color: #d32f2f;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 15px;
      animation: fadeInAlert 1.5s ease-in;
    }

    @keyframes fadeInAlert {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        height: auto;
      }

      .left-box, .right-box {
        flex: none;
        padding: 20px;
        text-align: center;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="left-box">
    <h1>Selamat Datang</h1>
    <p>Login Pasien Poliklinik</p>
  </div>
  <div class="right-box">
    <h2>Login</h2>
    <?php if (isset($_SESSION['error'])) { ?>
      <div class="alert"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>
    <form method="POST">
      <input type="text" name="nama" class="form-control" placeholder="Username" required />
      <input type="password" name="alamat" class="form-control" placeholder="Password" required />
      <p style="text-align: center;">Belum punya akun? 
        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/register.php" style="color: #d32f2f;">Daftar</a>
      </p>
      <button type="submit" name="klik" class="btn-primary">Masuk</button>
    </form>
  </div>
</div>
</body>
</html>
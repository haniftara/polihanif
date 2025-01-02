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
  if ($username == 'admin') {
    if ($password == 'admin') {
      $_SESSION['login'] = true;
      $_SESSION['id'] = null;
      $_SESSION['username'] = 'admin';
      $_SESSION['akses'] = 'admin';
      echo "<meta http-equiv='refresh' content='0; url=../admin'>";
      die();
    }
  } else {
    $cek_username = $pdo->prepare("SELECT * FROM dokter WHERE nama = :nama");
    $cek_username->bindParam(':nama', $username, PDO::PARAM_STR);
    try {
      $cek_username->execute();
      if ($cek_username->rowCount() == 1) {
        $baris = $cek_username->fetch(PDO::FETCH_ASSOC);
        if ($password == $baris['alamat']) {
          $_SESSION['login'] = true;
          $_SESSION['id'] = $baris['id'];
          $_SESSION['username'] = $baris['nama'];
          $_SESSION['akses'] = 'dokter';
          echo "<meta http-equiv='refresh' content='0; url=../dokter/index.php'>";
          die();
        }
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = $e->getMessage();
      echo "<meta http-equiv='refresh' content='0;'>";
      die();
    }
  }
  $_SESSION['error'] = 'Username dan Password Tidak Cocok';
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(45deg, rgb(255, 255, 255), rgb(221, 221, 221));
    }

    .container {
      display: flex;
      width: 900px;
      height: 600px;
      max-width: 100%;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .left-box {
      flex: 1;
      background-color: #001f3d;
      color: white;
      text-align: center;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .left-box h1 {
      font-size: 36px;
      margin-bottom: 10px;
      font-weight: 700;
    }

    .left-box p {
      font-size: 18px;
      color: #ffcdd2;
    }

    .right-box {
      flex: 1;
      background-color: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 50px;
      animation: slideInRight 1s ease-in;
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
      border: 1px solid #001f3d;
      border-radius: 50px;
      box-sizing: border-box;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
      border-color: #001f3d;
      box-shadow: 0 0 10px rgba(183, 28, 28, 0.5);
    }

    .btn-primary {
      background-color: #001f3d;
      color: white;
      padding: 15px;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      transition: background-color 0.3s, transform 0.3s;
      display: block; 
      margin: 20px auto; 
      text-align: center; 
      width: 100%;
      max-width: 400px;
    }

    .btn-primary:hover {
      background-color: rgb(0, 56, 112);
      transform: translateY(-3px);
    }

    .alert {
      background-color: #ffebee;
      color: #d32f2f;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 15px;
      animation: fadeInAlert 1s ease;
    }

    @keyframes fadeInAlert {
      from {
        opacity: 0;
        transform: scale(0.9);
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

      .left-box,
      .right-box {
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
      <p>Login untuk Dokter</p>
    </div>
    <div class="right-box">
      <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert"><?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?></div>
      <?php } ?>
      <form method="POST">
        <input type="text" name="nama" class="form-control" placeholder="Username" required />
        <input type="password" name="alamat" class="form-control" placeholder="Alamat" required />
        <button type="submit" name="klik" class="btn-primary">Masuk</button>
      </form>
    </div>
  </div>
</body>

</html>
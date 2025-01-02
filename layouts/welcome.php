<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome - Poliklinik Nusantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Gradasi background */
        .gradient-custom {
            background: linear-gradient(45deg, #001f3d, #004e8c);
            /* Navy to Blue */
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header tulisan di pojok kiri atas */
        .header-text {
            position: absolute;
            top: 20px;
            left: 20px;
            color: black;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10;
        }

        .header-text h1 {
            font-size: 32px;
            margin: 0 0 5px 0;
        }

        /* Layout container */
        .main-container {
            display: flex;
            flex: 1;
            height: calc(100% - 50px);
        }

        /* Gambar di kiri */
        .image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            background-color: #fff;
            padding-bottom: 0px;
        }

        /* Animasi Gambar Bergerak */
        @keyframes move-up-down {

            0%,
            100% {
                transform: translateY(0);
                /* Posisi awal */
            }

            50% {
                transform: translateY(-10px);
                /* Bergerak ke atas */
            }
        }

        .image-container img {
            max-width: 90;
            max-height: 90%;
            object-fit: contain;
            animation: move-up-down 3s ease-in-out infinite;
            /* Tambahkan animasi */
        }

        /* Form di kanan */
        .form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #001f3d, #004e8c);
            /* Gradasi biru gelap */
        }

        /* Card Design */
        .welcome-card {
            border-radius: 1rem;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .welcome-card h2 {
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
        }

        .welcome-card p {
            margin-bottom: 30px;
        }

        .btn-custom {
            background-color: #006bb3;
            color: white;
            border-radius: 1rem;
            width: 100%;
            margin-bottom: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #004e8c;
        }
    </style>
</head>

<body>

    <section class="vh-100 gradient-custom">
        <!-- Header tulisan -->
        <div class="header-text">
            <h1>Poliklinik Nusantara</h1>
        </div>

        <div class="main-container">
            <!-- Bagian gambar -->
            <div class="image-container">
                <img src="images/rs2.png" alt="Gambar Rumah Sakit">
            </div>

            <!-- Bagian form -->
            <div class="form-container">
                <div class="card welcome-card">
                    <div class="card-body text-center">
                        <h2 class="fw-bold text-uppercase">Selamat Datang</h2>
                        <p class="text-muted">Pilih opsi di bawah untuk melanjutkan</p>

                        <!-- Tombol Registrasi -->
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/pages/auth/login-pasien.php" class="btn btn-custom btn-lg">
                            <i class="fas fa-user"></i> Registrasi
                        </a>

                        <!-- Tombol Login -->
                        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/pages/auth/login.php" class="btn btn-custom btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Poliklinik BK</title>
    <link href="./dist/css/stylesku.css" rel="stylesheet" />
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff5f5;
            color: #b71c1c;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navbar styles */
        .navbar {
            background-color: #d32f2f;
            color: white;
            height: 80px; /* Ensure sufficient height for centering */
            display: flex; /* Enable flexbox */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .navbar-brand {
            font-size: 26px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
        }

        .navbar .navbar-brand:hover {
            color: #ffcdd2;
            transform: scale(1.1);
        }

        /* Features section */
        .features {
            padding: 60px 0;
            background-color: white;
        }

        .features .feature-item {
            background-color: #ffebee;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            animation: fadeIn 1s ease;
        }

        .features .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #ffcdd2;
        }

        .features .feature-title {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .features .feature-link {
            color: #d32f2f;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
            transition: color 0.3s, transform 0.3s;
        }

        .features .feature-link:hover {
            color: #b71c1c;
            transform: translateX(5px);
        }

        .icon-arrow-right:before {
            content: "\2192";
        }

        /* Footer styles */
        .footer {
            background-color: #d32f2f;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        .footer p {
            margin: 0;
            font-size: 16px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a class="navbar-brand" href="#">Poliklinik Bimbingan Karir</a>
    </nav>

    <!-- Features Section -->
    <?php if (!$muncul) : ?>
        <section class="features" id="features">
            <div class="container">
                <div class="feature-item">
                    <h2 class="feature-title">Login Sebagai Dokter</h2>
                    <p>Akses login dokter, Silahkan login sebagai dokter</p>
                    <a class="feature-link" href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/login.php">
                        Klik untuk login <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="container">
                <div class="feature-item">
                    <h2 class="feature-title">Login Sebagai Pasien</h2>
                    <p>Akses login Pasien, Silahkan login sebagai Pasien</p>
                    <a class="feature-link" href="http://<?= $_SERVER['HTTP_HOST'] ?>/bk_adel/pages/auth/login-pasien.php">
                        Klik untuk login <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    <?php endif ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; BK 2024 Poliklinik</p>
        </div>
    </footer>
</body>

</html>

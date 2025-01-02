<?php
include_once("../../../config/conn.php");
session_start();

if (!isset($_SESSION['login'])) {
    echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

if ($akses != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
}

if (!isset($_GET['pasien_id'])) {
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    die();
}

$pasien_id = $_GET['pasien_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik | Riwayat Pasien</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="path/to/adminlte.min.css">
    <!-- Bootstrap CSS (included with AdminLTE) -->
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<style>
    .content-header h1 {
        text-align: left; /* Pastikan teks judul berada di kiri */
    }

    .card-title {
        text-align: left; /* Pastikan teks detail judul berada di kiri */
    }
</style>

  
    <!-- Menampilkan judul -->
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Riwayat Pasien</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= $base_dokter; ?>">Home</a></li>
                                <li class="breadcrumb-item active">Riwayat Pasien</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Riwayat Pasien</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        try {
                            // Query untuk memfilter hanya data pasien yang benar-benar sudah mendaftar
                            $query = $pdo->prepare("SELECT 
                                                        p.nama AS 'nama_pasien',
                                                        pr.tgl_periksa,
                                                        pr.catatan,
                                                        pr.biaya_periksa,
                                                        d.nama AS 'nama_dokter',
                                                        dpo.keluhan AS 'keluhan',
                                                        GROUP_CONCAT(o.nama_obat SEPARATOR ', ') AS 'obat'
                                                    FROM periksa pr
                                                    INNER JOIN daftar_poli dpo ON pr.id_daftar_poli = dpo.id
                                                    INNER JOIN jadwal_periksa jp ON dpo.id_jadwal = jp.id
                                                    INNER JOIN dokter d ON jp.id_dokter = d.id
                                                    INNER JOIN pasien p ON dpo.id_pasien = p.id
                                                    LEFT JOIN detail_periksa dp ON pr.id = dp.id_periksa
                                                    LEFT JOIN obat o ON dp.id_obat = o.id
                                                    WHERE dpo.id_pasien = :pasien_id
                                                    AND dpo.status_periksa = 1
                                                    GROUP BY pr.id
                                                    ORDER BY pr.tgl_periksa DESC");
                            $query->bindParam(':pasien_id', $pasien_id, PDO::PARAM_INT);
                            $query->execute();

                            // Cek jika tidak ada data
                            if ($query->rowCount() == 0) {
                                echo "<h5>Tidak Ditemukan Riwayat Periksa</h5>";
                            } else {
                                echo '<div class="grid-container">';
                                echo '<div class="grid-item">No</div>';
                                echo '<div class="grid-item">Tanggal Periksa</div>';
                                echo '<div class="grid-item">Nama Pasien</div>';
                                echo '<div class="grid-item">Nama Dokter</div>';
                                echo '<div class="grid-item">Keluhan</div>';
                                echo '<div class="grid-item">Catatan</div>';
                                echo '<div class="grid-item">Obat</div>';
                                echo '<div class="grid-item">Biaya Periksa</div>';
                                
                                $no = 1;
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<div class='grid-item'>{$no}</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['tgl_periksa']) . "</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['nama_pasien']) . "</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['nama_dokter']) . "</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['keluhan']) . "</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['catatan']) . "</div>";
                                    echo "<div class='grid-item'>" . htmlspecialchars($row['obat']) . "</div>";
                                    echo "<div class='grid-item'>" . formatRupiah($row['biaya_periksa']) . "</div>";
                                    $no++;
                                }
                                echo '</div>';
                            }
                        } catch (PDOException $e) {
                            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
<?php 
$content = ob_get_clean(); // Simpan konten ke variabel $content
include '../../../layouts/index.php'; ?>

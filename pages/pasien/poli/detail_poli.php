<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
    $_SESSION['login'] = true;
} else {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}
$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id_poli = $url[count($url) - 1]; // ID daftar_poli

if ($akses != 'pasien') {
    echo "<meta http-equiv='refresh' content='0; url=..'>";
    die();
}
?>

<?php
$title = 'Poliklinik | Detail Pendaftaran';

// Breadcrumb Section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?=$base_pasien;?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?=$base_pasien . '/poli';?>">Poli</a></li>
    <li class="breadcrumb-item active">Detail Poli</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Detail Poli
<?php
$main_title = ob_get_clean();
ob_flush();

// Content Section
ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">Detail Poli</h3>
    </div>
    <div class="card-body">
    <?php
        // Mengambil data detail pendaftaran
        $poli = $pdo->prepare("
            SELECT 
                dp.nama_poli AS poli_nama,
                d.nama AS dokter_nama,
                jp.hari AS jadwal_hari,
                jp.jam_mulai AS jadwal_mulai,
                jp.jam_selesai AS jadwal_selesai,
                dfp.no_antrian AS antrian,
                dfp.id AS daftar_poli_id
            FROM daftar_poli AS dfp
            INNER JOIN jadwal_periksa AS jp ON dfp.id_jadwal = jp.id
            INNER JOIN dokter AS d ON jp.id_dokter = d.id
            INNER JOIN poli AS dp ON d.id_poli = dp.id
            WHERE dfp.id = ?
        ");
        $poli->execute([$id_poli]);
        
        if ($poli->rowCount() == 0) {
            echo "Tidak ada data.";
        } else {
            $p = $poli->fetch();

            echo "<center>";
            echo "<h5>Nama Poli</h5>";
            echo htmlspecialchars($p['poli_nama']) . "<hr>";
            echo "<h5>Nama Dokter</h5>";
            echo htmlspecialchars($p['dokter_nama']) . "<hr>";
            echo "<h5>Hari</h5>";
            echo htmlspecialchars($p['jadwal_hari']) . "<hr>";
            echo "<h5>Mulai</h5>";
            echo htmlspecialchars($p['jadwal_mulai']) . "<hr>";
            echo "<h5>Selesai</h5>";
            echo htmlspecialchars($p['jadwal_selesai']) . "<hr>";
            echo "<h5>Nomor Antrian</h5>";
            echo "<button class='btn btn-success'>" . htmlspecialchars($p['antrian']) . "</button>";
            echo "<hr>";
            echo "</center>";

            // Mengambil data pemeriksaan untuk pendaftaran ini
            $pemeriksaan = $pdo->prepare("
                SELECT 
                    p.id AS periksa_id,
                    p.catatan AS periksa_catatan,
                    p.biaya_periksa AS biaya_periksa
                FROM periksa AS p
                WHERE p.id_daftar_poli = ?
                LIMIT 1
            ");
            $pemeriksaan->execute([$id_poli]);

            if ($pemeriksaan->rowCount() > 0) {
                $periksa = $pemeriksaan->fetch();

                echo "<center>";
                echo "<h5>Catatan Periksa</h5>";
                echo htmlspecialchars($periksa['periksa_catatan'] ?: 'Belum ada catatan.') . "<hr>";
                echo "<h5>Biaya Periksa</h5>";
                echo htmlspecialchars($periksa['biaya_periksa'] ?: '0') . " IDR<hr>";

                // Mengambil data obat berdasarkan pemeriksaan ini
                $obatQuery = $pdo->prepare("
                    SELECT o.nama_obat 
                    FROM detail_periksa AS dp
                    INNER JOIN obat AS o ON dp.id_obat = o.id
                    WHERE dp.id_periksa = ?
                ");
                $obatQuery->execute([$periksa['periksa_id']]);
                $obatList = $obatQuery->fetchAll(PDO::FETCH_COLUMN);
                $obatStr = $obatList ? implode(", ", $obatList) : 'Tidak ada obat.';

                echo "<h5>Obat</h5>";
                echo htmlspecialchars($obatStr) . "<hr>";
                echo "</center>";
            } else {
                echo "<center><h5>Belum ada pemeriksaan.</h5></center>";
            }
        }
    ?>
    </div>
</div>

<a href="<?=$base_pasien . '/poli';?>" class="btn btn-primary btn-block">Kembali</a>

<?php
$content = ob_get_clean();
ob_flush();
?>

<?php include_once "../../../layouts/index.php"; ?>
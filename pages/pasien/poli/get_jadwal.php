<?php
include_once("../../../config/conn.php");

// Ambil ID poli dari parameter GET
$poliId = isset($_GET['poli_id']) ? $_GET['poli_id'] : null;

// Cek apakah ID poli ada
if ($poliId === null) {
    echo '<option value="900">Poli ID tidak ditemukan</option>';
    exit;
}

try {
    // Query untuk mendapatkan jadwal dokter berdasarkan ID poli
    $dataJadwal = $pdo->prepare("
        SELECT 
            d.nama AS nama_dokter, 
            jp.hari AS hari, 
            jp.id AS id_jadwal, 
            jp.jam_mulai AS jam_mulai, 
            jp.jam_selesai AS jam_selesai
        FROM 
            dokter d
        INNER JOIN 
            jadwal_periksa jp ON d.id = jp.id_dokter
        WHERE 
            d.id_poli = :poli_id 
            AND jp.aktif = 'Y'
    ");
    $dataJadwal->bindParam(':poli_id', $poliId, PDO::PARAM_INT);
    $dataJadwal->execute();

    // Cek hasil query
    if ($dataJadwal->rowCount() == 0) {
        echo '<option value="900">Tidak ada jadwal</option>';
    } else {
        while ($jd = $dataJadwal->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . htmlspecialchars($jd['id_jadwal']) . '">Dokter ' . htmlspecialchars($jd['nama_dokter']) . ' | ' . htmlspecialchars($jd['hari']) . ' | ' . htmlspecialchars($jd['jam_mulai']) . ' - ' . htmlspecialchars($jd['jam_selesai']) . '</option>';
        }
    }
} catch (PDOException $e) {
    echo '<option value="900">Error: ' . htmlspecialchars($e->getMessage()) . '</option>';
}
?>

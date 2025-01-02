<?php
include_once("../../../config/conn.php");
session_start();

// Memastikan admin telah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo "<meta http-equiv='refresh' content='0; url=../auth/login.php'>";
    die();
}

$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

// Batasi akses hanya untuk admin
if ($akses != 'admin') {
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
}

$title = 'Poliklinik | Pasien';

// Breadcrumb section
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
    <li class="breadcrumb-item active">Pasien</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Tambah / Edit Pasien
<?php
$main_title = ob_get_clean();
ob_flush();

// Content section
ob_start();
?>
<?php

// Form Tambah/Edit Pasien
$form_nama = '';
$form_alamat = '';
$form_no_hp = '';
$form_no_ktp = '';
$form_no_rm = '';

if (isset($_GET['id'])) {
    // Jika ID ada, ambil data pasien dari database
    try {
        $stmt = $pdo->prepare("SELECT * FROM pasien WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $form_nama = $row['nama'];
            $form_alamat = $row['alamat'];
            $form_no_hp = $row['no_hp'];
            $form_no_ktp = $row['no_ktp'];
            $form_no_rm = $row['no_rm']; // Ambil nomor RM dari database
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika ID tidak ada (Tambah Baru), generate nomor RM
    try {
        $stmt_no_rm = $pdo->query("SELECT MAX(id) as max_id FROM pasien");
        $max_id = $stmt_no_rm->fetch(PDO::FETCH_ASSOC)['max_id'];
        $next_id = $max_id + 1;
        $form_no_rm = date('Ymd') . '-' . str_pad($next_id, 3, '0', STR_PAD_LEFT); // Generate nomor RM
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<form class="form col" method="POST" action="" name="myForm">
    <input type="hidden" name="id" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
    <div class="row mt-3">
        <label for="nama" class="form-label fw-bold">Nama Pasien</label>
        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Pasien" value="<?= htmlspecialchars($form_nama); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="alamat" class="form-label fw-bold">Alamat</label>
        <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?= htmlspecialchars($form_alamat); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="no_ktp" class="form-label fw-bold">Nomor KTP</label>
        <input type="number" class="form-control" name="no_ktp" id="no_ktp" placeholder="Nomor KTP" value="<?= htmlspecialchars($form_no_ktp); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="no_hp" class="form-label fw-bold">Nomor HP</label>
        <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Nomor HP" value="<?= htmlspecialchars($form_no_hp); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="no_rm" class="form-label fw-bold">Nomor RM</label>
        <input type="text" class="form-control" name="no_rm" id="no_rm" readonly placeholder="Nomor RM" value="<?= htmlspecialchars($form_no_rm); ?>" required>
    </div>
    <div class="row d-flex mt-3 mb-3">
        <button type="submit" class="btn btn-primary" style="width: 3cm;" name="simpan">Simpan</button>
    </div>
</form>

<div class="row d-flex mt-3 mb-3">
    <a href="<?= $base_admin . '/pasien' ?>">
        <button class="btn btn-secondary ml-2" style="width: 3cm;">Reset</button>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pasien</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">No. KTP</th>
                    <th scope="col">No. Hp</th>
                    <th scope="col">No. RM</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $pdo->query("SELECT * FROM pasien ORDER BY id ASC");
                $no = 1;
                while ($data = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$data['nama']}</td>
                        <td>{$data['alamat']}</td>
                        <td>{$data['no_ktp']}</td>
                        <td>{$data['no_hp']}</td>
                        <td>{$data['no_rm']}</td>
                        <td>
                            <a class='btn btn-success rounded-pill px-3' href='?page=pasien&id={$data['id']}'>Ubah</a>
                            <a class='btn btn-danger rounded-pill px-3' href='?page=pasien&id={$data['id']}&aksi=hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                        </td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    try {
        if (!empty($_POST['id'])) {
            // Update data
            $stmt = $pdo->prepare("UPDATE pasien SET nama = :nama, alamat = :alamat, no_ktp = :no_ktp, no_hp = :no_hp WHERE id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Generate nomor RM
            $stmt_no_rm = $pdo->query("SELECT MAX(id) as max_id FROM pasien");
            $max_id = $stmt_no_rm->fetch(PDO::FETCH_ASSOC)['max_id'];
            $next_id = $max_id + 1;
            $generated_no_rm = date('Ymd') . '-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

            // Insert data baru
            $stmt = $pdo->prepare("INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)");
            $stmt->bindParam(':no_rm', $generated_no_rm, PDO::PARAM_STR);
        }

        // Bind parameter
        $stmt->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
        $stmt->bindParam(':alamat', $_POST['alamat'], PDO::PARAM_STR);
        $stmt->bindParam(':no_ktp', $_POST['no_ktp'], PDO::PARAM_INT);
        $stmt->bindParam(':no_hp', $_POST['no_hp'], PDO::PARAM_INT);

        $stmt->execute();
        echo "<script>alert('Data berhasil disimpan.');</script>";
        echo "<meta http-equiv='refresh' content='0; url=index.php?page=pasien'>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Proses Hapus Data
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM pasien WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        echo "<script>alert('Data berhasil dihapus.');</script>";
        echo "<meta http-equiv='refresh' content='0; url=index.php?page=pasien'>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>
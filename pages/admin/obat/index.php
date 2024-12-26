<?php
include_once("../../../config/conn.php");
session_start();

// Validasi login
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

$title = 'Poliklinik | Obat';

// Breadcrumb section Membuat breadcrumb untuk navigasi
ob_start(); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?= $base_admin; ?>">Home</a></li>
    <li class="breadcrumb-item active">Obat</li>
</ol>
<?php
$breadcrumb = ob_get_clean();
ob_flush();

// Title Section
ob_start(); ?>
Obat
<?php
$main_title = ob_get_clean();
ob_flush();

// Content Section
ob_start();
?>
<form class="form col" method="POST" action="" name="myForm" onsubmit="return(validate());">
    <?php
    $id = $nama_obat = $kemasan = $harga = '';
    //Form Tambah/Edit Data Obat
    if (isset($_GET['id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $id = $row['id'];
                $nama_obat = $row['nama_obat'];
                $kemasan = $row['kemasan'];
                $harga = $row['harga'];
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    //Input Data Obat
    ?>
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <div class="row mt-3">
        <label for="nama_obat" class="form-label fw-bold">Nama Obat</label>
        <input type="text" class="form-control" name="nama_obat" id="nama_obat" placeholder="Nama Obat"
            value="<?php echo htmlspecialchars($nama_obat); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="kemasan" class="form-label fw-bold">Kemasan</label>
        <input type="text" class="form-control" name="kemasan" id="kemasan" placeholder="Kemasan"
            value="<?php echo htmlspecialchars($kemasan); ?>" required>
    </div>
    <div class="row mt-3">
        <label for="harga" class="form-label fw-bold">Harga</label>
        <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga"
            value="<?php echo htmlspecialchars($harga); ?>" required>
    </div>
    <div class="row d-flex mt-3 mb-3">
        <button type="submit" class="btn btn-primary rounded-pill" style="width: 3cm;" name="simpan">Simpan</button>
    </div>
</form>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Obat</h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Obat</th>
                    <th scope="col">Kemasan</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Menampilkan Data: Data dari tabel obat 
                try {
                    $stmt = $pdo->query("SELECT * FROM obat ORDER BY id ASC");
                    $no = 1;
                    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$data['nama_obat']}</td>
                            <td>{$data['kemasan']}</td>
                            <td>Rp. " . number_format($data['harga'], 0, ',', '.') . "</td>
                            <td>
                                <a class='btn btn-success rounded-pill px-3' href='?page=obat&id={$data['id']}'>Edit</a>
                                <a class='btn btn-danger rounded-pill px-3' href='?page=obat&id={$data['id']}&aksi=hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                            </td>
                        </tr>";
                        $no++;
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
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
            // Update
            $stmt = $pdo->prepare("UPDATE obat SET nama_obat = :nama_obat, kemasan = :kemasan, harga = :harga WHERE id = :id");
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO obat (nama_obat, kemasan, harga) VALUES (:nama_obat, :kemasan, :harga)");
        }

        $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
        $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
        $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan.');</script>";
            echo "<meta http-equiv='refresh' content='0; url=index.php?page=obat'>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
    }
}

// Proses Hapus Data
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus.');</script>";
            echo "<meta http-equiv='refresh' content='0; url=index.php?page=obat'>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
    }
}
$content = ob_get_clean();
ob_flush();
?>

<?php include '../../../layouts/index.php'; ?>
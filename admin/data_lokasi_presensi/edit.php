<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} elseif ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Edit Data Lokasi Presensi";
include('../layout/header.php');
require_once('../../config.php');

// Validate and sanitize ID parameter
$id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $_SESSION['validasi'] = "ID Lokasi tidak valid!";
    header("Location: lokasi_presensi.php");
    exit;
}

// Fetch existing data using prepared statement
$query = "SELECT * FROM lokasi_presensi WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && $lokasi = mysqli_fetch_array($result)) {
    // Default values from database
    $nama_lokasi = $lokasi['nama_lokasi'];
    $alamat_lokasi = $lokasi['alamat_lokasi'];
    $tipe_lokasi = $lokasi['tipe_lokasi'];
    $latitude = $lokasi['latitude'];
    $longitude = $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_masuk = $lokasi['jam_masuk'];
    $jam_pulang = $lokasi['jam_pulang'];
} else {
    $_SESSION['validasi'] = "Data lokasi tidak ditemukan!";
    header("Location: lokasi_presensi.php");
    exit;
}
mysqli_stmt_close($stmt);

// Handle form submission
if (isset($_POST['update'])) {
    // Ambil nilai dari POST, gunakan operator null coalescing untuk menangani field kosong
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi'] ?? '');
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi'] ?? '');
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi'] ?? '');
    $latitude = htmlspecialchars($_POST['latitude'] ?? '');
    $longitude = htmlspecialchars($_POST['longitude'] ?? '');
    $radius = htmlspecialchars($_POST['radius'] ?? '');
    $zona_waktu = htmlspecialchars($_POST['zona_waktu'] ?? '');
    $jam_masuk = htmlspecialchars($_POST['jam_masuk'] ?? '');
    $jam_pulang = htmlspecialchars($_POST['jam_pulang'] ?? '');

    $pesan_kesalahan = [];

    // Validation logic with detailed error messages
    if (empty(trim($nama_lokasi))) {
        $pesan_kesalahan[] = "Nama lokasi wajib diisi!";
    }
    if (empty(trim($alamat_lokasi))) {
        $pesan_kesalahan[] = "Alamat lokasi wajib diisi!";
    }
    if (empty(trim($tipe_lokasi))) {
        $pesan_kesalahan[] = "Tipe lokasi wajib dipilih!";
    }
    if (empty(trim($latitude)) || !is_numeric($latitude)) {
        $pesan_kesalahan[] = "Latitude wajib diisi dan harus berupa angka!";
    }
    if (empty(trim($longitude)) || !is_numeric($longitude)) {
        $pesan_kesalahan[] = "Longitude wajib diisi dan harus berupa angka!";
    }
    if (empty(trim($radius)) || !is_numeric($radius)) {
        $pesan_kesalahan[] = "Radius wajib diisi dan harus berupa angka!";
    }
    if (empty(trim($zona_waktu))) {
        $pesan_kesalahan[] = "Zona waktu wajib dipilih!";
    }
    if (empty(trim($jam_masuk))) {
        $pesan_kesalahan[] = "Jam masuk wajib diisi!";
    }
    if (empty(trim($jam_pulang))) {
        $pesan_kesalahan[] = "Jam pulang wajib diisi!";
    }

    // Jika ada pesan kesalahan, simpan di session
    if (!empty($pesan_kesalahan)) {
        $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
        // Update data using prepared statement
        $query = "UPDATE lokasi_presensi SET nama_lokasi = ?, alamat_lokasi = ?, tipe_lokasi = ?, latitude = ?, longitude = ?, radius = ?, zona_waktu = ?, jam_masuk = ?, jam_pulang = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $nama_lokasi, $alamat_lokasi, $tipe_lokasi, $latitude, $longitude, $radius, $zona_waktu, $jam_masuk, $jam_pulang, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['berhasil'] = "Data lokasi berhasil diupdate!";
            header("Location: lokasi_presensi.php");
            exit;
        } else {
            $_SESSION['validasi'] = "Gagal mengupdate data: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    }
}

// Tampilkan pesan validasi jika ada
if (isset($_SESSION['validasi'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['validasi'] . '</div>';
    unset($_SESSION['validasi']);
}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl"> 
        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" value="<?= htmlspecialchars($nama_lokasi) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Alamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" value="<?= htmlspecialchars($alamat_lokasi) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Tipe Lokasi</label>
                        <select name="tipe_lokasi" class="form-control">
                            <option value="">--Pilih Tipe Lokasi--</option>
                            <option <?= $tipe_lokasi == 'Pusat' ? 'selected' : '' ?> value="Pusat">Pusat</option>
                            <option <?= $tipe_lokasi == 'Cabang' ? 'selected' : '' ?> value="Cabang">Cabang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<?= htmlspecialchars($latitude) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<?= htmlspecialchars($longitude) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Radius</label>
                        <input type="text" class="form-control" name="radius" value="<?= htmlspecialchars($radius) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Zona Waktu</label>
                        <select name="zona_waktu" class="form-control">
                            <option value="">--Pilih Zona Waktu--</option>
                            <option <?= $zona_waktu == 'WIB' ? 'selected' : '' ?> value="WIB">WIB</option>
                            <option <?= $zona_waktu == 'WITA' ? 'selected' : '' ?> value="WITA">WITA</option>
                            <option <?= $zona_waktu == 'WIT' ? 'selected' : '' ?> value="WIT">WIT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="">Jam Masuk</label>
                        <input type="text" class="form-control" name="jam_masuk" value="<?= htmlspecialchars($jam_masuk) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Jam Pulang</label>
                        <input type="text" class="form-control" name="jam_pulang" value="<?= htmlspecialchars($jam_pulang) ?>">
                    </div>
                    <input type="hidden" value="<?= $id ?>" name="id">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
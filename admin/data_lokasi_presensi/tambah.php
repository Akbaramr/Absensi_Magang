<?php 
session_start();
ob_start(); 
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
} 

$judul = "Tambah Lokasi Presensi";
include('../layout/header.php'); 
require_once('../../config.php');

if (isset($_POST['submit'])) {
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = []; // Ubah menjadi array

        if (empty($nama_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Nama Lokasi Wajib Diisi";
        }
        if (empty($alamat_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Alamat Lokasi Wajib Diisi";
        }
        if (empty($tipe_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Tipe Lokasi Wajib Diisi";
        }
        if (empty($latitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Latitude Wajib Diisi";
        }
        if (empty($longitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Longitude Wajib Diisi";
        }
        if (empty($radius)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Radius Wajib Diisi";
        }
        if (empty($zona_waktu)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Zona Waktu Wajib Diisi";
        }
        if (empty($jam_masuk)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jam Masuk Wajib Diisi";
        }
        if (empty($jam_pulang)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jam Pulang Wajib Diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $pesan_format = "<ul>";
            foreach ($pesan_kesalahan as $pesan) {
                $pesan_format .= "<li>$pesan</li>";
            }
            $pesan_format .= "</ul>";
            $_SESSION['validasi'] = $pesan_format;
        }else {
            // Query Insert Data
            $result = mysqli_query($connection, "INSERT INTO lokasi_presensi 
                (nama_lokasi, alamat_lokasi, tipe_lokasi, latitude, longitude, radius, zona_waktu, jam_masuk, jam_pulang) 
                VALUES ('$nama_lokasi', '$alamat_lokasi', '$tipe_lokasi', '$latitude', '$longitude', '$radius', '$zona_waktu', '$jam_masuk', '$jam_pulang')");

            if ($result) {
                $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
                header("Location: lokasi_presensi.php");
                exit;
            } else {
                $_SESSION['error'] = 'Gagal menyimpan data.';
            }
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
    <?php 
        if (isset($_SESSION['validasi'])): ?>
            <div class="alert alert-danger fade show" role="alert">
                <ul>
                    <?= $_SESSION['validasi']; ?>
                </ul>
            </div>
            <?php unset($_SESSION['validasi']); // Hapus setelah ditampilkan ?>
    <?php endif; ?>
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" method="POST">
                    <div class="mb-3">
                        <label for="nama_lokasi">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" value="<?php if (isset($_POST['nama_lokasi'])) echo htmlspecialchars($_POST['nama_lokasi']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="alamat_lokasi">Alamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" value="<?php if (isset($_POST['alamat_lokasi'])) echo htmlspecialchars($_POST['alamat_lokasi']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tipe_lokasi">Tipe Lokasi</label>
                        <select name="tipe_lokasi" class="form-control">
                            <option value="">--Pilih Tipe Lokasi--</option>
                            <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Pusat') echo 'selected'; ?> value="Pusat">Pusat</option>
                            <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Cabang') echo 'selected'; ?> value="Cabang">Cabang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<?php if (isset($_POST['latitude'])) echo htmlspecialchars($_POST['latitude']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<?php if (isset($_POST['longitude'])) echo htmlspecialchars($_POST['longitude']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="radius">Radius</label>
                        <input type="number" class="form-control" name="radius" value="<?php if (isset($_POST['radius'])) echo htmlspecialchars($_POST['radius']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="zona_waktu">Zona Waktu</label>
                        <select name="zona_waktu" class="form-control">
                            <option value="">--Pilih Zona Waktu--</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIB') echo 'selected'; ?> value="WIB">WIB</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WITA') echo 'selected'; ?> value="WITA">WITA</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIT') echo 'selected'; ?> value="WIT">WIT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" class="form-control" name="jam_masuk" value="<?php if (isset($_POST['jam_masuk'])) echo htmlspecialchars($_POST['jam_masuk']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="jam_pulang">Jam Pulang</label>
                        <input type="time" class="form-control" name="jam_pulang" value="<?php if (isset($_POST['jam_pulang'])) echo htmlspecialchars($_POST['jam_pulang']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
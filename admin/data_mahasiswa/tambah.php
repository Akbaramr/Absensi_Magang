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

$judul = "Tambah Mahasiswa";
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
            $pesan_kesalahan[] = "Nama Lokasi Wajib Diisi";
        }
        if (empty($alamat_lokasi)) {
            $pesan_kesalahan[] = "Alamat Lokasi Wajib Diisi";
        }
        if (empty($tipe_lokasi)) {
            $pesan_kesalahan[] = "Tipe Lokasi Wajib Diisi";
        }
        if (empty($latitude)) {
            $pesan_kesalahan[] = "Latitude Wajib Diisi";
        }
        if (empty($longitude)) {
            $pesan_kesalahan[] = "Longitude Wajib Diisi";
        }
        if (empty($radius)) {
            $pesan_kesalahan[] = "Radius Wajib Diisi";
        }
        if (empty($zona_waktu)) {
            $pesan_kesalahan[] = "Zona Waktu Wajib Diisi";
        }
        if (empty($jam_masuk)) {
            $pesan_kesalahan[] = "Jam Masuk Wajib Diisi";
        }
        if (empty($jam_pulang)) {
            $pesan_kesalahan[] = "Jam Pulang Wajib Diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = $pesan_kesalahan;
        } else {
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
        <?php if (isset($_SESSION['validasi'])): ?>
            <script>
                const errors = <?= json_encode($_SESSION['validasi']); ?>;
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: errors.map(error => `<li>${error}</li>`).join(''),
                    icon: 'error',
                });
            </script>
            <?php unset($_SESSION['validasi']); ?>
        <?php endif; ?>

        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" method="POST">
                    
                <?php
                $ambil_nim = mysqli_query($connection, "SELECT nim FROM mahasiswa ORDER BY nim DESC LIMIT 1");

                if(mysqli_num_rows($ambil_nim) > 0){
                    $row = mysqli_fetch_assoc($ambil_nim);
                    $nim_db = $row['nim'];
                    $nim_db = explode ("-", $nim_db);
                    $no_baru = (int)$nim_db[1] + 1;
                    $nim_baru = "MHS-". str_pad($no_baru, 4, 0, STR_PAD_LEFT);
                }else{
                    $nim_baru = "MHS-0001";
                }
                 
                ?>

                <div class="mb-3">
                        <label for="">NIM</label>
                        <input type="text" class="form-control" name="nim" value="<?=$nim_baru?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="nama" value="<?php if (isset
                        ($_POST['nama'])) echo ($_POST['nama']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">--Pilih Jenis kelamin--</option>
                            <option <?php if (isset($_POST['jenis_kelamin']) && $_POST
                            ['jenis_kelamin'] == 'Laki-Laki') {
                                    echo 'selected'; 
                                }?> value="Laki-Laki">Laki-Laki</option>

                            <option <?php if (isset($_POST['jenis_kelamin']) && $_POST
                            ['jenis_kelamin'] == 'Perempuan') {
                                    echo 'selected'; 
                                }?> value="Perempuan">Perempuan</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Alamat</label>
                        <input type="text" class="form-control" name="alamat" value="<?php if (isset
                        ($_POST['alamat'])) echo htmlspecialchars($_POST['alamat']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="">Jabatan</label>
                        <select name="" class="form-control">
                            <option value="">--Pilih Jabatan--</option>

                    <?php
                            $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY 
                            jabatan ASC");

                            while($jabatan = mysqli_fetch_assoc($ambil_jabatan)){
                                $nama_jabatan = $jabatan['jabatan'];

                                if (isset($_POST['jabatan']) && $_POST['jabatan'] == $nama_jabatan) {
                                    // Jika sama, tambahkan atribut selected
                                    echo '<option value="' . htmlspecialchars($nama_jabatan) . '" selected="selected">' . htmlspecialchars($nama_jabatan) . '</option>';
                                } else {
                                    // Jika tidak, tampilkan sebagai opsi biasa
                                    echo '<option value="' . htmlspecialchars($nama_jabatan) . '">' . htmlspecialchars($nama_jabatan) . '</option>';
                                }
                            }

                            ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="">--Pilih Status--</option>
                            <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Aktif') {
                                    echo 'selected'; 
                                }?> value="Aktif">Aktif</option>

                            <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Tidak Aktif') {
                                    echo 'selected'; 
                                }?> value="Tidak Aktif">Tidak Aktif</option>

                        </select>
                    </div>

                    
                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
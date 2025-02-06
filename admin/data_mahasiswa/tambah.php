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
    $nim = htmlspecialchars($_POST['nim']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $divisi = isset($_POST['divisi']) ? htmlspecialchars($_POST['divisi']) : '';
    $universitas = isset($_POST['universitas']) ? htmlspecialchars($_POST['universitas']) : '';
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = isset($_POST['lokasi_presensi']) ? htmlspecialchars($_POST['lokasi_presensi']) : ''; 
    $foto = ''; 
// Pastikan variabel selalu ada sebelum digunakan
$ekstensi_diizinkan = ["jpg", "jpeg", "png"]; // Ekstensi yang diperbolehkan
$ambil_ekstensi = ''; // Pastikan ini selalu ada
$ukuran_file = 0; // Default untuk menghindari error
$max_ukuran_file = 5 * 1024 * 1024; // 5MB

// File Upload Validation
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $file = $_FILES['foto'];
    $nama_file = $file['name'];
    $file_tmp = $file['tmp_name'];
    $ukuran_file = $file['size'];
    $file_direktori = "../../assets/img/foto_mahasiswa/" . $nama_file;

    // Ambil ekstensi file
    $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);

    if (in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
        if ($ukuran_file <= $max_ukuran_file) {
            move_uploaded_file($file_tmp, $file_direktori);
            $foto = $nama_file;
        } else {
            $pesan_kesalahan[] = "Ukuran file tidak boleh melebihi 5 MB";
        }
    } else {
        $pesan_kesalahan[] = "Hanya File JPG, JPEG, dan PNG yang Diperbolehkan";
    }
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = [];

        if (empty($nama)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Nama Wajib Diisi";
        }
        if (empty($jenis_kelamin)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jenis Kelamin Wajib Diisi";
        }
        if (empty($alamat)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Alamat Wajib Diisi";
        }
        if (empty($no_handphone)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Nomor Handphone Wajib Diisi";
        }
        if (empty($divisi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Divisi Wajib Diisi";
        }
        if (empty($username)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Username Wajib Diisi";
        }
        if (empty($role)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Role Wajib Diisi";
        }
        if (empty($status)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Status Wajib Diisi";
        }
        if (empty($lokasi_presensi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Lokasi Presensi Wajib Diisi";
        }
        if (empty($password)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Password Wajib Diisi";
        }
        if ($_POST['password'] != $_POST['konfirmasi_password']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Password Tidak Sama";
        }
        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya File JPG, JPEG, dan PNG yang Diperbolehkan";
        }
        if ($ukuran_file > $max_ukuran_file){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file tidak boleh melebihi 5 MB";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Query Insert Data
            $mahasiswa = mysqli_query($connection, "INSERT INTO mahasiswa 
                (nim, nama, jenis_kelamin, alamat, no_handphone, divisi, universitas, lokasi_presensi, foto) 
                VALUES ('$nim', '$nama', '$jenis_kelamin', '$alamat', '$no_handphone', '$divisi', '$universitas', '$lokasi_presensi', '$foto')");        

            if ($mahasiswa) {
                $id_mahasiswa = mysqli_insert_id($connection);
                $user = mysqli_query($connection, "INSERT INTO users 
                    (id_mahasiswa, username, password, status, role) 
                    VALUES ('$id_mahasiswa', '$username', '$password', '$status', '$role')");

                if ($user) {
                    $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
                    header("Location: mahasiswa.php");
                    exit;
                } else {
                    $_SESSION['error'] = 'Gagal menyimpan data user.';
                }
            } else {
                $_SESSION['error'] = 'Gagal menyimpan data mahasiswa.';
            }
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
    <form action="<?= base_url('admin/data_mahasiswa/tambah.php') ?>" method="POST" enctype="multipart/form-data">

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
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">

                            
                        <?php
                            $ambil_nim = mysqli_query($connection, "SELECT nim FROM mahasiswa ORDER BY id DESC LIMIT 1");

                            if (mysqli_num_rows($ambil_nim) > 0) {
                                $row = mysqli_fetch_assoc($ambil_nim);
                                $nim_db = trim($row['nim']); // Hilangkan spasi ekstra jika ada

                                if (strpos($nim_db, "-") !== false) {
                                    $nim_db = explode("-", $nim_db);

                                    if (isset($nim_db[1]) && is_numeric($nim_db[1])) {
                                        $no_baru = (int)$nim_db[1] + 1;
                                    } else {
                                        $no_baru = 1;
                                    }
                                } else {
                                    $no_baru = 1;
                                }

                                $nim_baru = "MHS-" . str_pad($no_baru, 4, "0", STR_PAD_LEFT);
                            } else {
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
                                <label for="">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone" value="<?php if (isset
                                ($_POST['no_handphone'])) echo htmlspecialchars($_POST['no_handphone']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Divisi</label>
                                <select name="divisi" class="form-control">
                                    <option value="">--Pilih Divisi--</option>

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
                                <label for="">Universitas</label>
                                <input type="text" class="form-control" name="universitas"
                                value="<?= isset($_POST['universitas']) ? htmlspecialchars($_POST['universitas']) : '' ?>">
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
                    </div>
                </div>
            </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php if (isset
                                ($_POST['username'])) echo ($_POST['username']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Password</label>
                                <input type="text" class="form-control" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="">Konfirmasi Password</label>
                                <input type="text" class="form-control" name="konfirmasi_password">
                            </div>
                            <div class="mb-3">
                                <label for="">Role</label>
                                <select name="role" class="form-control">
                                    <option value="">--Pilih Role--</option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'admin') {
                                    echo 'selected'; 
                                        }?> value="admin">Admin</option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'mahasiswa') {
                                            echo 'selected'; 
                                        }?> value="mahasiswa">Mahasiswa</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Lokasi Presensi</label>
                                <select name="lokasi_presensi" class="form-control">
                                    <option value="">--Pilih Lokasi Presensi--</option>
                                    <?php
                                        $ambil_lok_presensi = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");
                                        while($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)){
                                            $nama_lokasi = $lokasi['nama_lokasi'];
                                            if (isset($_POST['lokasi_presensi']) && $_POST['lokasi_presensi'] == $nama_lokasi) {
                                                echo '<option value="' . htmlspecialchars($nama_lokasi) . '" selected="selected">' . htmlspecialchars($nama_lokasi) . '</option>';
                                            } else {
                                                echo '<option value="' . htmlspecialchars($nama_lokasi) . '">' . htmlspecialchars($nama_lokasi) . '</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Foto</label>
                                <input type="file" class="form-control" name="foto">
                            </div>
                                <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
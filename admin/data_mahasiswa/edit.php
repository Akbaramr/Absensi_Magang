edit.php
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

$judul = "Edit Mahasiswa";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $divisi = isset($_POST['divisi']) ? htmlspecialchars($_POST['divisi']) : '';
    $universitas = isset($_POST['universitas']) ? htmlspecialchars($_POST['universitas']) : '';
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = isset($_POST['lokasi_presensi']) ? htmlspecialchars($_POST['lokasi_presensi']) : '';

    if (empty($_POST['password'])) {
        $password = $_POST['password_lama'];
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // Cek jika ada foto baru yang diunggah
    if ($_FILES['foto_baru']['error'] === 4) {
        $foto = $_POST['foto_lama'];
    } else {
        $ekstensi_diizinkan = ["jpg", "jpeg", "png"];
        $ambil_ekstensi = pathinfo($_FILES['foto_baru']['name'], PATHINFO_EXTENSION);
        $ukuran_file = $_FILES['foto_baru']['size'];
        $max_ukuran_file = 5 * 1024 * 1024; // 5MB
        $file_tmp = $_FILES['foto_baru']['tmp_name'];
        $file_direktori = "../../assets/img/foto_mahasiswa/" . $_FILES['foto_baru']['name'];

        if (in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan) && $ukuran_file <= $max_ukuran_file) {
            move_uploaded_file($file_tmp, $file_direktori);
            $foto = $_FILES['foto_baru']['name'];
        } else {
            $foto = $_POST['foto_lama']; // Jika gagal, gunakan foto lama
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = [];

        if (empty($nama)) $pesan_kesalahan[] = "Nama Wajib Diisi";
        if (empty($jenis_kelamin)) $pesan_kesalahan[] = "Jenis Kelamin Wajib Diisi";
        if (empty($alamat)) $pesan_kesalahan[] = "Alamat Wajib Diisi";
        if (empty($no_handphone)) $pesan_kesalahan[] = "Nomor Handphone Wajib Diisi";
        if (empty($divisi)) $pesan_kesalahan[] = "Divisi Wajib Diisi";
        if (empty($username)) $pesan_kesalahan[] = "Username Wajib Diisi";
        if (empty($role)) $pesan_kesalahan[] = "Role Wajib Diisi";
        if (empty($status)) $pesan_kesalahan[] = "Status Wajib Diisi";
        if (empty($lokasi_presensi)) $pesan_kesalahan[] = "Lokasi Presensi Wajib Diisi";
        if ($_POST['password'] != $_POST['ulangi_password']) $pesan_kesalahan[] = "Password Tidak Sama";

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Query Update Mahasiswa
            $mahasiswa = mysqli_query($connection, "UPDATE mahasiswa SET
                nama = '$nama',
                jenis_kelamin = '$jenis_kelamin',
                alamat = '$alamat',
                no_handphone = '$no_handphone',
                divisi = '$divisi',
                universitas = '$universitas',
                lokasi_presensi = '$lokasi_presensi',
                foto = '$foto'      
            WHERE id = '$id' ");

            if (!$mahasiswa) {
                die("Error updating mahasiswa: " . mysqli_error($connection));
            }

            // Query Update User
            $user = mysqli_query($connection, "UPDATE users SET 
                username = '$username',
                password = '$password',
                status = '$status',
                role = '$role'
            WHERE id_mahasiswa = '$id'");

            if (!$user) {
                die("Error updating users: " . mysqli_error($connection));
            }

            $_SESSION['berhasil'] = 'Data Berhasil diupdate';
            header("Location: mahasiswa.php");
            exit;
        }
    }
}

$id = isset($_GET['id']) ? $_GET['id'] :   $_POST['id'];
$result = mysqli_query($connection, "SELECT users.id_mahasiswa, users.username, users.password, users.status, users.role,
mahasiswa.* FROM users JOIN mahasiswa ON users.id_mahasiswa = mahasiswa.id WHERE mahasiswa.id = $id");

while ($mahasiswa = mysqli_fetch_array($result)) {
    $nama = $mahasiswa['nama'];
    $jenis_kelamin = $mahasiswa['jenis_kelamin'];
    $alamat = $mahasiswa['alamat'];
    $no_handphone = $mahasiswa['no_handphone'];
    $divisi = $mahasiswa['divisi'];
    $username = $mahasiswa['username'];
    $password = $mahasiswa['password'];
    $status = $mahasiswa['status'];
    $lokasi_presensi = $mahasiswa['lokasi_presensi'];
    $role = $mahasiswa['role'];
    $foto = $mahasiswa['foto'];
    $nim = $mahasiswa['nim'];
    $universitas = $mahasiswa['universitas'];
}

?>

<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('admin/data_mahasiswa/edit.php') ?>" method="POST" enctype="multipart/form-data">

            <?php if (isset($_SESSION['validasi'])): ?>
                <script>
                    const errors = <?= json_encode($_SESSION['validasi']); ?>;
                    Swal.fire({
                        title: 'Validasi Gagal',
                        html: errors.map(error => <li>${error}</li>).join(''),
                        icon: 'error',
                    });
                </script>
                <?php unset($_SESSION['validasi']); ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="mb-3">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?= $nama ?> ">
                            </div>

                            <div class="mb-3">
                                <label for="">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--Pilih Jenis kelamin--</option>
                                    <option <?php if ($jenis_kelamin == 'Laki-Laki') {
                                                echo 'selected';
                                            } ?> value="Laki-Laki">Laki-Laki</option>

                                    <option <?php if ($jenis_kelamin == 'Perempuan') {
                                                echo 'selected';
                                            } ?> value="Perempuan">Perempuan</option>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="<?= $alamat ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone" value="<?= $no_handphone ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Divisi</label>
                                <select name="divisi" class="form-control">
                                    <option value="">--Pilih Divisi--</option>

                                    <?php
                                    $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY 
                                    jabatan ASC");

                                    while ($row = mysqli_fetch_assoc($ambil_jabatan)) {
                                        $nama_jabatan = $row['jabatan'];

                                        if ($divisi == $nama_jabatan) {
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
                                    value="<?= $universitas ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--Pilih Status--</option>
                                    <option <?php if ($status == 'Aktif') {
                                                echo 'selected';
                                            } ?> value="Aktif">Aktif</option>

                                    <option <?php if ($status == 'Tidak Aktif') {
                                                echo 'selected';
                                            } ?> value="Tidak Aktif">Tidak Aktif</option>

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
                                <input type="text" class="form-control" name="username" value="<?= $username ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Password</label>
                                <input type="hidden" value="<?= $password; ?>" name="password_lama">
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="">Ulangi Password</label>
                                <input type="password" class="form-control" name="ulangi_password">
                            </div>
                            <div class="mb-3">
                                <label for="">Role</label>
                                <select name="role" class="form-control">
                                    <option value="">--Pilih Role--</option>
                                    <option <?php if ($role == 'admin') {
                                                echo 'selected';
                                            } ?> value="admin">Admin</option>
                                    <option <?php if ($role == 'mahasiswa') {
                                                echo 'selected';
                                            } ?> value="mahasiswa">Mahasiswa</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Lokasi Presensi</label>
                                <select name="lokasi_presensi" class="form-control">
                                    <option value="">--Pilih Lokasi Presensi--</option>
                                    <?php
                                    $ambil_lok_presensi = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");
                                    while ($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)) {
                                        $nama_lokasi = $lokasi['nama_lokasi'];
                                        if ($lokasi_presensi == $nama_lokasi) {
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
                                <input type="hidden" value="<?= $foto ?>" name="foto_lama">
                                <input type="file" class="form-control" name="foto_baru">
                            </div>

                            <input type="hidden" value="<?= $id ?>" name="id">

                            <button type="submit" class="btn btn-primary" name="edit">Update</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
</div>
</div>
<?php include('../layout/footer.php'); ?>
<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Detail Mahasiswa";
include('../layout/header.php');
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT users.id_mahasiswa, users.username, users.password, users.status, users.role,
mahasiswa.* FROM users JOIN mahasiswa ON users.id_mahasiswa = mahasiswa.id WHERE mahasiswa.id = $id");
?>

<?php while ($mahasiswa = mysqli_fetch_array($result)): ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama</strong></td>
                                    <td>: <?= $mahasiswa['nama'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin</strong></td>
                                    <td>: <?= $mahasiswa['jenis_kelamin'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No Handphone</strong></td>
                                    <td>: <?= $mahasiswa['no_handphone'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Divisi</strong></td>
                                    <td>: <?= $mahasiswa['divisi'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Username</strong></td>
                                    <td>: <?= $mahasiswa['username'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Role</strong></td>
                                    <td>: <?= $mahasiswa['role'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi Presensi</strong></td>
                                    <td>: <?= $mahasiswa['lokasi_presensi'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>: <?= $mahasiswa['status'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <img style="max-width: 100%; border-radius: 15px; max-height: 350px"
                        src="<?= base_url('assets/img/foto_mahasiswa/' . $mahasiswa['foto']) ?>" alt="Foto Mahasiswa">
                </div>
            </div>
        </div>
    </div>
<?php endwhile ?>

<?php include('../layout/footer.php'); ?>
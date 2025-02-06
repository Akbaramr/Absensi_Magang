<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'mahasiswa') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "";
include('../layout/header.php');
require_once('../../config.php');

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT users.id_mahasiswa, users.username, users.status, users.role,
mahasiswa.* FROM users JOIN mahasiswa ON users.id_mahasiswa = mahasiswa.id WHERE mahasiswa.id = $id");
?>

<?php while ($mahasiswa = mysqli_fetch_array($result)): ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body text-center">
                            <!-- Profile Picture -->
                            <img class="rounded-circle mb-4" style="width: 150px; height: 150px; object-fit: cover;"
                                src="<?= base_url('assets/img/foto_mahasiswa/' . $mahasiswa['foto']) ?>"
                                alt="Foto Mahasiswa">

                            <!-- Name -->
                            <h4 class="card-title mb-3"><?= $mahasiswa['nama'] ?></h4>

                            <!-- Details -->
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="text-end">Jenis Kelamin:</th>
                                        <td class="text-start"><?= $mahasiswa['jenis_kelamin'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">Alamat:</th>
                                        <td class="text-start"><?= $mahasiswa['alamat'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">No Handphone:</th>
                                        <td class="text-start"><?= $mahasiswa['no_handphone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">Divisi:</th>
                                        <td class="text-start"><?= $mahasiswa['divisi'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">Role:</th>
                                        <td class="text-start"><?= $mahasiswa['role'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">Lokasi Presensi:</th>
                                        <td class="text-start"><?= $mahasiswa['lokasi_presensi'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-end">Status:</th>
                                        <td class="text-start"><?= $mahasiswa['status'] ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <a href="../home/home.php" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<?php include('../layout/footer.php'); ?>
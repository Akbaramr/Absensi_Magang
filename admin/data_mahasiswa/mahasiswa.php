<?php 
session_start();  
if (!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
} 

$judul = "Data Mahasiswa";
include('../layout/header.php'); 
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT users.id_mahasiswa, users.username, users.password, users.status, users.role,
mahasiswa.* FROM users JOIN mahasiswa ON users.id_mahasiswa = mahasiswa.id");
?>

<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('admin/data_mahasiswa/tambah.php') ?>" class="btn btn-primary mb-3 w-100 d-md-inline">
            <i class="fa-solid fa-circle-plus"></i> Tambah Data
        </a>

        <div class="table-responsive"> <!-- Tambahkan table-responsive -->
            <table class="table table-bordered table-striped mt-3">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Divisi</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) == 0) { ?>
                        <tr>
                            <td colspan="7" class="text-center">Data Kosong, Silahkan tambahkan data baru</td>
                        </tr>
                    <?php } else { ?>
                        <?php $no = 1; 
                        while($mahasiswa = mysqli_fetch_array($result)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $mahasiswa['nim'] ?></td>
                                <td><?= $mahasiswa['nama']?></td>
                                <td><?= $mahasiswa['username']?></td>
                                <td><?= $mahasiswa['divisi']?></td>
                                <td><?= $mahasiswa['role']?></td>
                                <td class="text-center">
                                    <div class="d-flex flex-column flex-md-row gap-1">
                                        <a href="<?= base_url('admin/data_mahasiswa/detail.php?id=' . $mahasiswa['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                           <i class="fa-solid fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                        </a>

                                        <a href="<?= base_url('admin/data_mahasiswa/edit.php?id=' . $mahasiswa['id']) ?>" 
                                           class="btn btn-sm btn-warning">
                                           <i class="fa-solid fa-pen"></i> <span class="d-none d-md-inline">Edit</span>
                                        </a>

                                        <a href="<?= base_url('admin/data_mahasiswa/hapus.php?id=' . $mahasiswa['id']) ?>" 
                                           class="btn btn-sm btn-danger tombol-hapus">
                                           <i class="fa-solid fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div> <!-- End table-responsive -->
    </div>
</div>

<?php include('../layout/footer.php'); ?>

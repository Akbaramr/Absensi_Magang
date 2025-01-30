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
        <a href="<?= base_url('admin/data_mahasiswa/tambah.php') ?>" class="btn btn-primary mb-3">
            <span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span>
        </a>
        
        <table class="table table-bordered mt-3">
            <thead>
                <tr class="text-center">
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
                                <a href="<?= base_url('admin/data_mahasiswa/detail.php?id=' . $mahasiswa['id']) ?>" 
                                   class="badge badge-pill bg-primary">Detail</a>

                                <a href="<?= base_url('admin/data_mahasiswa/edit.php?id=' . $mahasiswa['id']) ?>" 
                                   class="badge badge-pill bg-primary">Edit</a>

                                <a href="<?= base_url('admin/data_mahasiswa/hapus.php?id=' . $mahasiswa['id']) ?>" 
                                   class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
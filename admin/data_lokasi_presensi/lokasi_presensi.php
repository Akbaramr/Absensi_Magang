<?php 
session_start();  
if (!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
} 

$judul = "Data Lokasi Presensi";
include('../layout/header.php'); 
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id DESC");
?>

<div class="page-body">
    <div class="container-xl">
        <!-- Tombol Tambah Data -->
        <a href="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" class="btn btn-primary mb-3 w-100 d-md-inline">
            <i class="fa-solid fa-circle-plus"></i> Tambah Data
        </a>

        <div class="table-responsive"> <!-- Tambahkan table-responsive -->
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Latitude/Longitude</th>
                        <th>Radius</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) == 0) { ?>
                        <tr>
                            <td colspan="5" class="text-center">Data Kosong, Silahkan tambahkan data baru</td>
                        </tr>
                    <?php } else { ?>
                        <?php $no = 1; while($lokasi = mysqli_fetch_array($result)) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($lokasi['nama_lokasi']) ?></td>
                                <td class="text-center text-nowrap"><?= htmlspecialchars($lokasi['latitude'] . '/' . $lokasi['longitude']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($lokasi['radius']) ?> m</td>
                                <td class="text-center">
                                    <div class="d-flex flex-column flex-md-row gap-1">
                                        <a href="<?= base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                           <i class="fa-solid fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                                        </a>

                                        <a href="<?= base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>" 
                                           class="btn btn-sm btn-warning">
                                           <i class="fa-solid fa-pen"></i> <span class="d-none d-md-inline">Edit</span>
                                        </a>

                                        <a href="<?= base_url('admin/data_lokasi_presensi/hapus.php?id=' . $lokasi['id']) ?>" 
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

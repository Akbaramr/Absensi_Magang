<?php 
session_start();  
if (!isset($_SESSION["login"])){
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if ($_SESSION["role"] != 'admin'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
} 


$judul = "Data Jabatan";
include('../layout/header.php'); 
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM jabatan  ORDER BY  id DESC");
?>

<!-- Page body -->
<div class="page-body">
  <div class="container-xl"> 

    <!-- Tombol Tambah Data -->
    <a href="<?= base_url('admin/data_jabatan/tambah.php') ?>" class="btn btn-primary mb-3 w-100 d-md-inline">
      <i class="fa-solid fa-circle-plus"></i> Tambah Data
    </a>

    <div class="table-responsive"> <!-- Tambahkan table-responsive -->
      <table class="table table-bordered table-striped">
        <thead class="text-center">
          <tr>
            <th>No.</th>
            <th>Nama Jabatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        
        <tbody>
          <?php if(mysqli_num_rows($result) === 0) : ?>
            <tr>
              <td colspan="3" class="text-center">Data masih kosong, silahkan tambahkan data baru</td>
            </tr>
          <?php else : ?>
            <?php $no = 1;
            while($jabatan = mysqli_fetch_array($result)) : ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $jabatan['jabatan']?></td> 
                <td class="text-center">
                  <div class="d-flex flex-column flex-md-row gap-1">
                    <a href="<?= base_url('admin/data_jabatan/edit.php?id='.$jabatan['id']) ?>" 
                       class="btn btn-sm btn-warning">
                      <i class="fa-solid fa-pen"></i> <span class="d-none d-md-inline">Edit</span>
                    </a>

                    <a href="<?= base_url('admin/data_jabatan/hapus.php?id='.$jabatan['id']) ?>" 
                       class="btn btn-sm btn-danger tombol-hapus">
                      <i class="fa-solid fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div> <!-- End table-responsive -->

  </div>
</div>

<?php include('../layout/footer.php'); ?>

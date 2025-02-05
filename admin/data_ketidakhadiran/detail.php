<?php
ob_start();
session_start();  
if (!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
} 

$judul = "Detail Ketidakhadiran";
include('../layout/header.php'); 
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $status_pengajuan = $_POST['status_pengajuan'];
    
    // Perbaikan query UPDATE
    $result = mysqli_query($connection, "UPDATE ketidakhadiran SET status_pengajuan='$status_pengajuan' WHERE id=$id");
    
    if($result){
        $_SESSION['berhasil'] = 'Status Pengajuan Berhasil Diupdate';
        header("Location: ketidakhadiran.php");
        exit;
    } else {
        $_SESSION['error'] = 'Gagal mengupdate status pengajuan: ' . mysqli_error($connection);
    }
}

// Query untuk menampilkan data
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");
while($data = mysqli_fetch_array($result)){
    $keterangan = $data['keterangan'];
    $status_pengajuan = $data['status_pengajuan'];
    $tanggal = $data['tanggal'];
}
?>
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <?php
                if(isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?=$tanggal?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" id="keterangan" value="<?=$keterangan?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status_pengajuan">Status pengajuan</label>
                        <select name="status_pengajuan" id="status_pengajuan" class="form-control">
                            <option value="">--Pilih Status--</option>
                            <option value="PENDING" <?= (strtoupper($status_pengajuan) == 'PENDING') ? 'selected' : '' ?>>Pending</option>
                            <option value="REJECTED" <?= (strtoupper($status_pengajuan) == 'REJECTED') ? 'selected' : '' ?>>Rejected</option>
                            <option value="APPROVED" <?= (strtoupper($status_pengajuan) == 'APPROVED') ? 'selected' : '' ?>>Approved</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?= $id?>">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
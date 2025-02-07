<?php 
ob_start();
session_start();  
if (!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?pesan=belum_login");
}else if ($_SESSION["role"] != 'mahasiswa'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
} 

// Definisi variabel yang diperlukan di awal
$ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'pdf'];
$max_ukuran_file = 5 * 1024 * 1024; // 5MB dalam bytes

$judul = "Edit Pengajuan Ketidakhadiran";
include('../layout/header.php'); 

$pesan_kesalahan = [];
$nama_file = '';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    
    // Validasi input dasar
    if (empty($keterangan)) {
        $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Keterangan wajib diisi";
    }
    if (empty($tanggal)) {
        $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Tanggal Wajib Diisi";
    }
    if (empty($deskripsi)) {
        $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Deskripsi Wajib Diisi";
    }

    // Cek apakah ada file baru yang diupload
    if($_FILES['file_baru']['error'] === 4){
        $nama_file = $_POST['file_lama'];
    } else {
        $file = $_FILES['file_baru'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;
        
        // Ambil ekstensi file
        $ambil_ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        // Validasi file
        if (!in_array($ambil_ekstensi, $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya File JPG, JPEG, PNG, dan PDF yang Diperbolehkan";
        }
        
        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file tidak boleh melebihi 5 MB";
        }
    }

    // Proses penyimpanan data
    if (empty($pesan_kesalahan)) {
        // Jika ada file baru, upload terlebih dahulu
        if($_FILES['file_baru']['error'] !== 4) {
            if (!move_uploaded_file($file_tmp, $file_direktori)) {
                $_SESSION['error'] = 'Gagal mengupload file';
                goto skipUpdate;
            }
        }

        // Update data
        $result = mysqli_query($connection, "UPDATE ketidakhadiran SET 
            keterangan='$keterangan',
            deskripsi='$deskripsi',
            tanggal='$tanggal',
            file='$nama_file' 
            WHERE id = $id");        

        if ($result) {
            $_SESSION['berhasil'] = 'Data Berhasil Diupdate';
            header("Location: ketidakhadiran.php");
            exit;
        } else {
            $_SESSION['error'] = 'Gagal menyimpan data: ' . mysqli_error($connection);
        }
    } else {
        $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    }
}

skipUpdate:

// Ambil data yang akan diedit
$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id=$id");
while($data = mysqli_fetch_array($result)){
    $keterangan = $data['keterangan'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
}
?>
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <?php
                if(isset($_SESSION['validasi'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['validasi'] . '</div>';
                    unset($_SESSION['validasi']);
                }
                if(isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if(isset($_SESSION['berhasil'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['berhasil'] . '</div>';
                    unset($_SESSION['berhasil']);
                }
                ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $_SESSION['id']?>" name="id_mahasiswa">
                    <div class="mb-3">
                        <label for="keterangan">Keterangan</label>
                        <select name="keterangan" id="keterangan" class="form-control">
                            <option value="">--Pilih Keterangan--</option>
                            <option value="cuti" <?= ($keterangan == 'cuti') ? 'selected' : '' ?>>Cuti</option>
                            <option value="izin" <?= ($keterangan == 'izin') ? 'selected' : '' ?>>Izin</option>
                            <option value="sakit" <?= ($keterangan == 'sakit') ? 'selected' : '' ?>>Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" rows="5"><?= trim($deskripsi) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?=$tanggal?>">
                    </div>
                    <div class="mb-3">
                        <label for="file">Surat keterangan</label>
                        <input type="file" class="form-control" name="file_baru">
                        <input type="hidden" name="file_lama" value="<?= $file?>">
                        <small class="text-muted">File yang diizinkan: JPG, JPEG, PNG, PDF (Max: 5MB)</small>
                        <?php if($file): ?>
                            <div class="mt-2">
                                <small>File saat ini: <?= $file ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="id" value="<?= $_GET['id'];?>">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

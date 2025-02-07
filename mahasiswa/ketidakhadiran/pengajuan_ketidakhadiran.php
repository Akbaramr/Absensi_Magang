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

$judul = "Pengajuan Ketidakhadiran";
include('../layout/header.php'); 

if(isset($_POST['submit'])){
    $id = $_POST['id_mahasiswa'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $status_pengajuan = 'PENDING';
    $nama_file = '';
    $ukuran_file = 0;
    $ambil_ekstensi = '';
    $pesan_kesalahan = [];

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

    // Validasi dan proses upload file
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;
    
        // Ambil ekstensi file
        $ambil_ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    
        if (!in_array($ambil_ekstensi, $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya File JPG, JPEG, PNG, dan PDF yang Diperbolehkan";
        }
        
        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file tidak boleh melebihi 5 MB";
        }
    } else {
        $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> File wajib diupload";
    }

    // Proses penyimpanan data
    if (!empty($pesan_kesalahan)) {
        $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
        // Upload file
        if (move_uploaded_file($file_tmp, $file_direktori)) {
            // Query Insert Data
            $result = mysqli_query($connection, "INSERT INTO ketidakhadiran
                (id_mahasiswa, keterangan, deskripsi, tanggal, status_pengajuan, file) 
                VALUES ('$id', '$keterangan', '$deskripsi', '$tanggal', '$status_pengajuan', '$nama_file')");        

            if ($result) {
                $_SESSION['berhasil'] = 'Data Berhasil Disimpan';
                header("Location: ketidakhadiran.php");
                exit;
            } else {
                $_SESSION['error'] = 'Gagal menyimpan data: ' . mysqli_error($connection);
            }
        } else {
            $_SESSION['error'] = 'Gagal mengupload file';
        }
    }
}

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id_mahasiswa = '$id' ORDER BY id DESC");

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
                            <option <?= (isset($_POST['keterangan']) && $_POST['keterangan'] == 'cuti') ? 'selected' : '' ?> value="cuti">Cuti</option>
                            <option <?= (isset($_POST['keterangan']) && $_POST['keterangan'] == 'izin') ? 'selected' : '' ?> value="izin">Izin</option>
                            <option <?= (isset($_POST['keterangan']) && $_POST['keterangan'] == 'sakit') ? 'selected' : '' ?> value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" rows="5"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="file">Surat keterangan</label>
                        <input type="file" class="form-control" name="file" id="file">
                        <small class="text-muted">File yang diizinkan: JPG, JPEG, PNG, PDF (Max: 5MB)</small>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
session_start();
ob_start();  
if (!isset($_SESSION["login"])){
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
}else if ($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
} 

$judul = "Edit Data Jabatan";
include('../layout/header.php'); 
require_once ('../../config.php');

// Validate and sanitize id parameter
$id = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);
}

// Redirect if no valid ID is provided
if ($id === null) {
    $_SESSION['validasi'] = "ID Jabatan tidak valid!";
    header("Location: jabatan.php");
    exit;
}

if(isset($_POST['update'])) {
    $jabatan = htmlspecialchars($_POST['jabatan']);

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($jabatan)) {
            $pesan_kesalahan = "Nama Jabatan Wajib Diisi!";
        }

        if(!empty($pesan_kesalahan)){
            $_SESSION['validasi'] = $pesan_kesalahan;
        } else {
            // Use prepared statement to prevent SQL injection
            $query = "UPDATE jabatan SET jabatan = ? WHERE id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "si", $jabatan, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['berhasil'] = "Data jabatan berhasil diupdate";
                header("Location: jabatan.php");
                exit;
            } else {
                $_SESSION['validasi'] = "Gagal mengupdate data: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch existing data using prepared statement
$query = "SELECT * FROM jabatan WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && $jabatan = mysqli_fetch_array($result)) {
    $nama_jabatan = $jabatan['jabatan'];
} else {
    $_SESSION['validasi'] = "Data jabatan tidak ditemukan!";
    header("Location: jabatan.php");
    exit;
}
mysqli_stmt_close($stmt);
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl"> 
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_jabatan/edit.php') ?>" method="POST">
                    <div class="mb-3">
                        <label for="jabatan">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" id="jabatan" value="<?= htmlspecialchars($nama_jabatan) ?>">
                    </div>
                    <input type="hidden" value="<?= $id ?>" name="id">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
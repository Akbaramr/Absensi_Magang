<?php
ob_start();
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'mahasiswa') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "<i class ='fa-solid fa-check'></i> Password baru wajib diisi";
        }

        if (empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "<i class ='fa-solid fa-check'></i> Ulangi Password baru wajib diisi";
        }

        if ($_POST['password_baru'] != $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "<i class ='fa-solid fa-check'></i> Password tidak cocok";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $mahasiswa = mysqli_query($connection, "UPDATE users SET 
                 password = '$password_baru' 
            WHERE id_mahasiswa = $id");

            $_SESSION['berhasil'] = 'Password Berhasil diubah';
            header("Location: ../home/home.php");
            exit;
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">

                    <form action="" method="POST">
                        <div class="form-floating mb-4">
                            <input type="password" name="password_baru" class="form-control rounded-3"
                                id="password_baru" placeholder="Password Baru">
                            <label for="password_baru">🔑 Password Baru</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="ulangi_password_baru" class="form-control rounded-3"
                                id="ulangi_password_baru" placeholder="Ulangi Password Baru">
                            <label for="ulangi_password_baru">🔁 Ulangi Password Baru</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit"
                                class="btn btn-gradient-primary btn-lg rounded-pill fw-semibold shadow-sm hover-scale"
                                name="update">
                                🚀 Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>

<style>
    /* Gradient Primary Button */
    .btn-gradient-primary {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
        color: white;
        transition: all 0.3s ease-in-out;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #2575fc, #6a11cb);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    /* Smooth shadow & input rounded */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.08);
    }

    .input-focus:focus {
        box-shadow: 0px 0px 8px rgba(81, 203, 238, 1);
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.03);
    }
</style>
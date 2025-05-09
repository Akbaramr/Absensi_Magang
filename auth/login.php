<?php
session_start();

 require_once ('../config.php');

 if (isset($_POST["login"])){
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Query login dengan case-sensitive
  $result = mysqli_query($connection, "SELECT * FROM users JOIN mahasiswa ON users.id_mahasiswa = mahasiswa.id WHERE BINARY username = '$username'");

  if (mysqli_num_rows($result) === 1){
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password,$row["password"])){
      if ($row['status'] == 'Aktif'){

        $_SESSION["login"] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['nim'] = $row['nim'];
        $_SESSION['divisi'] = $row['divisi'];
        $_SESSION['universitas'] = $row['universitas'];
        $_SESSION['lokasi_presensi'] = $row['lokasi_presensi'];
        $_SESSION['foto'] = $row['foto']; // Pastikan 'foto' adalah nama kolom di tabel mahasiswa


        if($row['role'] === 'admin'){
          header("Location: ../admin/home/home.php");
          exit();
        }else{
          header("Location: ../mahasiswa/home/home.php");
          exit(); 
        }

      }else{
        $_SESSION["gagal"] = "Akun Anda belum aktif";
      }

    } else{
      $_SESSION["gagal"] = "Password salah, silahkan coba lagi";
    }

  } else{  
      $_SESSION["gagal"] = "Username salah, silahkan coba lagi";
  }
}
 
 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>PRISMA - Login</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/tabler.min.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/tabler-vendors.min.css'); ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/demo.min.css'); ?>" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: 'Inter Var', sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <div class="page page-center">
      <div class="container py-4">
        <div class="row align-items-center justify-content-center g-4">
          <div class="col-md-8 col-lg-6">
            <div class="container-tight">
              <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                  <img src="<?= base_url('assets/img/LOGO_PN_MAKASSAR (1).png') ?>" height="66" alt="">
                </a>
              </div>

              <div class="card card-md">
                <div class="card-body">
                  <h2 class="h2 text-center mb-4">Login to your account</h2>
                  <form action="" method="POST" autocomplete="off">
                    <div class="mb-3">
                      <label class="form-label">Username</label>
                      <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Password</label>
                      <div class="input-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <span class="input-group-text">
                          <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                              <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="form-footer">
                      <button type="submit" name="login" class="btn btn-primary w-100">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
          </div>

    <!-- JS Dependencies -->
    <script src="<?= base_url('assets/js/tabler.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js') ?>" defer></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(isset($_SESSION['gagal']) && !empty($_SESSION['gagal'])) { ?>
    <script>   
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "<?= $_SESSION['gagal']; ?>",
        });
    </script>
    <?php unset($_SESSION['gagal']); ?>
    <?php } ?>

    <script>
  document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.querySelector(".input-group-text a");
    const passwordInput = document.querySelector("input[name='password']");
    
    togglePassword.addEventListener("click", function(event) {
      event.preventDefault(); // Mencegah aksi default link
      
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    });
  });
</script>


  </body>
</html>
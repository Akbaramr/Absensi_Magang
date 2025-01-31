<?php 
session_start();  
if (!isset($_SESSION["login"])){
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if ($_SESSION["role"] != 'mahasiswa'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
} 

include('../layout/header.php'); 
include_once("../../config.php");

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi =
'$lokasi_presensi'");

while($lokasi = mysqli_fetch_array($result)){
    $latitude_kantor = $lokasi['latitude'];
    $longitude_kantor = $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
}

?>

<style>
  .parent_date{
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }
</style>

<style>
  .parent_clock{
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 30px;
    text-align: center;
    font-weight: bold;
    justify-content: center;
  }
</style>



        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-4">
                <div class="card text-center">
                  <div class="card-header">Presensi Masuk</div>
                  <div class="card-body"></div>
                    <div class="parent_date">
                      <div id="tanggal_masuk"></div>
                      <div class="ms-2"></div>
                      <div id="bulan_masuk"></div>
                      <div class="ms-2"></div>
                      <div id="tahun_masuk"></div>
                    </div>

                    <div class="parent_clock">
                      <div id="jam_masuk"></div>
                      <div>:</div>

                      <div id="menit_masuk"></div>
                      <div>:</div>

                      <div id="detik_masuk"></div>
                    </div>

                    <form method="POST" action="<?= base_url('mahasiswa/presensi/presensi_masuk.
                    php')?>">
                    <input type="text" value="<?= $latitude_kantor ?>" name="latitude_kantor">
                    <input type="text" value="<?= $longitude_kantor ?>" name="longitude_kantor">
                    <input type="text" value="<?= $radius ?>" name="radius">
                    <input type="text" value="<?= $zona_waktu ?>" name="zona_waktu">

                      <button type="submit" class="btn btn-primary mt-3">Masuk</button> 
                    </form>
                </div>
                
              </div>

              <div class="col-md-4">
                <div class="card text-center">
                  <div class="card-header">Presensi Keluar</div>
                  <div class="card-body"></div>
                    <div class="parent_date">
                      <div id="tanggal_keluar"></div>
                      <div class="ms-2"></div>

                      <div id="bulan_keluar"></div>
                      <div class="ms-2"></div>

                      <div id="tahun_keluar"></div>
                    </div>

                    <div class="parent_clock">
                      <div id="jam_keluar"></div>
                      <div>:</div>
                      <div id="menit_keluar"></div>
                      <div>:</div>
                      <div id="detik_keluar"></div>
                    </div>
                    <form action="">
                      <button type="submit" class="btn btn-danger mt-3">Keluar</button> 
                    </form>
                </div>
              </div>
              <div class="col-md-2"></div>
            </div>
          </div>
        </div>

        <script>
          // set waktu di card presensi waktuMasuk
          window.setTimeout("waktuMasuk()", 1000);
          namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
          "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

          function waktuMasuk() {
            const waktu = new Date();
            setTimeout ("waktuMasuk()", 1000);
            document.getElementById("tanggal_masuk").innerHTML = waktu.getDate();
            document.getElementById("bulan_masuk").innerHTML = namaBulan[waktu.getMonth()];
            document.getElementById("tahun_masuk").innerHTML = waktu.getFullYear();
            document.getElementById("jam_masuk").innerHTML = waktu.getHours();
            document.getElementById("menit_masuk").innerHTML = waktu.getMinutes();
            document.getElementById("detik_masuk").innerHTML = waktu.getSeconds();
          }

          // set waktu di card presensi waktuKeluar
          window.setTimeout("waktuKeluar()", 1000);
          namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
          "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

          function waktuKeluar(){
            const waktu = new Date();
            setTimeout ("waktuKeluar()", 1000);
            document.getElementById("tanggal_keluar").innerHTML = waktu.getDate();
            document.getElementById("bulan_keluar").innerHTML = namaBulan[waktu.getMonth()];
            document.getElementById("tahun_keluar").innerHTML = waktu.getFullYear();
            document.getElementById("jam_keluar").innerHTML = waktu.getHours();
            document.getElementById("menit_keluar").innerHTML = waktu.getMinutes();
            document.getElementById("detik_keluar").innerHTML = waktu.getSeconds();
          }

          getLocation();

          function getLocation(){
            if(navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(showPosition);
            }else{
              alert("browser Anda Tidak Mendukung")
            }
          }

          function showPosition(position)
          {
            $('#latitude_mahasiswa').val(position.coords.latitude);
            $('#latitude_mahasiswa').val(position.coords.latitude);
            $('#latitude_mahasiswa').val(position.coords.latitude);
          }
        </script>

        <?php include('../layout/footer.php'); ?>
<?php 
ob_start();
session_start();  
if (!isset($_SESSION["login"])){
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if ($_SESSION["role"] != 'mahasiswa'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Rekap Presensi';
include('../layout/header.php'); 
include_once("../../config.php");

if(empty($_GET['tanggal_dari'])){
    $id = $_SESSION['id'];
    $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_mahasiswa = '$id' ORDER BY tanggal_masuk DESC");
}else{
    $id = $_SESSION['id'];
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $result = mysqli_query($connection, "SELECT * FROM presensi WHERE id_mahasiswa = '$id' AND 
    tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC");
}

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$lokasi = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

while($lokasi_result = mysqli_fetch_array($lokasi)):
    $jam_masuk_kantor = date('h:i:s', strtotime($lokasi_result['jam_masuk']));
endwhile;
?>

<div class="page-body">
  <div class="container-xl">
    <div class="row align-items-center">
      <!-- Tombol Export Excel -->
      <div class="col-md-2 mb-2">
        <button type="button" class="btn btn-primary" id="exportButton">
          Export Excel
        </button>
      </div>

      <!-- Form Filter Tanggal -->
      <div class="col-md-10">
        <form method="GET">
          <div class="input-group">
            <input type="date" class="form-control" name="tanggal_dari">
            <input type="date" class="form-control" name="tanggal_sampai">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Responsif -->
    <div class="table-responsive mt-3">
      <table class="table table-bordered text-center">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Total Jam</th>
            <th>Total Terlambat</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($result) === 0){ ?>
            <tr>
              <td colspan="6">Data Rekap Presensi Masih Kosong</td>
            </tr>
          <?php } else { ?>
            <?php $no =1; while($rekap = mysqli_fetch_array($result)) : 
              // Menghitung total jam kerja dengan format 24 jam
              $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'].' '.$rekap['jam_masuk']));
              $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'].' '.$rekap['jam_keluar']));
              $timestamp_masuk = strtotime($jam_tanggal_masuk);
              $timestamp_keluar = strtotime($jam_tanggal_keluar);

              $selisih = $timestamp_keluar - $timestamp_masuk;
              $total_jam_kerja = floor($selisih / 3600);
              $sisa = $selisih - ($total_jam_kerja * 3600);
              $selisih_menit_kerja = floor($sisa / 60);

              // Menghitung total keterlambatan
              $jam_masuk_real = date('H:i:s', strtotime($rekap['jam_masuk']));
              $timestamp_jam_masuk_real = strtotime($jam_masuk_real);
              $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);
              $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;

              if ($terlambat <= 0) {
                  $terlambat_output = '<span class="badge bg-success">On Time</span>';
              } else {
                  $total_jam_terlambat = floor($terlambat / 3600);
                  $sisa_terlambat = $terlambat - ($total_jam_terlambat * 3600);
                  $selisih_menit_terlambat = floor($sisa_terlambat / 60);
                  $terlambat_output = $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit';
              }
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
              <td class="text-center"><?= $rekap['jam_masuk'] ?></td>
              <td class="text-center"><?= $rekap['jam_keluar'] ?></td>
              <td class="text-center">
                <?php if ($rekap['tanggal_keluar'] == '0000-00-00') : ?>
                  <span>0 Jam 0 Menit</span>
                <?php else : ?>
                  <?= $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit' ?>
                <?php endif ?>
              </td>
              <td class="text-center"><?= $terlambat_output ?></td>
            </tr>
            <?php endwhile; ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript SweetAlert -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("exportButton").addEventListener("click", function (event) {
      event.preventDefault(); // Mencegah aksi default
      Swal.fire({
        icon: "error",
        title: "Gagal!",
        text: "Anda tidak bisa mengekspor!",
        confirmButtonText: "OK"
      });
    });
  });
</script>

<?php include('../layout/footer.php')?>

<?php
ob_start();
session_start();  
if (!isset($_SESSION["login"])){
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if ($_SESSION["role"] != 'mahasiswa'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
} 
include_once("../../config.php");
$file_foto = $_POST['photo'];
$id_mahasiswa = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk'];
$jam_masuk = $_POST['jam_masuk'];
$foto = $file_foto;
$foto = str_replace('data:image/jpeg;base64,', '' , $foto);
$foto = str_replace(' ','+', $foto);
$data = base64_decode($foto);
$nama_file = 'foto/'.'masuk'.date('Y-m-d'). '.png';
$file = 'masuk'.date('Y-m-d').'.png';
file_put_contents($nama_file,$data);
$result = mysqli_query($connection,"INSERT INTO presensi (id_mahasiswa,tanggal_masuk,jam_masuk,foto_masuk) VALUES ('$id_mahasiswa','$tanggal_masuk','$jam_masuk','$file')");
if($result){
    $_SESSION['berhasil'] = "Presensi masuk berhasil";
}else{
    $_SESSION['gagal'] = "Presensi masuk gagal";
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     <style>
        #map{
            height: 300px;
        }
     </style>
<?php
ob_start();
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit(); // Penting untuk menghentikan eksekusi script setelah redirect
} else if ($_SESSION["role"] != 'mahasiswa') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit(); // Penting untuk menghentikan eksekusi script setelah redirect
}

$judul = 'Presensi Masuk';
include('../layout/header.php');
include_once("../../config.php");

// Inisialisasi variabel
$latitude_mahasiswa = 0;
$longitude_mahasiswa = 0;
$latitude_kantor = 0;
$longitude_kantor = 0;
$radius = 0;
$tanggal_masuk = '';
$jam_masuk = '';
$jarak_meter = 0;

if (isset($_POST['tombol_masuk'])) {
    $latitude_mahasiswa = isset($_POST['latitude_mahasiswa']) ? floatval($_POST['latitude_mahasiswa']) : 0;
    $longitude_mahasiswa = isset($_POST['longitude_mahasiswa']) ? floatval($_POST['longitude_mahasiswa']) : 0;
    $latitude_kantor = isset($_POST['latitude_kantor']) ? floatval($_POST['latitude_kantor']) : 0;
    $longitude_kantor = isset($_POST['longitude_kantor']) ? floatval($_POST['longitude_kantor']) : 0;
    $radius = isset($_POST['radius']) ? floatval($_POST['radius']) : 0;
    $zona_waktu = isset($_POST['zona_waktu']) ? $_POST['zona_waktu'] : '';
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];

    // Konversi derajat ke radian
    $lat_mhs_rad = deg2rad($latitude_mahasiswa);
    $lon_mhs_rad = deg2rad($longitude_mahasiswa);
    $lat_ktr_rad = deg2rad($latitude_kantor);
    $lon_ktr_rad = deg2rad($longitude_kantor);

    // Haversine formula
    $dlon = $lon_ktr_rad - $lon_mhs_rad;
    $dlat = $lat_ktr_rad - $lat_mhs_rad;
    $a = pow(sin($dlat / 2), 2) + cos($lat_mhs_rad) * cos($lat_ktr_rad) * pow(sin($dlon / 2), 2);
    $c = 2 * asin(sqrt($a));
    $radius_bumi = 6371; // Radius bumi dalam kilometer
    $jarak_km = $radius_bumi * $c;
    $jarak_meter = $jarak_km * 1000;
}
?>
    <link href="
https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5.0.1/dark.min.css
" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body" style="margin: auto;">
                        <input type="hidden" id="id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" id="tanggal_masuk" value="<?= $tanggal_masuk ?>">
                        <input type="hidden" id="jam_masuk" value="<?= $jam_masuk ?>">
                        <div id="my_camera"></div>
                        <div id="my_result"></div>
                        <div><?= date('d F Y', strtotime($tanggal_masuk)) . ' - ' . $jam_masuk ?></div>
                        <button class="btn btn-primary mt-2" id="ambil_foto">Masuk</button>
                        <div id="peringatan" style="color: red; margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    Webcam.set({
        width: 320,
        height: 240,
        dest_width: 320,
        dest_height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90,
        force_flash: false
    });
    Webcam.attach('#my_camera');

    document.getElementById('ambil_foto').addEventListener('click', function() {
        let id = document.getElementById('id').value;
        let tanggal_masuk = document.getElementById('tanggal_masuk').value;
        let jam_masuk = document.getElementById('jam_masuk').value;
        let jarak_meter = <?= $jarak_meter ?>; // Mengambil jarak dari PHP
        let radius = <?= $radius ?>; // Mengambil radius dari PHP

        if (jarak_meter > radius) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Anda tidak berada di lokasi!',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                customClass: {
                    container: 'my-swal'
                }
            });
            return; // Menghentikan proses jika di luar radius
        }

        Webcam.snap(function(data_uri) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Berhasil presensi, redirect
                    window.location.href = '../home/home.php';
                } else if (this.readyState == 4 && this.status != 200) {
                    // Handle error jika perlu
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memproses presensi.',
                        confirmButtonText: 'OK'
                    });
                }
            };
            xhttp.open("POST", "presensi_masuk_aksi.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(
                'photo=' + encodeURIComponent(data_uri) +
                '&id=' + id +
                '&tanggal_masuk=' + tanggal_masuk +
                '&jam_masuk=' + jam_masuk
            );
        });
    });

    // #map leaflet js
    let latitude_ktr = <?= $latitude_kantor ?>;
    let longitude_ktr = <?= $longitude_kantor ?>;
    let latitude_mhs = <?= $latitude_mahasiswa ?>;
    let longitude_mhs = <?= $longitude_mahasiswa ?>;

    let map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map);

    var circle = L.circle([latitude_mhs, longitude_mhs], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: <?= $radius ?> // Radius diambil dari PHP
    }).addTo(map).bindPopup("Lokasi Anda Saat Ini").openPopup();
</script>

<?php include('../layout/footer.php') ?>

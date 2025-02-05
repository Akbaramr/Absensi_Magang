<?php
ob_start();
session_start();  
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = 'Rekap Presensi Bulanan';
include_once("../../config.php");

require('../../assets/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$filter_tahun = isset($_GET['filter_tahun']) ? mysqli_real_escape_string($connection, $_GET['filter_tahun']) : date('Y');
$filter_bulan = isset($_GET['filter_bulan']) ? mysqli_real_escape_string($connection, $_GET['filter_bulan']) : date('m');

$filter_tahun_bulan = $filter_tahun . '-' . $filter_bulan;
$result = mysqli_query($connection, 
        "SELECT presensi.*, mahasiswa.nama, mahasiswa.lokasi_presensi, mahasiswa.nim 
        FROM presensi 
        JOIN mahasiswa ON presensi.id_mahasiswa = mahasiswa.id 
        WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = '$filter_tahun_bulan' 
        ORDER BY tanggal_masuk DESC"
);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'REKAP PRESENSI BULANAN');
$sheet->setCellValue('A2', 'BULAN');
$sheet->setCellValue('A3', 'TAHUN');
$sheet->setCellValue('C2', $filter_bulan);
$sheet->setCellValue('C3', $filter_tahun);
$sheet->setCellValue('A5', 'NO');
$sheet->setCellValue('B5', 'NAMA');
$sheet->setCellValue('C5', 'NIM');
$sheet->setCellValue('D5', 'TANGGAL MASUK');
$sheet->setCellValue('E5', 'JAM MASUK');
$sheet->setCellValue('F5', 'TANGGAL KELUAR');
$sheet->setCellValue('G5', 'JAM KELUAR');
$sheet->setCellValue('H5', 'TOTAL JAM KERJA');
$sheet->setCellValue('I5', 'TOTAL JAM TERLAMBAT');

$sheet->mergeCells('A1:F3');
$sheet->mergeCells('A2:B2');
$sheet->mergeCells('A3:B3');

$no = 1;
$row = 6;

while($data = mysqli_fetch_array($result)){

    // Perhitungan Total Jam Kerja (menggunakan format 24 jam)
    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($data['tanggal_masuk'] . ' ' . 
    $data['jam_masuk']));
    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($data['tanggal_keluar'] . ' ' . 
    $data['jam_keluar']));

    $timestamp_masuk = strtotime($jam_tanggal_masuk);
    $timestamp_keluar = strtotime($jam_tanggal_keluar);

    $selisih = $timestamp_keluar - $timestamp_masuk;
    
    $total_jam_kerja = floor($selisih / 3600);
    $sisa = $selisih - ($total_jam_kerja * 3600);
    $selisih_menit_kerja = floor($sisa / 60);

    // Dapatkan jam masuk kantor untuk lokasi presensi masing-masing mahasiswa
    $lokasi_presensi = $data['lokasi_presensi'];
    if (!isset($locationCache[$lokasi_presensi])) {
        $lokasi_q = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
        $locationCache[$lokasi_presensi] = mysqli_fetch_array($lokasi_q);
    }
    // Jika data lokasi ditemukan, gunakan nilai jam_masuk; jika tidak, gunakan nilai default
    if ($locationCache[$lokasi_presensi] && isset($locationCache[$lokasi_presensi]['jam_masuk'])) {
        $jam_masuk_kantor = date('H:i:s', strtotime($locationCache[$lokasi_presensi]['jam_masuk']));
    } else {
        $jam_masuk_kantor = $jam_masuk_kantor_default;
    }

    // Default nilai keterlambatan
    $total_jam_terlambat = 0;
    $selisih_menit_terlambat = 0;

    // Menghitung keterlambatan: selisih antara jam masuk real dan jam masuk kantor
    $jam_masuk_real = date('H:i:s', strtotime($data['jam_masuk']));
    $timestamp_jam_masuk_real = strtotime($jam_masuk_real);
    $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);
    $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;

    if ($terlambat <= 0) {
        // Tidak terlambat
        $terlambat_output = '<span class="badge bg-success">On Time</span>';
    } else {
        $total_jam_terlambat = floor($terlambat / 3600);
        $sisa_terlambat = $terlambat - ($total_jam_terlambat * 3600);
        $selisih_menit_terlambat = floor($sisa_terlambat / 60);
        $terlambat_output = $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit';
    }

    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['nim']);
    $sheet->setCellValue('D' . $row, $data['tanggal_masuk']);
    $sheet->setCellValue('E' . $row, $data['jam_masuk']);
    $sheet->setCellValue('F' . $row, $data['tanggal_keluar']);
    $sheet->setCellValue('G' . $row, $data['jam_keluar']);
    $sheet->setCellValue('H' . $row, $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit ');
    $sheet->setCellValue('I' . $row, $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit ');

    $no++;
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Presensi Bulanan.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>
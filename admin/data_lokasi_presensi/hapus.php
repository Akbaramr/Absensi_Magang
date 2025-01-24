<?php

session_start();
require_once('../../config.php');

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['validasi'] = 'ID tidak valid!';
    header("Location: lokasi_presensi.php");
    exit;
}

$id = intval($_GET['id']);

// Hapus data
$result = mysqli_query($connection, "DELETE FROM lokasi_presensi WHERE id = $id");

if ($result) {
    $_SESSION['berhasil'] = 'Data berhasil dihapus';
} else {
    $_SESSION['validasi'] = 'Gagal menghapus data: ' . mysqli_error($connection);
}

// Redirect ke halaman lokasi_presensi.php
header("Location: lokasi_presensi.php");
exit;

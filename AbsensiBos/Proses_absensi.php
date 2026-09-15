<?php

session_start();
require_once "koneksi.php";

date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$aksi = $_POST['aksi'] ?? '';

$latitude  = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

$folder = "uploads/selfie/";


// Buat folder jika belum ada
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}


// =====================================================
// ABSEN MASUK
// =====================================================

if ($aksi === 'masuk') {

    // Cek apakah foto ada
    if (!isset($_FILES['selfie']) || $_FILES['selfie']['error'] !== UPLOAD_ERR_OK) {
        die("Foto selfie masuk wajib diupload.");
    }

    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');


    // Cek absensi hari ini
    $stmt = $pdo->prepare("
        SELECT *
        FROM absensi
        WHERE user_id = ?
        AND tanggal = ?
        LIMIT 1
    ");

    $stmt->execute([
        $user_id,
        $tanggal
    ]);

    $absensi = $stmt->fetch(PDO::FETCH_ASSOC);


    // Kalau sudah absen masuk
    if ($absensi && !empty($absensi['jam_masuk'])) {
        die("Anda sudah melakukan absen masuk hari ini.");
    }


    // Validasi file
    $file = $_FILES['selfie'];

    $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($file['type'], $allowed)) {
        die("Format foto harus JPG, PNG, atau WEBP.");
    }


    // Maksimal 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {
        die("Ukuran foto maksimal 5 MB.");
    }


    // Nama file unik
    $namaFile = "masuk_" .
        $user_id . "_" .
        date('YmdHis') . "_" .
        uniqid() .
        ".jpg";


    $tujuan = $folder . $namaFile;


    // Pindahkan foto
    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        die("Gagal menyimpan foto selfie.");
    }


    // Jika data absensi belum ada
    if (!$absensi) {

        $stmt = $pdo->prepare("
            INSERT INTO absensi
            (
                user_id,
                tanggal,
                jam_masuk,
                latitude,
                longitude,
                selfie_masuk
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $user_id,
            $tanggal,
            $jam,
            $latitude,
            $longitude,
            $namaFile
        ]);

    } else {

        // Jika sudah ada data tapi belum absen masuk
        $stmt = $pdo->prepare("
            UPDATE absensi
            SET
                jam_masuk = ?,
                latitude = ?,
                longitude = ?,
                selfie_masuk = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $jam,
            $latitude,
            $longitude,
            $namaFile,
            $absensi['id']
        ]);
    }


    header("Location: dashboard.php?status=absen_masuk");
    exit;
}


// =====================================================
// ABSEN PULANG
// =====================================================

if ($aksi === 'pulang') {

    // Cek foto
    if (!isset($_FILES['selfie']) || $_FILES['selfie']['error'] !== UPLOAD_ERR_OK) {
        die("Foto selfie pulang wajib diupload.");
    }

    $tanggal = date('Y-m-d');
    $jam = date('H:i:s');


    // Cari absensi hari ini
    $stmt = $pdo->prepare("
        SELECT *
        FROM absensi
        WHERE user_id = ?
        AND tanggal = ?
        LIMIT 1
    ");

    $stmt->execute([
        $user_id,
        $tanggal
    ]);

    $absensi = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$absensi) {
        die("Anda belum melakukan absen masuk.");
    }


    if (empty($absensi['jam_masuk'])) {
        die("Anda belum melakukan absen masuk.");
    }


    if (!empty($absensi['jam_keluar'])) {
        die("Anda sudah melakukan absen pulang hari ini.");
    }


    // Validasi file
    $file = $_FILES['selfie'];

    $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($file['type'], $allowed)) {
        die("Format foto harus JPG, PNG, atau WEBP.");
    }


    if ($file['size'] > 5 * 1024 * 1024) {
        die("Ukuran foto maksimal 5 MB.");
    }


    // Nama file
    $namaFile = "pulang_" .
        $user_id . "_" .
        date('YmdHis') . "_" .
        uniqid() .
        ".jpg";


    $tujuan = $folder . $namaFile;


    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        die("Gagal menyimpan foto selfie.");
    }


    // Update absensi
    $stmt = $pdo->prepare("
        UPDATE absensi
        SET
            jam_keluar = ?,
            selfie_pulang = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $jam,
        $namaFile,
        $absensi['id']
    ]);


    header("Location: dashboard.php?status=absen_pulang");
    exit;
}


die("Aksi absensi tidak ditemukan.");

?>

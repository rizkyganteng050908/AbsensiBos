<?php

session_start();
require "koneksi.php";

date_default_timezone_set("Asia/Jakarta");

/* =========================
   CEK LOGIN
========================= */

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

$aksi = $_POST['aksi'] ?? '';

/* =========================
   TANGGAL & JAM JAKARTA
========================= */

$tanggal = date("Y-m-d");
$jam     = date("H:i:s");


/* =========================
   DATA GPS
========================= */

$latitude  = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$jarak     = $_POST['jarak'] ?? null;


/* =========================
   FOLDER FOTO
========================= */

$folder = "uploads/selfie/";

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}


/* =========================
   ABSEN MASUK
========================= */

if ($aksi === "masuk") {

    /* Cek apakah hari ini sudah absen */

    $cek = $pdo->prepare("
        SELECT id
        FROM absensi
        WHERE user_id = ?
        AND tanggal = ?
        LIMIT 1
    ");

    $cek->execute([
        $user_id,
        $tanggal
    ]);

    if ($cek->fetch()) {

        header("Location: absensi.php?pesan=sudah_masuk");
        exit;
    }


    /* Cek foto */

    if (
        !isset($_FILES['selfie']) ||
        $_FILES['selfie']['error'] !== UPLOAD_ERR_OK
    ) {

        header("Location: absensi.php?pesan=foto_gagal");
        exit;
    }


    /* Nama file otomatis */

    $namaFile =
        "masuk_" .
        $user_id .
        "_" .
        date("YmdHis") .
        "_" .
        uniqid() .
        ".jpg";


    $tujuan = $folder . $namaFile;


    /* Simpan foto */

    if (!move_uploaded_file(
        $_FILES['selfie']['tmp_name'],
        $tujuan
    )) {

        header("Location: absensi.php?pesan=upload_gagal");
        exit;
    }


    /* =========================
       SIMPAN KE DATABASE
    ========================= */

    $stmt = $pdo->prepare("
        INSERT INTO absensi
        (
            user_id,
            tanggal,
            jam_masuk,
            latitude,
            longitude,
            jarak,
            selfie_masuk
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $tanggal,
        $jam,
        $latitude,
        $longitude,
        $jarak,
        $namaFile
    ]);


    header("Location: absensi.php?pesan=masuk_berhasil");
    exit;
}


/* =========================
   ABSEN PULANG
========================= */

if ($aksi === "pulang") {

    /* Cari absensi hari ini */

    $cek = $pdo->prepare("
        SELECT *
        FROM absensi
        WHERE user_id = ?
        AND tanggal = ?
        LIMIT 1
    ");

    $cek->execute([
        $user_id,
        $tanggal
    ]);

    $absensi = $cek->fetch(PDO::FETCH_ASSOC);


    /* Belum absen masuk */

    if (!$absensi) {

        header("Location: absensi.php?pesan=belum_masuk");
        exit;
    }


    /* Sudah absen pulang */

    if (!empty($absensi['jam_keluar'])) {

        header("Location: absensi.php?pesan=sudah_pulang");
        exit;
    }


    /* Cek foto */

    if (
        !isset($_FILES['selfie']) ||
        $_FILES['selfie']['error'] !== UPLOAD_ERR_OK
    ) {

        header("Location: absensi.php?pesan=foto_gagal");
        exit;
    }


    /* Nama file */

    $namaFile =
        "pulang_" .
        $user_id .
        "_" .
        date("YmdHis") .
        "_" .
        uniqid() .
        ".jpg";


    $tujuan = $folder . $namaFile;


    /* Simpan foto */

    if (!move_uploaded_file(
        $_FILES['selfie']['tmp_name'],
        $tujuan
    )) {

        header("Location: absensi.php?pesan=upload_gagal");
        exit;
    }


    /* =========================
       UPDATE DATABASE
    ========================= */

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


    header("Location: absensi.php?pesan=pulang_berhasil");
    exit;
}


/* =========================
   AKSI TIDAK VALID
========================= */

header("Location: absensi.php?pesan=aksi_tidak_valid");
exit;

?>

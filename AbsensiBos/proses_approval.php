<?php

session_start();
require "koneksi.php";

/* =========================
   CEK LOGIN ADMIN
========================= */

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}


/* =========================
   CEK METHOD
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: approval.php");
    exit;
}


/* =========================
   AMBIL DATA
========================= */

$id     = $_POST['id'] ?? '';
$status = $_POST['status'] ?? '';


/* =========================
   VALIDASI
========================= */

if (empty($id) || empty($status)) {
    header("Location: approval.php?pesan=data_tidak_lengkap");
    exit;
}


/* =========================
   STATUS YANG DIPERBOLEHKAN
========================= */

$status_valid = [
    'Disetujui',
    'Ditolak'
];

if (!in_array($status, $status_valid)) {
    header("Location: approval.php?pesan=status_tidak_valid");
    exit;
}


/* =========================
   CEK DATA IZIN
========================= */

$stmt = $pdo->prepare("
    SELECT *
    FROM izin
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$izin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$izin) {
    header("Location: approval.php?pesan=data_tidak_ditemukan");
    exit;
}


/* =========================
   CEK STATUS SEBELUMNYA
========================= */

if ($izin['status'] !== 'Menunggu') {
    header("Location: approval.php?pesan=sudah_diproses");
    exit;
}


/* =========================
   UPDATE STATUS
========================= */

$stmt = $pdo->prepare("
    UPDATE izin
    SET
        status = ?,
        diproses_pada = NOW()
    WHERE id = ?
");

$stmt->execute([
    $status,
    $id
]);


/* =========================
   KEMBALI KE APPROVAL
========================= */

header("Location: approval.php?pesan=berhasil");
exit;

?>

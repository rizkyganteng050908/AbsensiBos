<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$aksi = $_POST['aksi'] ?? '';

if ($aksi === 'tambah') {
    $nip        = $_POST['nip'] ?? '';
    $nama       = $_POST['nama'] ?? '';
    $jabatan    = $_POST['jabatan'] ?? '';
    $username   = $_POST['username'] ?? '';
    $password   = $_POST['password'] ?? '';
    $role       = 'karyawan';
    $status_aktif = 1;

    if (!$nip || !$nama || !$username || !$password) {
        echo "<script>alert('Semua kolom wajib diisi!'); history.back();</script>";
        exit;
    }

    try {
        $sql = "INSERT INTO users (nip, nama, jabatan, username, password, role, status_aktif, created_at)
                VALUES (:nip, :nama, :jabatan, :username, :password, :role, :status_aktif, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nip'         => $nip,
            ':nama'        => $nama,
            ':jabatan'     => $jabatan,
            ':username'    => $username,
            ':password'    => $password,
            ':role'        => $role,
            ':status_aktif'=> $status_aktif
        ]);

        header("Location: Karyawan.php?status=berhasil_tambah");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); history.back();</script>";
        exit;
    }
}

if ($aksi === 'password') {
    $id = $_POST['id'];
    $pwd = $_POST['password'];
    $stmt = $pdo->prepare("UPDATE users SET password=:pwd WHERE id=:id");
    $stmt->execute([':pwd'=>$pwd, ':id'=>$id]);
    header("Location: Karyawan.php?status=berhasil_password");
    exit;
}

if ($aksi === 'nonaktifkan') {
    $stmt = $pdo->prepare("UPDATE users SET status_aktif=0 WHERE id=:id");
    $stmt->execute([':id'=>$_POST['id']]);
    header("Location: Karyawan.php?status=berhasil_nonaktif");
    exit;
}

if ($aksi === 'aktifkan') {
    $stmt = $pdo->prepare("UPDATE users SET status_aktif=1 WHERE id=:id");
    $stmt->execute([':id'=>$_POST['id']]);
    header("Location: Karyawan.php?status=berhasil_aktif");
    exit;
}

header("Location: Karyawan.php");
exit;
?>

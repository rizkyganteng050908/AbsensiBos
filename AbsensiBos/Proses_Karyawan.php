<?php
session_start();
require_once "koneksi.php";

// Hanya admin yang boleh akses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$aksi = $_POST['aksi'] ?? '';

// ==============================================
// 1. TAMBAH KARYAWAN
// ==============================================
if ($aksi === 'tambah') {
    // Ambil data dari formulir
    $nip        = trim($_POST['nip']);
    $nama       = trim($_POST['nama']);
    $jabatan    = trim($_POST['jabatan']);
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $role       = 'karyawan';       // otomatis jadi karyawan
    $status_aktif = 1;              // otomatis aktif

    // Simpan ke database pakai PDO (sesuai gaya kode kamu)
    $sql = "INSERT INTO users 
            (nip, nama, jabatan, username, password, role, status_aktif, created_at)
            VALUES 
            (:nip, :nama, :jabatan, :username, :password, :role, :status_aktif, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nip', $nip);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':jabatan', $jabatan);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':status_aktif', $status_aktif);
    
    if ($stmt->execute()) {
        header("Location: Karyawan.php?status=berhasil_tambah");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data!'); history.back();</script>";
    }
}

// ==============================================
// 2. UBAH PASSWORD
// ==============================================
if ($aksi === 'password') {
    $id = $_POST['id'];
    $password_baru = trim($_POST['password']);

    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':password', $password_baru);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: Karyawan.php?status=berhasil_password");
        exit;
    }
}

// ==============================================
// 3. NONAKTIFKAN
// ==============================================
if ($aksi === 'nonaktifkan') {
    $id = $_POST['id'];

    $sql = "UPDATE users SET status_aktif = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: Karyawan.php?status=berhasil_nonaktif");
        exit;
    }
}

// ==============================================
// 4. AKTIFKAN KEMBALI
// ==============================================
if ($aksi === 'aktifkan') {
    $id = $_POST['id'];

    $sql = "UPDATE users SET status_aktif = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: Karyawan.php?status=berhasil_aktif");
        exit;
    }
}

// Kalau akses langsung tanpa aksi
header("Location: Karyawan.php");
exit;
?>

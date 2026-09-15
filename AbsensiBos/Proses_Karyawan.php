<?php

session_start();

require_once "koneksi.php";

// Cek admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$aksi = $_POST['aksi'] ?? '';


// ==================================================
// TAMBAH KARYAWAN
// ==================================================

if ($aksi === 'tambah') {

    $nip      = trim($_POST['nip'] ?? '');
    $nama     = trim($_POST['nama'] ?? '');
    $jabatan  = trim($_POST['jabatan'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi
    if (
        $nip === '' ||
        $nama === '' ||
        $jabatan === '' ||
        $username === '' ||
        $password === ''
    ) {
        die("Semua data wajib diisi.");
    }

    if (strlen($password) < 6) {
        die("Password minimal 6 karakter.");
    }


    // Cek NIP
    $stmt = $pdo->prepare("
        SELECT id
        FROM users
        WHERE nip = ?
        LIMIT 1
    ");

    $stmt->execute([$nip]);

    if ($stmt->fetch()) {
        die("NIP sudah digunakan.");
    }


    // Cek username
    $stmt = $pdo->prepare("
        SELECT id
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        die("Username sudah digunakan.");
    }


    // Hash password
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // Insert
    try {

        $stmt = $pdo->prepare("
            INSERT INTO users
            (
                nip,
                nama,
                jabatan,
                username,
                password,
                role,
                status_aktif
            )
            VALUES (?, ?, ?, ?, ?, 'karyawan', 1)
        ");

        $stmt->execute([
            $nip,
            $nama,
            $jabatan,
            $username,
            $passwordHash
        ]);

        header("Location: karyawan.php?status=berhasil_tambah");
        exit;

    } catch (PDOException $e) {

        die("Gagal menambahkan karyawan: " . $e->getMessage());

    }

}


// ==================================================
// UBAH PASSWORD
// ==================================================

if ($aksi === 'password') {

    $id       = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($id === '') {
        die("ID karyawan tidak ditemukan.");
    }

    if (strlen($password) < 6) {
        die("Password minimal 6 karakter.");
    }


    // Hash password baru
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    try {

        $stmt = $pdo->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
            AND role = 'karyawan'
        ");

        $stmt->execute([
            $passwordHash,
            $id
        ]);

        header("Location: karyawan.php?status=berhasil_password");
        exit;

    } catch (PDOException $e) {

        die("Gagal mengubah password: " . $e->getMessage());

    }

}


// ==================================================
// NONAKTIFKAN KARYAWAN
// ==================================================

if ($aksi === 'nonaktifkan') {

    $id = $_POST['id'] ?? '';

    if ($id === '') {
        die("ID karyawan tidak ditemukan.");
    }


    try {

        $stmt = $pdo->prepare("
            UPDATE users
            SET status_aktif = 0
            WHERE id = ?
            AND role = 'karyawan'
        ");

        $stmt->execute([$id]);

        header("Location: karyawan.php?status=berhasil_nonaktif");
        exit;

    } catch (PDOException $e) {

        die("Gagal menonaktifkan karyawan: " . $e->getMessage());

    }

}


// ==================================================
// AKTIFKAN KARYAWAN
// ==================================================

if ($aksi === 'aktifkan') {

    $id = $_POST['id'] ?? '';

    if ($id === '') {
        die("ID karyawan tidak ditemukan.");
    }


    try {

        $stmt = $pdo->prepare("
            UPDATE users
            SET status_aktif = 1
            WHERE id = ?
            AND role = 'karyawan'
        ");

        $stmt->execute([$id]);

        header("Location: karyawan.php?status=berhasil_aktif");
        exit;

    } catch (PDOException $e) {

        die("Gagal mengaktifkan karyawan: " . $e->getMessage());

    }

}


// ==================================================
// AKSI TIDAK DIKENAL
// ==================================================

die("Aksi tidak ditemukan.");

?>

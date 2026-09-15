<?php

session_start();

require_once "koneksi.php";

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: login.php?error=kosong");
    exit;
}


// Ambil user yang masih aktif
$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE username = ?
    AND status_aktif = 1
    LIMIT 1
");

$stmt->execute([$username]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Cek user dan password
if ($user && password_verify($password, $user['password'])) {

    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nip'] = $user['nip'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['jabatan'] = $user['jabatan'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: dashboard.php");
    exit;

} else {

    header("Location: login.php?error=gagal");
    exit;

}

?>

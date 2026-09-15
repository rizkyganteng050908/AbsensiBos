<?php

$host = "localhost";
$db   = "smart_attendance";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Waktu Jakarta
    $pdo->exec("SET time_zone = '+07:00'");

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>

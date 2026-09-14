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

    $data = [
        [
            "nip" => null,
            "nama" => "Administrator HR",
            "jabatan" => "HR / Admin",
            "username" => "admin",
            "password" => "admin",
            "role" => "admin"
        ],
        [
            "nip" => "EMP001",
            "nama" => "Rizky Ramadhan",
            "jabatan" => "Staff IT",
            "username" => "rizky",
            "password" => "12345",
            "role" => "karyawan"
        ],
        [
            "nip" => "EMP002",
            "nama" => "Andi Setiawan",
            "jabatan" => "Staff Keuangan",
            "username" => "andi",
            "password" => "12345",
            "role" => "karyawan"
        ],
        [
            "nip" => "EMP003",
            "nama" => "Budi Santoso",
            "jabatan" => "Staff Administrasi",
            "username" => "budi",
            "password" => "12345",
            "role" => "karyawan"
        ],
        [
            "nip" => "EMP004",
            "nama" => "Siti Aminah",
            "jabatan" => "Staff HR",
            "username" => "siti",
            "password" => "12345",
            "role" => "karyawan"
        ]
    ];

    $sql = "INSERT INTO users
            (nip, nama, jabatan, username, password, role)
            VALUES
            (:nip, :nama, :jabatan, :username, :password, :role)";

    $stmt = $pdo->prepare($sql);

    foreach ($data as $item) {

        // Password diubah menjadi HASH sebelum masuk database
        $passwordHash = password_hash(
            $item["password"],
            PASSWORD_DEFAULT
        );

        $stmt->execute([
            ":nip" => $item["nip"],
            ":nama" => $item["nama"],
            ":jabatan" => $item["jabatan"],
            ":username" => $item["username"],
            ":password" => $passwordHash,
            ":role" => $item["role"]
        ]);
    }

    echo "<h2>Berhasil!</h2>";
    echo "<p>Semua akun berhasil dibuat dengan password yang sudah di-hash.</p>";

    echo "<h3>Akun untuk Login</h3>";
    echo "<p>Admin → <b>admin</b> / <b>admin</b></p>";
    echo "<p>Rizky → <b>rizky</b> / <b>12345</b></p>";
    echo "<p>Andi → <b>andi</b> / <b>12345</b></p>";
    echo "<p>Budi → <b>budi</b> / <b>12345</b></p>";
    echo "<p>Siti → <b>siti</b> / <b>12345</b></p>";

} catch (PDOException $e) {
    echo "<h2>Gagal!</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
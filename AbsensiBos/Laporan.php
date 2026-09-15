<?php

session_start();
require_once "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


$stmt = $pdo->query("
    SELECT
        absensi.id,
        absensi.tanggal,
        absensi.jam_masuk,
        absensi.jam_keluar,
        absensi.selfie_masuk,
        absensi.selfie_pulang,
        users.nip,
        users.nama,
        users.jabatan
    FROM absensi
    INNER JOIN users
        ON users.id = absensi.user_id
    ORDER BY
        absensi.tanggal DESC,
        absensi.id DESC
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Absensi</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}

.sidebar {
    position: fixed;
    width: 240px;
    height: 100vh;
    background: #1e293b;
    padding: 25px 15px;
    color: white;
}

.logo {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 30px;
}

.menu a {
    display: block;
    padding: 13px;
    color: #cbd5e1;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 8px;
}

.menu a:hover,
.menu a.active {
    background: #2563eb;
    color: white;
}

.content {
    margin-left: 240px;
    padding: 30px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
}

h1 {
    margin-top: 0;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    padding: 13px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
}

th {
    background: #f8fafc;
}

.foto {
    width: 75px;
    height: 75px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid #e2e8f0;
}

.no-foto {
    color: #94a3b8;
}

.badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.masuk {
    background: #dcfce7;
    color: #166534;
}

.pulang {
    background: #fef3c7;
    color: #92400e;
}

@media(max-width:768px) {

    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    .content {
        margin-left: 0;
        padding: 20px;
    }

}

</style>

</head>

<body>


<div class="sidebar">

<div class="logo">
    📊 Smart Attendance
</div>

<div class="menu">

<a href="dashboard.php">
    🏠 Dashboard
</a>

<a href="karyawan.php">
    👨‍💼 Data Karyawan
</a>

<a href="approval.php">
    📋 Approval
</a>

<a href="laporan.php" class="active">
    📊 Laporan Absensi
</a>

<a href="logout.php">
    🚪 Logout
</a>

</div>

</div>


<div class="content">

<div class="card">

<h1>📊 Laporan Absensi</h1>

<p>
Foto selfie karyawan saat melakukan absensi masuk dan pulang.
</p>


<div class="table-wrapper">

<table>

<thead>

<tr>

<th>No</th>

<th>Tanggal</th>

<th>NIP</th>

<th>Nama Karyawan</th>

<th>Jabatan</th>

<th>Jam Masuk</th>

<th>Foto Masuk</th>

<th>Jam Pulang</th>

<th>Foto Pulang</th>

</tr>

</thead>


<tbody>

<?php if (count($data) > 0): ?>

<?php $no = 1; ?>

<?php foreach ($data as $row): ?>

<tr>

<td>
<?= $no++ ?>
</td>


<td>
<?= htmlspecialchars($row['tanggal']) ?>
</td>


<td>
<?= htmlspecialchars($row['nip']) ?>
</td>


<td>
<?= htmlspecialchars($row['nama']) ?>
</td>


<td>
<?= htmlspecialchars($row['jabatan']) ?>
</td>


<td>

<?php if ($row['jam_masuk']): ?>

<span class="badge masuk">
<?= htmlspecialchars($row['jam_masuk']) ?>
</span>

<?php else: ?>

-

<?php endif; ?>

</td>


<td>

<?php if (!empty($row['selfie_masuk'])): ?>

<img
    src="uploads/selfie/<?= htmlspecialchars($row['selfie_masuk']) ?>"
    class="foto"
    onclick="lihatFoto(this.src)"
    alt="Foto Absen Masuk"
>

<?php else: ?>

<span class="no-foto">
Belum ada foto
</span>

<?php endif; ?>

</td>


<td>

<?php if ($row['jam_keluar']): ?>

<span class="badge pulang">
<?= htmlspecialchars($row['jam_keluar']) ?>
</span>

<?php else: ?>

-

<?php endif; ?>

</td>


<td>

<?php if (!empty($row['selfie_pulang'])): ?>

<img
    src="uploads/selfie/<?= htmlspecialchars($row['selfie_pulang']) ?>"
    class="foto"
    onclick="lihatFoto(this.src)"
    alt="Foto Absen Pulang"
>

<?php else: ?>

<span class="no-foto">
Belum ada foto
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="9" style="text-align:center;">
Belum ada data absensi.
</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>


<script>

function lihatFoto(url) {

    window.open(
        url,
        "_blank",
        "width=700,height=700"
    );

}

</script>

</body>

</html>

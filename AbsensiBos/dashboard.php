<?php

session_start();
require_once "koneksi.php";

date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


/* ==========================================
   STATISTIK
========================================== */

$totalKaryawan = $pdo->query("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'karyawan'
    AND status_aktif = 1
")->fetchColumn();


$tanggalHariIni = date('Y-m-d');


$totalMasuk = $pdo->prepare("
    SELECT COUNT(*)
    FROM absensi
    WHERE tanggal = ?
    AND jam_masuk IS NOT NULL
");

$totalMasuk->execute([$tanggalHariIni]);

$jumlahMasuk = $totalMasuk->fetchColumn();


$totalPulang = $pdo->prepare("
    SELECT COUNT(*)
    FROM absensi
    WHERE tanggal = ?
    AND jam_keluar IS NOT NULL
");

$totalPulang->execute([$tanggalHariIni]);

$jumlahPulang = $totalPulang->fetchColumn();


/* ==========================================
   ABSENSI TERBARU
========================================== */

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
    ORDER BY absensi.id DESC
    LIMIT 10
");

$absensiTerbaru = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - Smart Attendance</title>

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


/* SIDEBAR */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 240px;
    height: 100vh;
    background: #1e293b;
    color: white;
    padding: 25px 15px;
}

.logo {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 30px;
}

.menu a {
    display: block;
    color: #cbd5e1;
    text-decoration: none;
    padding: 13px 15px;
    margin-bottom: 8px;
    border-radius: 8px;
}

.menu a:hover,
.menu a.active {
    background: #2563eb;
    color: white;
}


/* CONTENT */

.content {
    margin-left: 240px;
    padding: 30px;
}

.header {
    margin-bottom: 25px;
}

.header h1 {
    margin: 0;
    font-size: 28px;
}

.header p {
    color: #64748b;
}


/* STATISTIK */

.stats {
    display: grid;
    grid-template-columns:
        repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
}

.stat-icon {
    font-size: 30px;
    margin-bottom: 10px;
}

.stat-title {
    color: #64748b;
}

.stat-number {
    font-size: 30px;
    font-weight: bold;
    margin-top: 5px;
}


/* CARD */

.card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
    margin-bottom: 25px;
}

.card h2 {
    margin-top: 0;
}


/* ABSENSI */

.absensi-list {
    display: grid;
    grid-template-columns:
        repeat(2, 1fr);
    gap: 20px;
}

.absensi-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px;
}

.karyawan {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 20px;
}

.nama {
    font-weight: bold;
    font-size: 17px;
}

.nip {
    color: #64748b;
    font-size: 13px;
}


.info {
    display: grid;
    grid-template-columns:
        1fr 1fr;
    gap: 15px;
}

.info-box {
    background: #f8fafc;
    padding: 12px;
    border-radius: 10px;
}

.info-title {
    color: #64748b;
    font-size: 12px;
    margin-bottom: 5px;
}


/* FOTO */

.foto-container {
    display: grid;
    grid-template-columns:
        1fr 1fr;
    gap: 15px;
    margin-top: 15px;
}

.foto-box {
    text-align: center;
}

.foto-label {
    font-weight: bold;
    margin-bottom: 8px;
}

.foto {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: .2s;
}

.foto:hover {
    transform: scale(1.02);
}

.no-foto {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-radius: 10px;
    color: #94a3b8;
}


/* BUTTON */

.btn-laporan {
    display: inline-block;
    padding: 11px 17px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 20px;
}

.btn-laporan:hover {
    background: #1d4ed8;
}


/* MOBILE */

@media(max-width: 900px) {

    .stats {
        grid-template-columns: 1fr;
    }

    .absensi-list {
        grid-template-columns: 1fr;
    }

}


@media(max-width: 768px) {

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


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        📊 Smart Attendance
    </div>

    <div class="menu">

        <a href="dashboard.php" class="active">
            🏠 Dashboard
        </a>

        <a href="karyawan.php">
            👨‍💼 Data Karyawan
        </a>

        <a href="approval.php">
            📋 Approval
        </a>

        <a href="laporan.php">
            📊 Laporan Absensi
        </a>

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>


<!-- CONTENT -->

<div class="content">


<div class="header">

    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>
    </p>

</div>


<!-- STATISTIK -->

<div class="stats">


<div class="stat-card">

    <div class="stat-icon">
        👨‍💼
    </div>

    <div class="stat-title">
        Karyawan Aktif
    </div>

    <div class="stat-number">
        <?= $totalKaryawan ?>
    </div>

</div>


<div class="stat-card">

    <div class="stat-icon">
        🟢
    </div>

    <div class="stat-title">
        Absen Masuk Hari Ini
    </div>

    <div class="stat-number">
        <?= $jumlahMasuk ?>
    </div>

</div>


<div class="stat-card">

    <div class="stat-icon">
        🟠
    </div>

    <div class="stat-title">
        Absen Pulang Hari Ini
    </div>

    <div class="stat-number">
        <?= $jumlahPulang ?>
    </div>

</div>


</div>


<!-- FOTO ABSENSI -->

<div class="card">

<h2>
    📸 Foto Absensi Karyawan Terbaru
</h2>

<p style="color:#64748b;">
    Menampilkan foto selfie saat karyawan melakukan absensi.
</p>


<a
    href="laporan.php"
    class="btn-laporan"
>
    📊 Lihat Semua Laporan
</a>


<div class="absensi-list">


<?php if (count($absensiTerbaru) > 0): ?>


<?php foreach ($absensiTerbaru as $row): ?>


<div class="absensi-item">


<!-- NAMA -->

<div class="karyawan">

<div class="avatar">

<?= strtoupper(
    substr($row['nama'], 0, 1)
) ?>

</div>


<div>

<div class="nama">

<?= htmlspecialchars(
    $row['nama']
) ?>

</div>

<div class="nip">

<?= htmlspecialchars(
    $row['nip']
) ?>

</div>

</div>

</div>


<!-- INFO -->

<div class="info">


<div class="info-box">

<div class="info-title">
Tanggal
</div>

<strong>
<?= htmlspecialchars(
    $row['tanggal']
) ?>
</strong>

</div>


<div class="info-box">

<div class="info-title">
Jabatan
</div>

<strong>
<?= htmlspecialchars(
    $row['jabatan']
) ?>
</strong>

</div>


</div>


<!-- FOTO -->

<div class="foto-container">


<!-- FOTO MASUK -->

<div class="foto-box">

<div class="foto-label">
🟢 Absen Masuk
</div>


<?php if (!empty($row['selfie_masuk'])): ?>

<img
    src="uploads/selfie/<?= htmlspecialchars($row['selfie_masuk']) ?>"
    class="foto"
    onclick="lihatFoto(this.src)"
    alt="Foto Absen Masuk"
>


<div style="
    margin-top:7px;
    color:#166534;
">

<?= htmlspecialchars(
    $row['jam_masuk']
) ?>

</div>


<?php else: ?>

<div class="no-foto">
    Belum Absen
</div>

<?php endif; ?>

</div>


<!-- FOTO PULANG -->

<div class="foto-box">

<div class="foto-label">
🟠 Absen Pulang
</div>


<?php if (!empty($row['selfie_pulang'])): ?>

<img
    src="uploads/selfie/<?= htmlspecialchars($row['selfie_pulang']) ?>"
    class="foto"
    onclick="lihatFoto(this.src)"
    alt="Foto Absen Pulang"
>


<div style="
    margin-top:7px;
    color:#92400e;
">

<?= htmlspecialchars(
    $row['jam_keluar']
) ?>

</div>


<?php else: ?>

<div class="no-foto">
    Belum Absen
</div>

<?php endif; ?>

</div>


</div>


</div>


<?php endforeach; ?>


<?php else: ?>


<div style="
    padding:30px;
    text-align:center;
    color:#64748b;
">

    📷 Belum ada data absensi.

</div>


<?php endif; ?>


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

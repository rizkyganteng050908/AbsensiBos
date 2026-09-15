<?php
session_start();
require "koneksi.php";

if (
    !isset($_SESSION['user']) ||
    $_SESSION['user']['role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}

$sql = "
    SELECT
        absensi.*,
        users.nip,
        users.nama,
        users.jabatan
    FROM absensi
    INNER JOIN users ON absensi.user_id = users.id
    ORDER BY absensi.tanggal DESC, absensi.id DESC
";

$stmt = $pdo->query($sql);
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
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f1f5f9;
    padding: 30px;
}

.container {
    max-width: 1400px;
    margin: auto;
}

.header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
}

.header h1 {
    color: #1e293b;
    margin-bottom: 8px;
}

.header p {
    color: #64748b;
}

.table-box {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #2563eb;
    color: white;
    padding: 14px;
    text-align: center;
}

td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
    vertical-align: middle;
}

tr:hover {
    background: #f8fafc;
}

.foto {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
}

.foto:hover {
    transform: scale(1.05);
}

.tidak-ada {
    color: #94a3b8;
}

.jam {
    font-weight: bold;
    color: #1e293b;
}

</style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>📊 Laporan Absensi Karyawan</h1>

        <p>
            Data absensi masuk dan pulang karyawan.
        </p>

    </div>


    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Tanggal</th>

                    <th>NIP</th>

                    <th>Nama</th>

                    <th>Jabatan</th>

                    <th>Jam Masuk</th>

                    <th>Foto Masuk</th>

                    <th>Jam Pulang</th>

                    <th>Foto Pulang</th>

                </tr>

            </thead>


            <tbody>

            <?php if (empty($data)): ?>

                <tr>

                    <td colspan="9">

                        Belum ada data absensi.

                    </td>

                </tr>

            <?php else: ?>

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
                            <strong>
                                <?= htmlspecialchars($row['nama']) ?>
                            </strong>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['jabatan']) ?>
                        </td>

                        <td class="jam">

                            <?php if (!empty($row['jam_masuk'])): ?>

                                <?= htmlspecialchars($row['jam_masuk']) ?>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>


                        <td>

                            <?php if (!empty($row['selfie_masuk'])): ?>

                                <img
                                    src="uploads/selfie/<?= htmlspecialchars($row['selfie_masuk']) ?>"
                                    class="foto"
                                    onclick="window.open(this.src, '_blank')"
                                    alt="Foto Masuk"
                                >

                            <?php else: ?>

                                <span class="tidak-ada">
                                    Tidak ada
                                </span>

                            <?php endif; ?>

                        </td>


                        <td class="jam">

                            <?php if (!empty($row['jam_keluar'])): ?>

                                <?= htmlspecialchars($row['jam_keluar']) ?>

                            <?php else: ?>

                                -

                            <?php endif; ?>

                        </td>


                        <td>

                            <?php if (!empty($row['selfie_pulang'])): ?>

                                <img
                                    src="uploads/selfie/<?= htmlspecialchars($row['selfie_pulang']) ?>"
                                    class="foto"
                                    onclick="window.open(this.src, '_blank')"
                                    alt="Foto Pulang"
                                >

                            <?php else: ?>

                                <span class="tidak-ada">
                                    Belum absen pulang
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>

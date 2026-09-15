<?php
session_start();
require_once "koneksi.php";

// Hanya admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan
$stmt = $pdo->query("
    SELECT id, nip, nama, jabatan, username, status_aktif, created_at
    FROM users
    WHERE role = 'karyawan'
    ORDER BY id DESC
");

$karyawan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kelola Karyawan - Smart Attendance</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}

/* SIDEBAR */

.sidebar {
    position: fixed;
    width: 240px;
    height: 100vh;
    background: #1e293b;
    color: white;
    padding: 25px 15px;
}

.logo {
    font-size: 21px;
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
}

.menu a {
    display: block;
    padding: 13px 15px;
    margin-bottom: 8px;
    color: #cbd5e1;
    text-decoration: none;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header h1 {
    font-size: 28px;
}

.header p {
    color: #64748b;
    margin-top: 5px;
}

/* CARD */

.card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,.06);
    margin-bottom: 25px;
}

.card h2 {
    margin-bottom: 20px;
}

/* FORM */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 7px;
    font-weight: bold;
}

.form-group input {
    padding: 11px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    outline: none;
}

.form-group input:focus {
    border-color: #2563eb;
}

.btn {
    border: none;
    padding: 11px 16px;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
}

/* TABLE */

.table-wrapper {
    overflow-x: auto;
}

.search {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 13px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

th {
    background: #f8fafc;
}

tr:hover {
    background: #f8fafc;
}

/* BADGE */

.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.aktif {
    background: #dcfce7;
    color: #166534;
}

.nonaktif {
    background: #fee2e2;
    color: #991b1b;
}

/* ACTION */

.action {
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
}

.btn-password {
    background: #f59e0b;
    color: white;
}

.btn-nonaktif {
    background: #ef4444;
    color: white;
}

.btn-aktif {
    background: #10b981;
    color: white;
}

/* ALERT */

.alert {
    padding: 13px;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #dcfce7;
    color: #166534;
}

/* MODAL */

.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.5);
}

.modal-content {
    background: white;
    width: 400px;
    max-width: 90%;
    margin: 100px auto;
    padding: 25px;
    border-radius: 12px;
}

.modal-content h2 {
    margin-bottom: 20px;
}

.close {
    float: right;
    font-size: 25px;
    cursor: pointer;
}

/* MOBILE */

@media(max-width: 768px) {

    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .content {
        margin-left: 0;
        padding: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .header {
        display: block;
    }

    .header h1 {
        font-size: 24px;
        margin-bottom: 5px;
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

        <a href="dashboard.php">
            🏠 Dashboard
        </a>

        <a href="karyawan.php" class="active">
            👨‍💼 Data Karyawan
        </a>

        <a href="approval.php">
            📋 Approval
        </a>

        <a href="laporan.php">
            📊 Laporan
        </a>

        <a href="logout.php">
            🚪 Logout
        </a>

    </div>

</div>


<!-- CONTENT -->

<div class="content">

    <div class="header">

        <div>
            <h1>Data Karyawan</h1>
            <p>Kelola data karyawan Smart Attendance</p>
        </div>

    </div>


    <?php if ($status === 'berhasil_tambah'): ?>

        <div class="alert">
            ✅ Karyawan berhasil ditambahkan.
        </div>

    <?php elseif ($status === 'berhasil_password'): ?>

        <div class="alert">
            🔑 Password berhasil diubah.
        </div>

    <?php elseif ($status === 'berhasil_nonaktif'): ?>

        <div class="alert">
            🚫 Karyawan berhasil dinonaktifkan.
        </div>

    <?php elseif ($status === 'berhasil_aktif'): ?>

        <div class="alert">
            ✅ Karyawan berhasil diaktifkan kembali.
        </div>

    <?php endif; ?>


    <!-- TAMBAH KARYAWAN -->

    <div class="card">

        <h2>➕ Tambah Karyawan</h2>

        <form action="proses_karyawan.php" method="POST">

            <input type="hidden" name="aksi" value="tambah">

            <div class="form-grid">

                <div class="form-group">
                    <label>NIP</label>
                    <input
                        type="text"
                        name="nip"
                        placeholder="Contoh: EMP005"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama"
                        placeholder="Nama karyawan"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <input
                        type="text"
                        name="jabatan"
                        placeholder="Contoh: Staff IT"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        placeholder="Username login"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Password Awal</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                </div>

            </div>

            <br>

            <button type="submit" class="btn btn-primary">
                💾 Simpan Karyawan
            </button>

        </form>

    </div>


    <!-- DATA KARYAWAN -->

    <div class="card">

        <h2>👥 Daftar Karyawan</h2>

        <input
            type="text"
            id="search"
            class="search"
            placeholder="🔍 Cari nama, NIP, username atau jabatan..."
        >

        <div class="table-wrapper">

            <table id="tabelKaryawan">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php if (count($karyawan) > 0): ?>

                    <?php $no = 1; ?>

                    <?php foreach ($karyawan as $data): ?>

                    <tr>

                        <td>
                            <?= $no++ ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nip']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['jabatan']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['username']) ?>
                        </td>

                        <td>

                            <?php if ($data['status_aktif'] == 1): ?>

                                <span class="badge aktif">
                                    Aktif
                                </span>

                            <?php else: ?>

                                <span class="badge nonaktif">
                                    Nonaktif
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="action">

                                <!-- PASSWORD -->

                                <button
                                    type="button"
                                    class="btn btn-password"
                                    onclick="bukaPassword(
                                        <?= $data['id'] ?>,
                                        '<?= htmlspecialchars($data['nama'], ENT_QUOTES) ?>'
                                    )"
                                >
                                    🔑 Password
                                </button>


                                <?php if ($data['status_aktif'] == 1): ?>

                                    <!-- NONAKTIF -->

                                    <form
                                        action="proses_karyawan.php"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menonaktifkan karyawan ini?')"
                                    >

                                        <input
                                            type="hidden"
                                            name="aksi"
                                            value="nonaktifkan"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $data['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-nonaktif"
                                        >
                                            🚫 Nonaktifkan
                                        </button>

                                    </form>

                                <?php else: ?>

                                    <!-- AKTIFKAN -->

                                    <form
                                        action="proses_karyawan.php"
                                        method="POST"
                                        onsubmit="return confirm('Aktifkan kembali karyawan ini?')"
                                    >

                                        <input
                                            type="hidden"
                                            name="aksi"
                                            value="aktifkan"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $data['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-aktif"
                                        >
                                            ✅ Aktifkan
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7" style="text-align:center;">
                            Belum ada data karyawan.
                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- MODAL PASSWORD -->

<div id="modalPassword" class="modal">

    <div class="modal-content">

        <span class="close" onclick="tutupPassword()">
            &times;
        </span>

        <h2>🔑 Ubah Password</h2>

        <p id="namaKaryawan"></p>

        <br>

        <form action="proses_karyawan.php" method="POST">

            <input
                type="hidden"
                name="aksi"
                value="password"
            >

            <input
                type="hidden"
                name="id"
                id="idKaryawan"
            >

            <div class="form-group">

                <label>Password Baru</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    minlength="6"
                    required
                >

            </div>

            <br>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Password
            </button>

        </form>

    </div>

</div>


<script>

// SEARCH

document.getElementById("search").addEventListener("keyup", function() {

    let keyword = this.value.toLowerCase();

    let rows = document.querySelectorAll("#tabelKaryawan tbody tr");

    rows.forEach(function(row) {

        let text = row.innerText.toLowerCase();

        if (text.includes(keyword)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});


// MODAL PASSWORD

function bukaPassword(id, nama) {

    document.getElementById("idKaryawan").value = id;

    document.getElementById("namaKaryawan").innerText =
        "Karyawan: " + nama;

    document.getElementById("modalPassword").style.display = "block";
}


function tutupPassword() {

    document.getElementById("modalPassword").style.display = "none";

}


window.onclick = function(event) {

    let modal = document.getElementById("modalPassword");

    if (event.target === modal) {
        modal.style.display = "none";
    }

}

</script>

</body>
</html>

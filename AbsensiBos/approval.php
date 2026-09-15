<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "
    SELECT 
        izin.*,
        users.nip,
        users.nama,
        users.jabatan
    FROM izin
    JOIN users ON izin.user_id = users.id
    ORDER BY izin.created_at DESC
";

$data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Approval Izin - Smart Attendance</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f7fb;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
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
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8fafc;
        }

        .bukti-img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: .2s;
        }

        .bukti-img:hover {
            transform: scale(1.05);
        }

        .no-bukti {
            color: #94a3b8;
        }

        .status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .disetujui {
            background: #dcfce7;
            color: #166534;
        }

        .ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            border: none;
            padding: 8px 12px;
            border-radius: 7px;
            color: white;
            cursor: pointer;
            margin: 2px;
        }

        .btn-setuju {
            background: #16a34a;
        }

        .btn-tolak {
            background: #dc2626;
        }

        .btn:hover {
            opacity: .85;
        }

        /* MODAL GAMBAR */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,.85);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,.5);
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>📋 Approval Izin Karyawan</h1>
        <p>Kelola pengajuan izin, cuti, dan sakit karyawan.</p>
    </div>

    <div class="table-box">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Karyawan</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if (count($data) == 0): ?>

                <tr>
                    <td colspan="8" style="text-align:center;">
                        Belum ada pengajuan izin.
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
                            <strong>
                                <?= htmlspecialchars($row['nama']) ?>
                            </strong>
                            <br>
                            <small>
                                <?= htmlspecialchars($row['nip']) ?>
                            </small>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['jenis']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['tanggal']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['keterangan']) ?>
                        </td>

                        <td>

                            <?php if (!empty($row['bukti'])): ?>

                                <img
                                    src="uploads/bukti/<?= htmlspecialchars($row['bukti']) ?>"
                                    class="bukti-img"
                                    alt="Bukti izin"
                                    onclick="openImage(this.src)"
                                >

                            <?php else: ?>

                                <span class="no-bukti">
                                    Tidak ada
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if ($row['status'] == 'Menunggu'): ?>

                                <span class="status menunggu">
                                    Menunggu
                                </span>

                            <?php elseif ($row['status'] == 'Disetujui'): ?>

                                <span class="status disetujui">
                                    Disetujui
                                </span>

                            <?php else: ?>

                                <span class="status ditolak">
                                    Ditolak
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if ($row['status'] == 'Menunggu'): ?>

                                <form
                                    action="proses_approval.php"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $row['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="Disetujui"
                                    >

                                    <button
                                        class="btn btn-setuju"
                                        type="submit"
                                    >
                                        ✓ Setujui
                                    </button>

                                </form>


                                <form
                                    action="proses_approval.php"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $row['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="Ditolak"
                                    >

                                    <button
                                        class="btn btn-tolak"
                                        type="submit"
                                    >
                                        ✕ Tolak
                                    </button>

                                </form>

                            <?php else: ?>

                                <span style="color:#94a3b8;">
                                    Selesai
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


<!-- MODAL GAMBAR -->

<div
    class="modal"
    id="imageModal"
    onclick="closeImage()"
>

    <span class="close">
        &times;
    </span>

    <img
        id="modalImage"
        src=""
        alt="Bukti"
        onclick="event.stopPropagation()"
    >

</div>


<script>

function openImage(src) {

    document.getElementById("modalImage").src = src;

    document.getElementById("imageModal").style.display = "flex";

}

function closeImage() {

    document.getElementById("imageModal").style.display = "none";

    document.getElementById("modalImage").src = "";

}

</script>

</body>
</html>

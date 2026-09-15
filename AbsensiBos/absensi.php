<?php

session_start();
require_once "koneksi.php";

date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$tanggal = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT *
    FROM absensi
    WHERE user_id = ?
    AND tanggal = ?
    LIMIT 1
");

$stmt->execute([
    $user_id,
    $tanggal
]);

$absensi = $stmt->fetch(PDO::FETCH_ASSOC);

$sudahMasuk = $absensi && !empty($absensi['jam_masuk']);
$sudahPulang = $absensi && !empty($absensi['jam_keluar']);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Absensi Karyawan</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
}

.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 20px;
}

h1 {
    margin-bottom: 5px;
}

.info {
    color: #64748b;
    margin-bottom: 20px;
}

.camera {
    width: 100%;
    max-width: 600px;
    margin: auto;
}

video,
canvas {
    width: 100%;
    border-radius: 15px;
    background: #0f172a;
}

canvas {
    display: none;
}

button {
    border: none;
    padding: 13px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 15px;
}

.btn-camera {
    background: #2563eb;
    color: white;
}

.btn-absen {
    background: #10b981;
    color: white;
}

.btn-pulang {
    background: #f59e0b;
    color: white;
}

.btn-disabled {
    background: #94a3b8;
    color: white;
    cursor: not-allowed;
}

.status {
    padding: 15px;
    border-radius: 10px;
    background: #f8fafc;
    margin-top: 20px;
}

form {
    margin-top: 15px;
}

.hidden {
    display: none;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    color: #2563eb;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="back">
    ← Kembali ke Dashboard
</a>

<div class="card">

<h1>📸 Absensi Karyawan</h1>

<p class="info">
    Silakan aktifkan kamera dan ambil foto selfie untuk melakukan absensi.
</p>


<div class="camera">

<video id="video" autoplay playsinline></video>

<canvas id="canvas"></canvas>

</div>


<button
    type="button"
    class="btn-camera"
    onclick="aktifkanKamera()"
>
    📷 Aktifkan Kamera
</button>


<button
    type="button"
    class="btn-camera"
    onclick="ambilFoto()"
>
    📸 Ambil Foto
</button>


<form
    id="formAbsensi"
    action="proses_absensi.php"
    method="POST"
    enctype="multipart/form-data"
>

<input
    type="hidden"
    name="aksi"
    id="aksi"
>

<input
    type="hidden"
    name="latitude"
    id="latitude"
>

<input
    type="hidden"
    name="longitude"
    id="longitude"
>


<input
    type="file"
    name="selfie"
    id="selfie"
    accept="image/*"
    capture="user"
    hidden
>


<button
    type="submit"
    class="btn-absen"
    id="btnMasuk"
    onclick="setAksi('masuk')"
    <?= $sudahMasuk ? 'disabled class="btn-disabled"' : '' ?>
>
    <?= $sudahMasuk ? '✅ Sudah Absen Masuk' : '🟢 Absen Masuk' ?>
</button>


<button
    type="submit"
    class="btn-pulang"
    id="btnPulang"
    onclick="setAksi('pulang')"
    <?= (!$sudahMasuk || $sudahPulang) ? 'disabled class="btn-disabled"' : '' ?>
>
    <?= $sudahPulang ? '✅ Sudah Absen Pulang' : '🟠 Absen Pulang' ?>
</button>

</form>


<div class="status">

<strong>Status Hari Ini</strong>

<br><br>

Jam Masuk:
<?= $absensi['jam_masuk'] ?? '-' ?>

<br>

Jam Pulang:
<?= $absensi['jam_keluar'] ?? '-' ?>

</div>

</div>

</div>


<script>

let video = document.getElementById("video");

let canvas = document.getElementById("canvas");

let stream = null;


// AKTIFKAN KAMERA

async function aktifkanKamera() {

    try {

        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "user"
            },
            audio: false
        });

        video.srcObject = stream;

    } catch (error) {

        alert(
            "Kamera tidak dapat digunakan. Pastikan izin kamera diberikan."
        );

        console.error(error);

    }

}


// AMBIL FOTO

function ambilFoto() {

    if (!stream) {

        alert("Aktifkan kamera terlebih dahulu.");

        return;

    }


    canvas.width = video.videoWidth;

    canvas.height = video.videoHeight;


    let context = canvas.getContext("2d");

    context.drawImage(
        video,
        0,
        0,
        canvas.width,
        canvas.height
    );


    canvas.toBlob(function(blob) {

        let file = new File(
            [blob],
            "selfie.jpg",
            {
                type: "image/jpeg"
            }
        );


        let dataTransfer = new DataTransfer();

        dataTransfer.items.add(file);

        document.getElementById("selfie").files =
            dataTransfer.files;


        alert("Foto berhasil diambil.");

    }, "image/jpeg", 0.9);

}


// AKSI

function setAksi(aksi) {

    document.getElementById("aksi").value = aksi;

}


// GPS

if (navigator.geolocation) {

    navigator.geolocation.getCurrentPosition(

        function(position) {

            document.getElementById("latitude").value =
                position.coords.latitude;

            document.getElementById("longitude").value =
                position.coords.longitude;

        },

        function(error) {

            console.log(
                "Lokasi tidak tersedia."
            );

        }

    );

}

</script>

</body>

</html>

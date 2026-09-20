<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Attendance Management System</title>

<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:Arial,sans-serif;
}

body{
    background:#f1f5f9;
    color:#1e293b;
}

.login-page{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
}

.login-box{
    background:white;
    width:380px;
    padding:35px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,.2);
}

.login-box h1{
    text-align:center;
    color:#2563eb;
    margin-bottom:8px;
}

.login-box p{
    text-align:center;
    color:#64748b;
    margin-bottom:25px;
}

input,select,textarea{
    width:100%;
    padding:12px;
    margin:7px 0 15px;
    border:1px solid #cbd5e1;
    border-radius:8px;
    outline:none;
}

input:focus,select:focus,textarea:focus{
    border-color:#2563eb;
}

button{
    border:none;
    padding:11px 16px;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.btn-primary{
    background:#2563eb;
    color:white;
}

.btn-success{
    background:#10b981;
    color:white;
}

.btn-danger{
    background:#ef4444;
    color:white;
}

.btn-warning{
    background:#f59e0b;
    color:white;
}

.btn-secondary{
    background:#64748b;
    color:white;
}

button:hover{
    opacity:.9;
}

#app{
    display:none;
}

.sidebar{
    width:240px;
    height:100vh;
    background:#0f172a;
    color:white;
    position:fixed;
    left:0;
    top:0;
    padding:20px;
}

.logo{
    font-size:20px;
    font-weight:bold;
    margin-bottom:30px;
    color:#60a5fa;
}

.menu button{
    display:block;
    width:100%;
    text-align:left;
    background:transparent;
    color:#cbd5e1;
    margin-bottom:7px;
}

.menu button:hover{
    background:#1e293b;
    color:white;
}

.logout{
    position:absolute;
    bottom:20px;
    width:200px;
    background:#dc2626;
    color:white;
}

.main{
    margin-left:240px;
    padding:25px;
}

.topbar{
    background:white;
    padding:18px 22px;
    border-radius:12px;
    margin-bottom:25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.user-info{
    text-align:right;
}

.clock{
    font-size:15px;
    font-weight:bold;
    color:#2563eb;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:18px;
    margin-bottom:25px;
}

.card{
    background:white;
    padding:22px;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,.06);
}

.card h3{
    color:#64748b;
    font-size:14px;
    margin-bottom:10px;
}

.card .number{
    font-size:28px;
    font-weight:bold;
    color:#2563eb;
}

.section{
    background:white;
    padding:22px;
    border-radius:14px;
    margin-bottom:20px;
}

.section h2{
    margin-bottom:18px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
    text-align:left;
    font-size:14px;
}

th{
    background:#f8fafc;
}

.badge{
    padding:5px 9px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.badge-green{
    background:#dcfce7;
    color:#166534;
}

.badge-red{
    background:#fee2e2;
    color:#991b1b;
}

.badge-yellow{
    background:#fef3c7;
    color:#92400e;
}

.employee-box{
    background:#eff6ff;
    border-left:5px solid #2563eb;
    padding:18px;
    border-radius:8px;
    margin-bottom:20px;
}

.action-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:15px;
}

.action-card{
    padding:20px;
    border:1px solid #e2e8f0;
    border-radius:12px;
}

.action-card h3{
    margin-bottom:8px;
}

.camera-box{
    margin-top:15px;
}

video{
    width:100%;
    max-width:400px;
    border-radius:12px;
    background:#000;
}

.hidden{
    display:none !important;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-box{
    background:white;
    width:420px;
    max-width:95%;
    padding:25px;
    border-radius:15px;
}

.modal-box h2{
    margin-bottom:20px;
}

.modal-actions{
    display:flex;
    gap:10px;
    justify-content:flex-end;
}

.alert{
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
    background:#eff6ff;
    color:#1e40af;
}

@media(max-width:768px){

    .sidebar{
        width:100%;
        height:auto;
        position:relative;
    }

    .main{
        margin-left:0;
    }

    .logout{
        position:static;
        margin-top:15px;
        width:100%;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .user-info{
        text-align:left;
    }

    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
    }
}
</style>
</head>

<body>

<!-- ==================================================
     LOGIN
================================================== -->

<div class="login-page" id="loginPage">

    <div class="login-box">

        <h1>Smart Attendance</h1>

        <p>Sistem Absensi Karyawan</p>

        <div id="loginAlert"></div>

        <label>Username</label>

        <input
            type="text"
            id="loginUsername"
            placeholder="Masukkan username"
        >

        <label>Password</label>

        <input
            type="password"
            id="loginPassword"
            placeholder="Masukkan password"
        >

        <button
            class="btn-primary"
            style="width:100%"
            onclick="login()"
        >
            Login
        </button>

        <br><br>

        <small style="color:#64748b">
            Admin: admin / admin<br>
            Karyawan: rizky / 12345
        </small>

    </div>

</div>


<!-- ==================================================
     APP
================================================== -->

<div id="app">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">
            📍 Smart Attendance
        </div>

        <div class="menu">

            <button onclick="showPage('dashboard')">
                🏠 Dashboard
            </button>

            <button id="menuAbsensi"
                    onclick="showPage('absensi')">
                🕒 Absensi
            </button>

            <button id="menuIzin"
                    onclick="showPage('izin')">
                📝 Izin / Cuti
            </button>

            <button onclick="showPage('riwayat')">
                📋 Riwayat Absensi
            </button>

            <button id="menuKaryawan"
                    onclick="showPage('karyawan')">
                👥 Data Karyawan
            </button>

            <button id="menuApproval"
                    onclick="showPage('approval')">
                ✅ Persetujuan
            </button>

            <button id="menuLaporan"
                    onclick="showPage('laporan')">
                📊 Laporan
            </button>

        </div>

        <button class="logout" onclick="logout()">
            🚪 Logout
        </button>

    </aside>


    <!-- MAIN -->

    <main class="main">

        <div class="topbar">

            <div>

                <h2 id="pageTitle">
                    Dashboard
                </h2>

                <small id="currentDate">
                </small>

                <div class="clock">
                    🕐 WIB:
                    <span id="jakartaClock">
                        00:00:00
                    </span>
                </div>

            </div>

            <div class="user-info">

                <b id="topUserName"></b>

                <br>

                <small id="topUserRole"></small>

            </div>

        </div>


        <!-- ==================================================
             DASHBOARD
        ================================================== -->

        <section id="dashboard" class="page">

            <div id="employeeInfo"></div>

            <div class="cards">

                <div class="card">

                    <h3>Total Karyawan</h3>

                    <div
                        class="number"
                        id="totalKaryawan">
                        0
                    </div>

                </div>


                <div class="card">

                    <h3>Total Absensi</h3>

                    <div
                        class="number"
                        id="totalAbsensi">
                        0
                    </div>

                </div>


                <div class="card">

                    <h3>Hadir Hari Ini</h3>

                    <div
                        class="number"
                        id="hadirHariIni">
                        0
                    </div>

                </div>


                <div class="card">

                    <h3>Pengajuan Izin</h3>

                    <div
                        class="number"
                        id="totalIzin">
                        0
                    </div>

                </div>

            </div>


            <div class="section">

                <h2>
                    Selamat Datang 👋
                </h2>

                <p>
                    Sistem menggunakan waktu resmi Jakarta
                    (WIB / Asia-Jakarta).
                </p>

            </div>

        </section>


        <!-- ==================================================
             ABSENSI
        ================================================== -->

        <section id="absensi"
                 class="page hidden">

            <div class="section">

                <h2>
                    Absensi Karyawan
                </h2>

                <div
                    class="employee-box"
                    id="absensiEmployeeInfo">
                </div>


                <div class="alert"
                     id="gpsStatus">

                    GPS belum diperiksa.

                </div>


                <div class="action-grid">

                    <div class="action-card">

                        <h3>
                            📍 Lokasi GPS
                        </h3>

                        <p id="locationText">
                            Belum mengambil lokasi.
                        </p>

                        <br>

                        <button
                            class="btn-primary"
                            onclick="getLocation()">

                            Cek Lokasi

                        </button>

                    </div>


                    <div class="action-card">

                        <h3>
                            📸 Selfie
                        </h3>

                        <p>
                            Ambil foto sebagai
                            bukti absensi.
                        </p>

                        <br>

                        <button
                            class="btn-primary"
                            onclick="startCamera()">

                            Buka Kamera

                        </button>

                    </div>

                </div>


                <div
                    class="camera-box hidden"
                    id="cameraBox">

                    <video
                        id="video"
                        autoplay>
                    </video>

                    <br><br>

                    <button
                        class="btn-success"
                        onclick="takeSelfie()">

                        📸 Ambil Selfie

                    </button>

                    <canvas
                        id="canvas"
                        class="hidden">
                    </canvas>

                    <p id="selfieStatus"></p>

                </div>


                <br>

                <button
                    class="btn-success"
                    onclick="checkIn()">

                    🟢 Check In

                </button>


                <button
                    class="btn-warning"
                    onclick="checkOut()">

                    🔴 Check Out

                </button>

            </div>

        </section>


        <!-- ==================================================
             IZIN
        ================================================== -->

        <section id="izin"
                 class="page hidden">

            <div class="section">

                <h2>
                    Pengajuan Izin / Cuti
                </h2>

                <label>
                    Jenis Pengajuan
                </label>

                <select id="izinJenis">

                    <option value="Izin">
                        Izin
                    </option>

                    <option value="Cuti">
                        Cuti
                    </option>

                    <option value="Sakit">
                        Sakit
                    </option>

                </select>


                <label>
                    Tanggal
                </label>

                <input
                    type="date"
                    id="izinTanggal"
                >


                <label>
                    Keterangan
                </label>

                <textarea
                    id="izinKeterangan"
                    placeholder="Masukkan alasan...">
                </textarea>


                <label>
                    Upload Bukti
                </label>

                <input
                    type="file"
                    id="izinBukti"
                >


                <button
                    class="btn-primary"
                    onclick="submitIzin()">

                    Kirim Pengajuan

                </button>

            </div>


            <div class="section">

                <h2>
                    Pengajuan Saya
                </h2>

                <div style="overflow-x:auto">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Jenis
                                </th>

                                <th>
                                    Keterangan
                                </th>

                                <th>
                                    Bukti
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody id="myIzinTable">
                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- ==================================================
             RIWAYAT
        ================================================== -->

        <section id="riwayat"
                 class="page hidden">

            <div class="section">

                <h2>
                    Riwayat Absensi
                </h2>

                <div style="overflow-x:auto">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    NIP
                                </th>

                                <th>
                                    Nama
                                </th>

                                <th>
                                    Jabatan
                                </th>

                                <th>
                                    Check In
                                </th>

                                <th>
                                    Check Out
                                </th>

                                <th>
                                    GPS
                                </th>

                                <th>
                                    Selfie
                                </th>

                            </tr>

                        </thead>

                        <tbody id="historyTable">
                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- ==================================================
             DATA KARYAWAN
        ================================================== -->

        <section id="karyawan"
                 class="page hidden">

            <div class="section">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    margin-bottom:20px;
                ">

                    <h2>
                        Data Karyawan
                    </h2>

                    <button
                        class="btn-primary"
                        onclick="openEmployeeModal()">

                        + Tambah Karyawan

                    </button>

                </div>


                <div style="overflow-x:auto">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    No
                                </th>

                                <th>
                                    NIP
                                </th>

                                <th>
                                    Nama
                                </th>

                                <th>
                                    Jabatan
                                </th>

                                <th>
                                    Username
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody id="employeeTable">
                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- ==================================================
             APPROVAL
        ================================================== -->

        <section id="approval"
                 class="page hidden">

            <div class="section">

                <h2>
                    Persetujuan Izin / Cuti
                </h2>

                <div style="overflow-x:auto">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    NIP
                                </th>

                                <th>
                                    Nama
                                </th>

                                <th>
                                    Jenis
                                </th>

                                <th>
                                    Keterangan
                                </th>

                                <th>
                                    Bukti
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody id="approvalTable">
                        </tbody>

                    </table>

                </div>

            </div>

        </section>


        <!-- ==================================================
             LAPORAN
        ================================================== -->

        <section id="laporan"
                 class="page hidden">

            <div class="section">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                ">

                    <h2>
                        Rekap Laporan Absensi
                    </h2>

                    <button
                        class="btn-success"
                        onclick="exportCSV()">

                        ⬇ Export CSV

                    </button>

                </div>

                <br>

                <div style="overflow-x:auto">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    NIP
                                </th>

                                <th>
                                    Nama
                                </th>

                                <th>
                                    Jabatan
                                </th>

                                <th>
                                    Check In
                                </th>

                                <th>
                                    Check Out
                                </th>

                                <th>
                                    GPS
                                </th>

                                <th>
                                    Selfie
                                </th>

                            </tr>

                        </thead>

                        <tbody id="reportTable">
                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>

</div>


<!-- ==================================================
     MODAL KARYAWAN
================================================== -->

<div class="modal"
     id="employeeModal">

    <div class="modal-box">

        <h2 id="employeeModalTitle">
            Tambah Karyawan
        </h2>

        <input
            type="hidden"
            id="editEmployeeIndex"
        >


        <label>
            NIP
        </label>

        <input
            type="text"
            id="empNip"
            placeholder="Contoh: EMP005"
        >


        <label>
            Nama Lengkap
        </label>

        <input
            type="text"
            id="empNama"
            placeholder="Nama karyawan"
        >


        <label>
            Jabatan
        </label>

        <input
            type="text"
            id="empJabatan"
            placeholder="Contoh: Staff IT"
        >


        <label>
            Username
        </label>

        <input
            type="text"
            id="empUsername"
            placeholder="Username login"
        >


        <label>
            Password
        </label>

        <input
            type="text"
            id="empPassword"
            placeholder="Password login"
        >


        <div class="modal-actions">

            <button
                class="btn-secondary"
                onclick="closeEmployeeModal()">

                Batal

            </button>

            <button
                class="btn-primary"
                onclick="saveEmployee()">

                Simpan

            </button>

        </div>

    </div>

</div>


<script>

/* ==================================================
   DATA DEFAULT
================================================== */

const DEFAULT_EMPLOYEES = [

    {
        nip:"EMP001",
        nama:"Rizky Ramadhan",
        jabatan:"Staff IT",
        username:"rizky",
        password:"12345"
    },

    {
        nip:"EMP002",
        nama:"Andi Setiawan",
        jabatan:"Staff Keuangan",
        username:"andi",
        password:"12345"
    },

    {
        nip:"EMP003",
        nama:"Budi Santoso",
        jabatan:"Staff Administrasi",
        username:"budi",
        password:"12345"
    },

    {
        nip:"EMP004",
        nama:"Siti Aminah",
        jabatan:"Staff HR",
        username:"siti",
        password:"12345"
    }

];


/* ==================================================
   DATABASE
================================================== */

if(!localStorage.getItem("employees")){

    localStorage.setItem(
        "employees",
        JSON.stringify(DEFAULT_EMPLOYEES)
    );

}

if(!localStorage.getItem("attendance")){

    localStorage.setItem(
        "attendance",
        "[]"
    );

}

if(!localStorage.getItem("izin")){

    localStorage.setItem(
        "izin",
        "[]"
    );

}


/* ==================================================
   USER
================================================== */

let currentUser = null;

let currentRole = null;


/* ==================================================
   GPS
================================================== */

let currentLat = null;

let currentLng = null;

let currentDistance = null;


/* ==================================================
   SELFIE
================================================== */

let selfieTaken = false;

let stream = null;


/* ==================================================
   KOORDINAT KANTOR
================================================== */

const OFFICE_LAT = -6.2088;

const OFFICE_LNG = 106.8456;


/*
    Radius:
    200 KM = 200.000 meter
*/

const MAX_DISTANCE = 200000;


/* ==================================================
   WAKTU JAKARTA
================================================== */

/*
    Semua waktu sistem menggunakan:

    Asia/Jakarta
    WIB
    UTC +7

    Jadi tidak mengikuti timezone
    komputer pengguna.
*/


function getJakartaDate(){

    return new Date().toLocaleDateString(
        "en-CA",
        {
            timeZone:"Asia/Jakarta"
        }
    );

}


function getJakartaTime(){

    return new Date().toLocaleTimeString(
        "id-ID",
        {
            timeZone:"Asia/Jakarta",
            hour:"2-digit",
            minute:"2-digit",
            second:"2-digit",
            hour12:false
        }
    );

}


function getJakartaDateTime(){

    return new Date().toLocaleString(
        "id-ID",
        {
            timeZone:"Asia/Jakarta",
            weekday:"long",
            year:"numeric",
            month:"long",
            day:"numeric",
            hour:"2-digit",
            minute:"2-digit",
            second:"2-digit",
            hour12:false
        }
    );

}


/* ==================================================
   JAM JAKARTA BERJALAN
================================================== */

function updateJakartaClock(){

    document.getElementById(
        "jakartaClock"
    ).textContent = getJakartaTime();


    document.getElementById(
        "currentDate"
    ).textContent = new Date()
        .toLocaleDateString(
            "id-ID",
            {
                timeZone:"Asia/Jakarta",
                weekday:"long",
                year:"numeric",
                month:"long",
                day:"numeric"
            }
        );

}


updateJakartaClock();

setInterval(
    updateJakartaClock,
    1000
);


/* ==================================================
   LOGIN
================================================== */

function login(){

    const username =
        document.getElementById(
            "loginUsername"
        ).value.trim();


    const password =
        document.getElementById(
            "loginPassword"
        ).value;


    const alertBox =
        document.getElementById(
            "loginAlert"
        );


    /* ADMIN */

    if(
        username === "admin" &&
        password === "admin"
    ){

        currentUser = {

            username:"admin",

            nama:"Administrator HR",

            role:"admin"

        };


        currentRole = "admin";


        openApp();

        return;

    }


    /* KARYAWAN */

    const employees =
        JSON.parse(
            localStorage.getItem(
                "employees"
            )
        ) || [];


    const employee =
        employees.find(
            e =>
            e.username === username &&
            e.password === password
        );


    if(employee){

        currentUser = {

            ...employee,

            role:"karyawan"

        };


        currentRole =
            "karyawan";


        openApp();

    }

    else{

        alertBox.innerHTML = `

            <div style="
                background:#fee2e2;
                color:#991b1b;
                padding:10px;
                border-radius:8px;
                margin-bottom:15px;
            ">

                Username atau password salah!

            </div>

        `;

    }

}


/* ==================================================
   OPEN APP
================================================== */

function openApp(){

    document.getElementById(
        "loginPage"
    ).style.display = "none";


    document.getElementById(
        "app"
    ).style.display = "block";


    document.getElementById(
        "topUserName"
    ).textContent =
        currentUser.nama;


    document.getElementById(
        "topUserRole"
    ).textContent =

        currentRole === "admin"

        ? "Admin / HR"

        : currentUser.jabatan;


    if(currentRole === "admin"){

        document.getElementById(
            "menuAbsensi"
        ).classList.add("hidden");


        document.getElementById(
            "menuIzin"
        ).classList.add("hidden");


        document.getElementById(
            "menuKaryawan"
        ).classList.remove("hidden");


        document.getElementById(
            "menuApproval"
        ).classList.remove("hidden");


        document.getElementById(
            "menuLaporan"
        ).classList.remove("hidden");

    }

    else{

        document.getElementById(
            "menuAbsensi"
        ).classList.remove("hidden");


        document.getElementById(
            "menuIzin"
        ).classList.remove("hidden");


        document.getElementById(
            "menuKaryawan"
        ).classList.add("hidden");


        document.getElementById(
            "menuApproval"
        ).classList.add("hidden");


        document.getElementById(
            "menuLaporan"
        ).classList.add("hidden");


        showEmployeeInfo();

    }


    updateDashboard();

    showPage("dashboard");

}


/* ==================================================
   LOGOUT
================================================== */

function logout(){

    stopCamera();


    currentUser = null;

    currentRole = null;


    document.getElementById(
        "app"
    ).style.display = "none";


    document.getElementById(
        "loginPage"
    ).style.display = "flex";


    document.getElementById(
        "loginUsername"
    ).value = "";


    document.getElementById(
        "loginPassword"
    ).value = "";

}


/* ==================================================
   PAGE
================================================== */

function showPage(page){

    document.querySelectorAll(
        ".page"
    ).forEach(
        p =>
        p.classList.add("hidden")
    );


    document.getElementById(
        page
    ).classList.remove("hidden");


    const titles = {

        dashboard:"Dashboard",

        absensi:"Absensi Karyawan",

        izin:"Izin / Cuti",

        riwayat:"Riwayat Absensi",

        karyawan:"Data Karyawan",

        approval:"Persetujuan",

        laporan:"Laporan Absensi"

    };


    document.getElementById(
        "pageTitle"
    ).textContent =
        titles[page];


    if(page === "riwayat")
        loadHistory();


    if(page === "karyawan")
        loadEmployees();


    if(page === "approval")
        loadApproval();


    if(page === "laporan")
        loadReport();


    if(page === "izin")
        loadMyIzin();

}


/* ==================================================
   DASHBOARD
================================================== */

function updateDashboard(){

    const employees =
        JSON.parse(
            localStorage.getItem(
                "employees"
            )
        ) || [];


    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    document.getElementById(
        "totalKaryawan"
    ).textContent =
        employees.length;


    document.getElementById(
        "totalAbsensi"
    ).textContent =
        attendance.length;


    const today =
        getJakartaDate();


    const hadir =
        attendance.filter(
            a =>
            a.date === today
        );


    document.getElementById(
        "hadirHariIni"
    ).textContent =
        hadir.length;


    document.getElementById(
        "totalIzin"
    ).textContent =
        izin.length;

}


/* ==================================================
   INFO KARYAWAN
================================================== */

function showEmployeeInfo(){

    if(currentRole !== "karyawan")
        return;


    const html = `

        <div class="employee-box">

            <h3>
                👤 Data Karyawan
            </h3>

            <br>

            <p>
                <b>NIP:</b>
                ${currentUser.nip}
            </p>

            <p>
                <b>Nama:</b>
                ${currentUser.nama}
            </p>

            <p>
                <b>Jabatan:</b>
                ${currentUser.jabatan}
            </p>

        </div>

    `;


    document.getElementById(
        "employeeInfo"
    ).innerHTML = html;


    document.getElementById(
        "absensiEmployeeInfo"
    ).innerHTML = html;

}


/* ==================================================
   GPS
================================================== */

function getLocation(){

    if(!navigator.geolocation){

        alert(
            "Browser tidak mendukung GPS."
        );

        return;

    }


    document.getElementById(
        "gpsStatus"
    ).textContent =
        "Sedang mengambil lokasi GPS...";


    navigator.geolocation.getCurrentPosition(

        position => {

            currentLat =
                position.coords.latitude;


            currentLng =
                position.coords.longitude;


            currentDistance =
                calculateDistance(

                    currentLat,

                    currentLng,

                    OFFICE_LAT,

                    OFFICE_LNG

                );


            document.getElementById(
                "locationText"
            ).innerHTML = `

                Latitude:
                ${currentLat.toFixed(6)}

                <br>

                Longitude:
                ${currentLng.toFixed(6)}

                <br><br>

                Jarak dari kantor:
                <b>
                    ${Math.round(
                        currentDistance
                    )} meter
                </b>

            `;


            if(
                currentDistance <=
                MAX_DISTANCE
            ){

                document.getElementById(
                    "gpsStatus"
                ).innerHTML = `

                    <span
                        class="badge badge-green">

                        ✓ Berada dalam
                        area absensi

                    </span>

                `;

            }

            else{

                document.getElementById(
                    "gpsStatus"
                ).innerHTML = `

                    <span
                        class="badge badge-red">

                        ✕ Di luar area absensi

                    </span>

                `;

            }

        },


        error => {

            document.getElementById(
                "gpsStatus"
            ).innerHTML = `

                <span
                    class="badge badge-red">

                    GPS gagal diambil.
                    Pastikan izin lokasi
                    sudah diberikan.

                </span>

            `;

        },


        {

            enableHighAccuracy:true,

            timeout:10000,

            maximumAge:0

        }

    );

}


/* ==================================================
   HITUNG JARAK
================================================== */

function calculateDistance(
    lat1,
    lon1,
    lat2,
    lon2
){

    const R = 6371000;


    const dLat =
        (lat2-lat1)
        * Math.PI / 180;


    const dLon =
        (lon2-lon1)
        * Math.PI / 180;


    const a =

        Math.sin(dLat/2)
        * Math.sin(dLat/2)

        +

        Math.cos(
            lat1*Math.PI/180
        )

        *

        Math.cos(
            lat2*Math.PI/180
        )

        *

        Math.sin(dLon/2)
        * Math.sin(dLon/2);


    const c =
        2 * Math.atan2(
            Math.sqrt(a),
            Math.sqrt(1-a)
        );


    return R*c;

}


/* ==================================================
   CAMERA
================================================== */

async function startCamera(){

    try{

        stream =
            await navigator
            .mediaDevices
            .getUserMedia({
                video:true
            });


        document.getElementById(
            "video"
        ).srcObject =
            stream;


        document.getElementById(
            "cameraBox"
        ).classList.remove(
            "hidden"
        );

    }

    catch(error){

        alert(
            "Kamera tidak dapat digunakan. " +
            "Pastikan izin kamera diberikan."
        );

    }

}


function takeSelfie(){

    const video =
        document.getElementById(
            "video"
        );


    const canvas =
        document.getElementById(
            "canvas"
        );


    canvas.width =
        video.videoWidth;


    canvas.height =
        video.videoHeight;


    const context =
        canvas.getContext(
            "2d"
        );


    context.drawImage(
        video,
        0,
        0,
        canvas.width,
        canvas.height
    );


    selfieTaken = true;


    document.getElementById(
        "selfieStatus"
    ).innerHTML = `

        <span
            class="badge badge-green">

            ✓ Selfie berhasil diambil

        </span>

    `;

}


function stopCamera(){

    if(stream){

        stream
        .getTracks()
        .forEach(
            track =>
            track.stop()
        );


        stream = null;

    }

}


/* ==================================================
   CHECK IN
================================================== */

function checkIn(){

    if(currentRole !== "karyawan")
        return;


    if(currentDistance === null){

        alert(
            "Silakan cek lokasi GPS terlebih dahulu."
        );

        return;

    }


    if(
        currentDistance >
        MAX_DISTANCE
    ){

        alert(

            "Anda berada di luar area kantor.\n" +

            "Jarak: " +

            Math.round(
                currentDistance
            ) +

            " meter."

        );

        return;

    }


    if(!selfieTaken){

        alert(
            "Silakan ambil selfie terlebih dahulu."
        );

        return;

    }


    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    /*
       PENTING:

       Tanggal menggunakan Jakarta.
    */

    const today =
        getJakartaDate();


    let record =
        attendance.find(

            a =>

            a.username ===
            currentUser.username &&

            a.date === today

        );


    if(
        record &&
        record.checkIn
    ){

        alert(
            "Anda sudah melakukan Check In hari ini."
        );

        return;

    }


    if(!record){

        record = {

            username:
                currentUser.username,

            nip:
                currentUser.nip,

            nama:
                currentUser.nama,

            jabatan:
                currentUser.jabatan,

            date:
                today,

            checkIn:
                getJakartaTime(),

            checkOut:"",

            latitude:
                currentLat,

            longitude:
                currentLng,

            distance:
                Math.round(
                    currentDistance
                ),

            selfie:"Ada"

        };


        attendance.push(
            record
        );

    }

    else{

        record.checkIn =
            getJakartaTime();

    }


    localStorage.setItem(
        "attendance",
        JSON.stringify(
            attendance
        )
    );


    alert(
        "Check In berhasil!\n\n" +
        "Waktu: " +
        getJakartaTime() +
        " WIB"
    );


    updateDashboard();

    loadHistory();

}


/* ==================================================
   CHECK OUT
================================================== */

function checkOut(){

    if(currentRole !== "karyawan")
        return;


    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    const today =
        getJakartaDate();


    const record =
        attendance.find(

            a =>

            a.username ===
            currentUser.username &&

            a.date === today

        );


    if(!record){

        alert(
            "Anda belum melakukan Check In."
        );

        return;

    }


    if(record.checkOut){

        alert(
            "Anda sudah melakukan Check Out."
        );

        return;

    }


    record.checkOut =
        getJakartaTime();


    localStorage.setItem(
        "attendance",
        JSON.stringify(
            attendance
        )
    );


    alert(
        "Check Out berhasil!\n\n" +
        "Waktu: " +
        getJakartaTime() +
        " WIB"
    );


    updateDashboard();

    loadHistory();

}


/* ==================================================
   RIWAYAT
================================================== */

function loadHistory(){

    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    let data =
        attendance;


    if(
        currentRole ===
        "karyawan"
    ){

        data =
            attendance.filter(

                a =>
                a.username ===
                currentUser.username

            );

    }


    const tbody =
        document.getElementById(
            "historyTable"
        );


    tbody.innerHTML = "";


    data
    .slice()
    .reverse()
    .forEach(
        a => {

            tbody.innerHTML += `

                <tr>

                    <td>
                        ${a.date}
                    </td>

                    <td>
                        ${a.nip}
                    </td>

                    <td>
                        ${a.nama}
                    </td>

                    <td>
                        ${a.jabatan}
                    </td>

                    <td>
                        ${a.checkIn || "-"}
                    </td>

                    <td>
                        ${a.checkOut || "-"}
                    </td>

                    <td>
                        ${a.distance || 0}
                        meter
                    </td>

                    <td>

                        <span
                            class="badge badge-green">

                            ✓ ${a.selfie}

                        </span>

                    </td>

                </tr>

            `;

        }
    );

}


/* ==================================================
   MODAL KARYAWAN
================================================== */

function openEmployeeModal(
    index = null
){

    document.getElementById(
        "employeeModal"
    ).style.display =
        "flex";


    if(index === null){

        document.getElementById(
            "employeeModalTitle"
        ).textContent =
            "Tambah Karyawan";


        document.getElementById(
            "editEmployeeIndex"
        ).value = "";


        document.getElementById(
            "empNip"
        ).value = "";


        document.getElementById(
            "empNama"
        ).value = "";


        document.getElementById(
            "empJabatan"
        ).value = "";


        document.getElementById(
            "empUsername"
        ).value = "";


        document.getElementById(
            "empPassword"
        ).value = "";

    }

    else{

        const employees =
            JSON.parse(
                localStorage.getItem(
                    "employees"
                )
            ) || [];


        const employee =
            employees[index];


        document.getElementById(
            "employeeModalTitle"
        ).textContent =
            "Edit Karyawan";


        document.getElementById(
            "editEmployeeIndex"
        ).value =
            index;


        document.getElementById(
            "empNip"
        ).value =
            employee.nip;


        document.getElementById(
            "empNama"
        ).value =
            employee.nama;


        document.getElementById(
            "empJabatan"
        ).value =
            employee.jabatan;


        document.getElementById(
            "empUsername"
        ).value =
            employee.username;


        document.getElementById(
            "empPassword"
        ).value =
            employee.password;

    }

}


function closeEmployeeModal(){

    document.getElementById(
        "employeeModal"
    ).style.display =
        "none";

}


/* ==================================================
   SIMPAN KARYAWAN
================================================== */

function saveEmployee(){

    const nip =
        document.getElementById(
            "empNip"
        ).value.trim();


    const nama =
        document.getElementById(
            "empNama"
        ).value.trim();


    const jabatan =
        document.getElementById(
            "empJabatan"
        ).value.trim();


    const username =
        document.getElementById(
            "empUsername"
        ).value.trim();


    const password =
        document.getElementById(
            "empPassword"
        ).value;


    if(
        !nip ||
        !nama ||
        !jabatan ||
        !username ||
        !password
    ){

        alert(
            "Semua data harus diisi."
        );

        return;

    }


    const employees =
        JSON.parse(
            localStorage.getItem(
                "employees"
            )
        ) || [];


    const editIndex =
        document.getElementById(
            "editEmployeeIndex"
        ).value;


    const duplicate =
        employees.some(

            (e,index) =>

            e.username === username &&

            String(index) !==
            String(editIndex)

        );


    if(duplicate){

        alert(
            "Username sudah digunakan."
        );

        return;

    }


    const employee = {

        nip,

        nama,

        jabatan,

        username,

        password

    };


    if(editIndex === ""){

        employees.push(
            employee
        );


        alert(
            "Karyawan berhasil ditambahkan."
        );

    }

    else{

        employees[
            Number(editIndex)
        ] = employee;


        alert(
            "Data karyawan berhasil diperbarui."
        );

    }


    localStorage.setItem(
        "employees",
        JSON.stringify(
            employees
        )
    );


    closeEmployeeModal();

    loadEmployees();

    updateDashboard();

}


/* ==================================================
   DATA KARYAWAN
================================================== */

function loadEmployees(){

    const employees =
        JSON.parse(
            localStorage.getItem(
                "employees"
            )
        ) || [];


    const tbody =
        document.getElementById(
            "employeeTable"
        );


    tbody.innerHTML = "";


    employees.forEach(
        (e,index) => {

            tbody.innerHTML += `

                <tr>

                    <td>
                        ${index+1}
                    </td>

                    <td>
                        ${e.nip}
                    </td>

                    <td>
                        ${e.nama}
                    </td>

                    <td>
                        ${e.jabatan}
                    </td>

                    <td>
                        ${e.username}
                    </td>

                    <td>

                        <button
                            class="btn-warning"
                            onclick="openEmployeeModal(${index})">

                            Edit

                        </button>

                        <button
                            class="btn-danger"
                            onclick="deleteEmployee(${index})">

                            Hapus

                        </button>

                    </td>

                </tr>

            `;

        }
    );

}


/* ==================================================
   HAPUS KARYAWAN
================================================== */

function deleteEmployee(index){

    const employees =
        JSON.parse(
            localStorage.getItem(
                "employees"
            )
        ) || [];


    const employee =
        employees[index];


    const confirmDelete =
        confirm(
            `Hapus karyawan ${employee.nama}?`
        );


    if(!confirmDelete)
        return;


    employees.splice(
        index,
        1
    );


    localStorage.setItem(
        "employees",
        JSON.stringify(
            employees
        )
    );


    loadEmployees();

    updateDashboard();


    alert(
        "Karyawan berhasil dihapus."
    );

}


/* ==================================================
   IZIN
================================================== */

function submitIzin(){

    if(currentRole !== "karyawan")
        return;


    const jenis =
        document.getElementById(
            "izinJenis"
        ).value;


    const tanggal =
        document.getElementById(
            "izinTanggal"
        ).value;


    const keterangan =
        document.getElementById(
            "izinKeterangan"
        ).value.trim();


    const file =
        document.getElementById(
            "izinBukti"
        ).files[0];


    if(
        !tanggal ||
        !keterangan
    ){

        alert(
            "Tanggal dan keterangan harus diisi."
        );

        return;

    }


    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    izin.push({

        id:Date.now(),

        username:
            currentUser.username,

        nip:
            currentUser.nip,

        nama:
            currentUser.nama,

        jenis,

        tanggal,

        keterangan,

        bukti:
            file
            ? file.name
            : "-",

        status:
            "Menunggu",

        dibuatPada:
            getJakartaDateTime()

    });


    localStorage.setItem(
        "izin",
        JSON.stringify(
            izin
        )
    );


    alert(
        "Pengajuan berhasil dikirim."
    );


    document.getElementById(
        "izinTanggal"
    ).value = "";


    document.getElementById(
        "izinKeterangan"
    ).value = "";


    document.getElementById(
        "izinBukti"
    ).value = "";


    loadMyIzin();

    updateDashboard();

}


/* ==================================================
   IZIN SAYA
================================================== */

function loadMyIzin(){

    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    const data =
        izin.filter(

            i =>
            i.username ===
            currentUser.username

        );


    const tbody =
        document.getElementById(
            "myIzinTable"
        );


    tbody.innerHTML = "";


    data
    .slice()
    .reverse()
    .forEach(
        i => {

            let badge =
                "badge-yellow";


            if(
                i.status ===
                "Disetujui"
            )

                badge =
                    "badge-green";


            if(
                i.status ===
                "Ditolak"
            )

                badge =
                    "badge-red";


            tbody.innerHTML += `

                <tr>

                    <td>
                        ${i.tanggal}
                    </td>

                    <td>
                        ${i.jenis}
                    </td>

                    <td>
                        ${i.keterangan}
                    </td>

                    <td>
                        ${i.bukti}
                    </td>

                    <td>

                        <span
                            class="badge ${badge}">

                            ${i.status}

                        </span>

                    </td>

                </tr>

            `;

        }
    );

}


/* ==================================================
   APPROVAL
================================================== */

function loadApproval(){

    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    const tbody =
        document.getElementById(
            "approvalTable"
        );


    tbody.innerHTML = "";


    izin
    .slice()
    .reverse()
    .forEach(
        i => {

            let badge =
                "badge-yellow";


            if(
                i.status ===
                "Disetujui"
            )

                badge =
                    "badge-green";


            if(
                i.status ===
                "Ditolak"
            )

                badge =
                    "badge-red";


            const realIndex =
                izin.findIndex(
                    x =>
                    x.id === i.id
                );


            tbody.innerHTML += `

                <tr>

                    <td>
                        ${i.tanggal}
                    </td>

                    <td>
                        ${i.nip}
                    </td>

                    <td>
                        ${i.nama}
                    </td>

                    <td>
                        ${i.jenis}
                    </td>

                    <td>
                        ${i.keterangan}
                    </td>

                    <td>
                        ${i.bukti}
                    </td>

                    <td>

                        <span
                            class="badge ${badge}">

                            ${i.status}

                        </span>

                    </td>

                    <td>

                        ${
                            i.status === "Menunggu"

                            ?

                            `

                            <button
                                class="btn-success"
                                onclick="approveIzin(${realIndex})">

                                Setujui

                            </button>

                            <button
                                class="btn-danger"
                                onclick="rejectIzin(${realIndex})">

                                Tolak

                            </button>

                            `

                            :

                            "-"

                        }

                    </td>

                </tr>

            `;

        }
    );

}


/* ==================================================
   APPROVE
================================================== */

function approveIzin(index){

    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    izin[index].status =
        "Disetujui";


    izin[index].diprosesPada =
        getJakartaDateTime();


    localStorage.setItem(
        "izin",
        JSON.stringify(
            izin
        )
    );


    loadApproval();

    updateDashboard();

}


/* ==================================================
   REJECT
================================================== */

function rejectIzin(index){

    const izin =
        JSON.parse(
            localStorage.getItem(
                "izin"
            )
        ) || [];


    izin[index].status =
        "Ditolak";


    izin[index].diprosesPada =
        getJakartaDateTime();


    localStorage.setItem(
        "izin",
        JSON.stringify(
            izin
        )
    );


    loadApproval();

    updateDashboard();

}


/* ==================================================
   LAPORAN
================================================== */

function loadReport(){

    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    const tbody =
        document.getElementById(
            "reportTable"
        );


    tbody.innerHTML = "";


    attendance
    .slice()
    .reverse()
    .forEach(
        a => {

            tbody.innerHTML += `

                <tr>

                    <td>
                        ${a.date}
                    </td>

                    <td>
                        ${a.nip}
                    </td>

                    <td>
                        ${a.nama}
                    </td>

                    <td>
                        ${a.jabatan}
                    </td>

                    <td>
                        ${a.checkIn || "-"}
                    </td>

                    <td>
                        ${a.checkOut || "-"}
                    </td>

                    <td>
                        ${a.distance || 0}
                        meter
                    </td>

                    <td>
                        ${a.selfie || "-"}
                    </td>

                </tr>

            `;

        }
    );

}


/* ==================================================
   EXPORT CSV
================================================== */

function exportCSV(){

    const attendance =
        JSON.parse(
            localStorage.getItem(
                "attendance"
            )
        ) || [];


    if(
        attendance.length === 0
    ){

        alert(
            "Belum ada data absensi."
        );

        return;

    }


    let csv =
        "Tanggal,NIP,Nama,Jabatan,Check In,Check Out,Jarak GPS,Selfie\n";


    attendance.forEach(
        a => {

            csv +=

                `"${a.date}",` +

                `"${a.nip}",` +

                `"${a.nama}",` +

                `"${a.jabatan}",` +

                `"${a.checkIn || ""}",` +

                `"${a.checkOut || ""}",` +

                `"${a.distance || ""} meter",` +

                `"${a.selfie || ""}"\n`;

        }
    );


    const blob =
        new Blob(

            [csv],

            {
                type:
                "text/csv;charset=utf-8;"
            }

        );


    const url =
        URL.createObjectURL(
            blob
        );


    const link =
        document.createElement(
            "a"
        );


    link.href = url;

    link.download =
        "laporan-absensi.csv";


    link.click();


    URL.revokeObjectURL(
        url
    );

}


/* ==================================================
   INIT
================================================== */

updateDashboard();

</script>

</body>
</html>

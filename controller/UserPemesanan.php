<?php
include 'koneksi.php';
session_start();

// Redirect jika pengguna belum login
if (!isset($_SESSION['user'])) {
    $_SESSION['pesan'] = [
        'title' => 'Login Diperlukan',
        'text' => 'Silakan login untuk membuat pemesanan.',
        'icon' => 'warning'
    ];
    header("Location: login.php");
    exit();
}

// Ambil informasi pengguna dari session
$user_id = $_SESSION['id_user'];
$user_name = $_SESSION['user'];

// Cek apakah `id_lapangan` ada di URL
if (!isset($_GET['id_lapangan'])) {
    $_SESSION['pesan'] = [
        'title' => 'Error!',
        'text' => 'Lapangan ID tidak ditemukan. Silakan pilih lapangan terlebih dahulu.',
        'icon' => 'error'
    ];
    header("Location: index.php");
    exit();
}

$id_lapangan = mysqli_real_escape_string($conn, $_GET['id_lapangan']);
$query = "SELECT * FROM tb_lapangan WHERE id_lapangan = '$id_lapangan'";
$result = mysqli_query($conn, $query);

// Cek apakah lapangan ada di database
if (mysqli_num_rows($result) === 0) {
    $_SESSION['pesan'] = [
        'title' => 'Error!',
        'text' => 'Lapangan ID tidak valid.',
        'icon' => 'error'
    ];
    header("Location: index.php");
    exit();
}

$lapangan = mysqli_fetch_assoc($result);

// Proses saat form disubmit
if (isset($_POST['pesanan'])) {
    $tanggal_pesan = $_POST['tanggal_pesanan'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Query untuk cek apakah waktu sudah dipesan
    $cek_pemesanan = "SELECT * FROM tb_pesanan 
                      WHERE id_lapangan = '$id_lapangan' 
                      AND tanggal_pesanan = '$tanggal_pesan' 
                      AND status = 'Belum Main' 
                      AND (jam_mulai < '$jam_selesai' AND jam_selesai > '$jam_mulai')";

    $result_cek = mysqli_query($conn, $cek_pemesanan);

    if (mysqli_num_rows($result_cek) > 0) {
        // Jika ada pemesanan yang bentrok
        $_SESSION['pesan'] = [
            'title' => 'Waktu Tidak Tersedia!',
            'text' => 'Pemesanan pada waktu tersebut sudah ada. Silakan pilih waktu lain.',
            'icon' => 'error'
        ];
    } else {
        // Hitung total biaya
        $durasi = strtotime($jam_selesai) - strtotime($jam_mulai);
        $durasi_jam = $durasi / 3600;
        $total_biaya = $durasi_jam * $lapangan['harga_per_jam'];
        $status = "Belum Main";

        // Query untuk menyimpan pemesanan
        $sql = "INSERT INTO tb_pesanan (id_user, id_lapangan, tanggal_pesanan, jam_mulai, jam_selesai, total_biaya, status)
                VALUES ('$user_id', '$id_lapangan', '$tanggal_pesan', '$jam_mulai', '$jam_selesai', '$total_biaya', '$status')";

        if (mysqli_query($conn, $sql)) {
            $id_pesanan = mysqli_insert_id($conn);
            $_SESSION['pesan'] = [
                'title' => 'Pemesanan Berhasil!',
                'text' => 'Pemesanan Anda berhasil disimpan.',
                'icon' => 'success'
            ];
            $_SESSION['id_pesanan'] = $id_pesanan; // Simpan id_pesanan untuk redirect
        } else {
            $_SESSION['pesan'] = [
                'title' => 'Pemesanan Gagal!',
                'text' => 'Terjadi kesalahan dalam menyimpan pemesanan Anda.',
                'icon' => 'error'
            ];
            header("Location: index.php");
            exit();
        }
    }
}

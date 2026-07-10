<?php
session_start();
include 'koneksi.php';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $nama = urldecode($_GET['nama']);
    $email = urldecode($_GET['email']);
    $no_telp = urldecode($_GET['no_telp']);
    $password = urldecode($_GET['password']);

    // Cek apakah email sudah ada
    $check = $conn->query("SELECT * FROM tb_user WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $_SESSION['pesan'] = [
            'title' => 'Gagal!',
            'text' => 'Email ini sudah terdaftar sebelumnya.',
            'icon' => 'error'
        ];
        header("Location: ../index.php");
        exit();
    }

    // Simpan data user ke database
    $sql = "INSERT INTO tb_user (nama, email, no_telp, password) 
            VALUES ('$nama', '$email', '$no_telp', '$password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['pesan'] = [
            'title' => 'Verifikasi Berhasil!',
            'text' => 'Akun Anda telah aktif. Silakan login.',
            'icon' => 'success'
        ];
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Error!',
            'text' => 'Terjadi kesalahan saat menyimpan akun.',
            'icon' => 'error'
        ];
        header("Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['pesan'] = [
        'title' => 'Error!',
        'text' => 'Tautan verifikasi tidak valid.',
        'icon' => 'error'
    ];
    header("Location: ../index.php");
    exit();
}

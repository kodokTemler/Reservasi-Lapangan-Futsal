<?php
session_start();
include 'koneksi.php';

if (isset($_POST['daftarUser'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO tb_user (nama, email, no_telp, password) VALUES ('$nama', '$email', '$no_telp', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['pesan'] = [
            'title' => 'Berhasil!',
            'text' => 'Akun Anda telah berhasil dibuat.',
            'icon' => 'success'
        ];
        header("Location: index.php"); // Redirect ke halaman login
        exit();
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Error!',
            'text' => 'Terjadi kesalahan saat membuat akun.',
            'icon' => 'error'
        ];
        header("Location: index.php"); // Redirect ke halaman login
        exit();
    }
} elseif (isset($_POST['masukUser'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tb_user WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['user'] = $user['nama'];
            $_SESSION['pesan'] = [
                'title' => 'Login Berhasil!',
                'text' => 'Selamat datang, ' . $user['nama'] . '!',
                'icon' => 'success',
                'redirect' => 'user/index.php' // Menyimpan URL redirect
            ];
        } else {
            $_SESSION['pesan'] = [
                'title' => 'Login Gagal!',
                'text' => 'Email dan Password yang Anda masukkan mungkin salah.',
                'icon' => 'error'
            ];
        }
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Login Gagal!',
            'text' => 'Email dan Password yang Anda masukkan mungkin salah.',
            'icon' => 'error'
        ];
    }
    header("Location: index.php"); // Redirect ke halaman login agar pesan bisa muncul
    exit();
}

<?php
session_start();
include 'koneksi.php';

if (isset($_POST["login"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM tb_admin WHERE username = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

        if ($password === $row['password']) {
            $_SESSION['name'] = $row['username'];
            $_SESSION['pesan'] = [
                'title' => 'Berhasil!',
                'text' => 'Login berhasil!',
                'icon' => 'success',
                'redirect' => 'dashboard.php' // Redirect ke halaman dashboard
            ];
            header("Location: index.php"); // Redirect ke halaman login agar SweetAlert muncul
            exit();
        } else {
            $_SESSION['pesan'] = [
                'title' => 'Gagal!',
                'text' => 'Username atau password salah.',
                'icon' => 'error'
            ];
        }
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Gagal!',
            'text' => 'Username atau password salah.',
            'icon' => 'error'
        ];
    }
}

<?php
include 'koneksi.php';

if (isset($_POST['addUlasan'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $ulasan = $_POST['ulasan'];

    // Query untuk menyimpan ulasan
    $stmt = $conn->prepare("INSERT INTO tb_ulasan (nama, email, no_telp, ulasan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $no_telp, $ulasan);

    if ($stmt->execute()) {
        $_SESSION['pesan'] = [
            'title' => 'Success',
            'text' => 'Ulasan berhasil ditambahkan!',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['pesan'] = [
            'title' => 'error',
            'text' => 'Gagal menambahkan ulasan: ' . $stmt->error,
            'icon' => 'eror'
        ];
    }

    // Tutup conn
    $stmt->close();
    $conn->close();

    // Redirect kembali ke halaman form
    header('Location: index.php');
    exit;
}

if (isset($_POST['deleteDataUlasan'])) {
    $id_ulasan = $_POST['id_ulasan'];

    // Membuat query delete
    $hapusDataUlasanSQL = "DELETE FROM tb_ulasan WHERE id_ulasan = ?";

    // Menyiapkan statement
    if ($stmtHapusData = $conn->prepare($hapusDataUlasanSQL)) {
        // Mengikat parameter
        $stmtHapusData->bind_param("i", $id_ulasan);

        // Menjalankan query
        if ($stmtHapusData->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: ulasan.php?active=ulasan");
        } else {
            // Redirect ke halaman dashboard jika gagal
            echo "Error executing query: " . $stmtHapusData->error;
        }

        // Menutup statement
        $stmtHapusData->close();
    } else {
        // Menangani kesalahan dalam menyiapkan statement
        echo "Error preparing statement: " . $conn->error;
    }
}

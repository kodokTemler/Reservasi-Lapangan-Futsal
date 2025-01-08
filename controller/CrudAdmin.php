<?php
include 'koneksi.php';

// Tambah Admin
if (isset($_POST['tambahDataAdmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $tambahDataAdminSQL = "INSERT INTO tb_admin ( `username`, `password`) VALUES ('$username', '$password')";

    $InsertAdmin = mysqli_query($conn, $tambahDataAdminSQL);
    if ($InsertAdmin) {
        header("Location: admin.php?active=admin");
    } else {
        header("Location: admin.php");
    }
}

// Hapus Admin
if (isset($_POST['deleteDataAdmin'])) {
    $id_admin = $_POST['id_admin'];

    // Membuat query delete
    $hapusDataAdminSQL = "DELETE FROM tb_admin WHERE id_admin = ?";

    // Menyiapkan statement
    if ($stmtHapusData = $conn->prepare($hapusDataAdminSQL)) {
        // Mengikat parameter
        $stmtHapusData->bind_param("i", $id_admin);

        // Menjalankan query
        if ($stmtHapusData->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: admin.php");
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

// Edit Admin
if (isset($_POST['editDataAdmin'])) {
    $id_admin = $_POST['id_admin'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Membuat query update
    $editDataAdminSQL = "UPDATE tb_admin SET username = ?, password = ? WHERE id_admin = ?";

    // Menyiapkan statement
    if ($stmtEditData = $conn->prepare($editDataAdminSQL)) {
        // Mengikat parameter
        $stmtEditData->bind_param("ssi", $username, $password, $id_admin);

        // Menjalankan query
        if ($stmtEditData->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: admin.php?active=admin");
            exit(); // Pastikan script berhenti setelah redirect
        } else {
            // Menampilkan pesan error jika query gagal
            echo "Error executing query: " . $stmtEditData->error;
        }

        // Menutup statement
        $stmtEditData->close();
    } else {
        // Menampilkan pesan error jika statement gagal disiapkan
        echo "Error preparing statement: " . $conn->error;
    }
}

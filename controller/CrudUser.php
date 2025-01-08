<?php
include 'koneksi.php';

// Hapus User
if (isset($_POST['deleteDataUser'])) {
    $id_admin = $_POST['id_user'];

    // Membuat query untuk menghapus data terkait di tabel tb_pesanan
    $hapusPesananSQL = "DELETE FROM tb_pesanan WHERE id_user = ?";

    // Menyiapkan statement untuk menghapus data pesanan
    if ($stmtHapusPesanan = $conn->prepare($hapusPesananSQL)) {
        // Mengikat parameter
        $stmtHapusPesanan->bind_param("i", $id_admin);

        // Menjalankan query
        if ($stmtHapusPesanan->execute()) {
            // Setelah menghapus pesanan, hapus data di tb_user
            $hapusDataAdminSQL = "DELETE FROM tb_user WHERE id_user = ?";
            if ($stmtHapusData = $conn->prepare($hapusDataAdminSQL)) {
                // Mengikat parameter
                $stmtHapusData->bind_param("i", $id_admin);

                // Menjalankan query
                if ($stmtHapusData->execute()) {
                    // Redirect ke halaman dashboard jika berhasil
                    header("Location: user.php?active=user");
                } else {
                    echo "Error executing query: " . $stmtHapusData->error;
                }
                $stmtHapusData->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Error executing query: " . $stmtHapusPesanan->error;
        }
        $stmtHapusPesanan->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Edit Admin
if (isset($_POST['editDataUser'])) {
    $id_admin = $_POST['id_user'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];

    // Membuat query update
    $editDataUserSQL = "UPDATE tb_user SET nama = ?, email = ?, no_telp = ? WHERE id_user = ?";

    // Menyiapkan statement
    if ($stmtEditData = $conn->prepare($editDataUserSQL)) {
        // Mengikat parameter
        $stmtEditData->bind_param("sssi", $nama, $email, $no_telp, $id_admin);

        // Menjalankan query
        if ($stmtEditData->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: user.php?active=user");
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

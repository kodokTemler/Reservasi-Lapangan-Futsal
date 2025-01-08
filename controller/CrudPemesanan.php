<?php
include 'koneksi.php';

$queryLapangan = "SELECT id_lapangan, tipe_lapangan FROM tb_lapangan";
$resultLapangan = $conn->query($queryLapangan);

// Membuat array untuk menyimpan tipe lapangan
$lapanganOptions = [];
if ($resultLapangan->num_rows > 0) {
    while ($row = $resultLapangan->fetch_assoc()) {
        $lapanganOptions[] = $row;
    }
}


if (isset($_POST['statusPesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];

    $statusSQL = "UPDATE tb_pesanan SET status = ? WHERE id_pesanan = ?";
    if ($stmtEditData = $conn->prepare($statusSQL)) {
        // Mengikat parameter
        $stmtEditData->bind_param("si", $status, $id_pesanan);

        // Menjalankan query
        if ($stmtEditData->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: pemesanan.php?active=pemesanan");
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
} elseif (isset($_POST['hapusPesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];

    $deleteSQL = "DELETE FROM tb_pesanan WHERE id_pesanan = ?";
    if ($stmtDelete = $conn->prepare($deleteSQL)) {
        // Mengikat parameter
        $stmtDelete->bind_param("i", $id_pesanan);

        // Menjalankan query
        if ($stmtDelete->execute()) {
            // Redirect ke halaman dashboard jika berhasil
            header("Location: pemesanan.php?active=pemesanan");
            exit();
        } else {
            // Menampilkan pesan error jika query gagal
            echo "Error executing delete query: " . $stmtDelete->error;
        }
        // Menutup statement
        $stmtDelete->close();
    } else {
        // Menampilkan pesan error jika statement gagal disiapkan
        echo "Error preparing delete statement: " . $conn->error;
    }
} elseif (isset($_POST['editDataPesanan'])) {
    // Mendapatkan data dari form
    $id_pesanan = $_POST['id_pesanan'];
    $id_lapangan = $_POST['id_lapangan']; // Pastikan ini diambil dari input
    $tanggal_pesanan = $_POST['tanggal_pesanan'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Validasi untuk memastikan id_lapangan tidak kosong
    if (empty($id_lapangan)) {
        echo "<script>alert('ID lapangan tidak boleh kosong.'); window.history.back();</script>";
        exit();
    }

    // SQL untuk memperbarui data pemesanan
    $updatePesananSQL = "UPDATE tb_pesanan SET id_lapangan = ?, tanggal_pesanan = ?, jam_mulai = ?, jam_selesai = ? WHERE id_pesanan = ?";

    if ($stmtUpdatePesanan = $conn->prepare($updatePesananSQL)) {
        $stmtUpdatePesanan->bind_param("ssssi", $id_lapangan, $tanggal_pesanan, $jam_mulai, $jam_selesai, $id_pesanan);

        if ($stmtUpdatePesanan->execute()) {
            // Redirect ke halaman pemesanan jika berhasil
            header("Location: pemesanan.php?active=pemesanan");
            exit();
        } else {
            echo "Error executing update query: " . $stmtUpdatePesanan->error;
        }
        $stmtUpdatePesanan->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
}

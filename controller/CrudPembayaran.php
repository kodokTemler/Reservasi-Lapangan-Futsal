<?php
include 'koneksi.php';

// Cek jika form dikirim untuk mengupdate status pembayaran
if (isset($_POST['editDataPembayaran'])) {
    // Ambil data dari form
    $id_pesanan = $_POST['id_pesanan'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // SQL query untuk mengupdate status pembayaran
    $updateStatusSql = "UPDATE tb_pembayaran SET status_pembayaran = ? WHERE id_pesanan = ?";

    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $updateStatusSql);
    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, 'si', $status_pembayaran, $id_pesanan);

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, beri notifikasi atau arahkan kembali ke halaman yang sesuai
            echo "<script> Swal.fire('Success', 'Status Pembayaran Berhasil Diperbarui!', 'success'); </script>";
        } else {
            // Jika gagal, beri notifikasi error
            echo "<script> Swal.fire('Error', 'Terjadi kesalahan, coba lagi.', 'error'); </script>";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    }
} elseif (isset($_POST['hapusPembayaran'])) {
    // Ambil id_pesanan dari form
    $id_pesanan = $_POST['id_pesanan'];

    // SQL query untuk menghapus data pembayaran berdasarkan id_pesanan
    $deleteSql = "DELETE FROM tb_pembayaran WHERE id_pesanan = ?";

    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $deleteSql);
    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, 'i', $id_pesanan);

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, beri notifikasi atau arahkan kembali ke halaman yang sesuai
            echo "<script> Swal.fire('Success', 'Pembayaran berhasil dihapus!', 'success'); </script>";
        } else {
            // Jika gagal, beri notifikasi error
            echo "<script> Swal.fire('Error', 'Terjadi kesalahan saat menghapus pembayaran.', 'error'); </script>";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    }
}

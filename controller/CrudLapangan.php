<?php
include 'koneksi.php';

// Tambah Lapangan
if (isset($_POST['tambahDataLapangan'])) {
    // Ambil data dari form
    $nama_lapangan = $_POST['nama_lapangan'];
    $tipe_lapangan = $_POST['tipe_lapangan'];
    $lokasi = $_POST['lokasi'];
    $harga_per_jam = $_POST['harga_per_jam'];
    $status_lapangan = $_POST['status_lapangan'];

    // Handle upload gambar
    if (isset($_FILES['gambar_lapangan']) && $_FILES['gambar_lapangan']['error'] == 0) {
        $gambar_lapangan = $_FILES['gambar_lapangan'];
        $ext = pathinfo($gambar_lapangan['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $ext;
        $targetDir = 'assets/img/lapangan/';
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($gambar_lapangan['tmp_name'], $targetFile)) {
            $gambar_lapangan = $fileName;
        } else {
            die('Upload gambar gagal.');
        }
    } else {
        $gambar_lapangan = null; // Jika gambar tidak diupload
    }

    // Siapkan SQL untuk insert data
    $sql = "INSERT INTO tb_lapangan (nama_lapangan, tipe_lapangan, lokasi, harga_per_jam, status_lapangan, gambar_lapangan) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Koneksi ke database dan persiapan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssiss', $nama_lapangan, $tipe_lapangan, $lokasi, $harga_per_jam, $status_lapangan, $gambar_lapangan);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke lapangan.php setelah berhasil
        header('Location: lapangan.php?active=lapangan');
        exit();
    } else {
        echo "Gagal menambah data: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
}

// Edit Lapangan
if (isset($_POST['editDataLapangan'])) {
    // Ambil data dari form
    $id_lapangan = $_POST['id_lapangan']; // Pastikan id lapangan diambil dari form
    $nama_lapangan = $_POST['nama_lapangan'];
    $tipe_lapangan = $_POST['tipe_lapangan'];
    $lokasi = $_POST['lokasi'];
    $harga_per_jam = $_POST['harga_per_jam'];
    $status_lapangan = $_POST['status_lapangan'];

    // Inisialisasi gambar_lapangan
    $gambar_lapangan = null;

    // Cek apakah gambar diupload atau tidak
    if (isset($_FILES['gambar_lapangan']) && $_FILES['gambar_lapangan']['error'] == 0) {
        // Ambil gambar lama dari database jika ada
        $sql = "SELECT gambar_lapangan FROM tb_lapangan WHERE id_lapangan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_lapangan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $lapangan = $result->fetch_assoc();
            $gambar_lama = $lapangan['gambar_lapangan']; // Ambil gambar lama dari database
            $stmt->close();

            // Hapus gambar lama jika ada gambar baru yang diupload
            if (!empty($gambar_lama) && file_exists('assets/img/lapangan/' . $gambar_lama)) {
                unlink('assets/img/lapangan/' . $gambar_lama); // Hapus gambar lama
            }
        } else {
            die('Data lapangan tidak ditemukan.');
        }

        // Gambar baru diupload, proses upload
        $gambar_lapangan = $_FILES['gambar_lapangan'];
        $ext = pathinfo($gambar_lapangan['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $ext;
        $targetDir = 'assets/img/lapangan/';
        $targetFile = $targetDir . $fileName;

        // Upload file gambar
        if (move_uploaded_file($gambar_lapangan['tmp_name'], $targetFile)) {
            // Gambar baru berhasil diupload
            $gambar_lapangan = $fileName;
        } else {
            die('Upload gambar gagal.');
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $sql = "SELECT gambar_lapangan FROM tb_lapangan WHERE id_lapangan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_lapangan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $lapangan = $result->fetch_assoc();
            $gambar_lapangan = $lapangan['gambar_lapangan']; // Gunakan gambar lama jika tidak ada gambar baru
        } else {
            die('Data lapangan tidak ditemukan.');
        }
        $stmt->close();
    }

    // Siapkan SQL untuk update data lapangan
    $sql = "UPDATE tb_lapangan 
            SET nama_lapangan = ?, tipe_lapangan = ?, lokasi = ?, harga_per_jam = ?, status_lapangan = ?, gambar_lapangan = ? 
            WHERE id_lapangan = ?";

    // Koneksi ke database dan persiapan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissi', $nama_lapangan, $tipe_lapangan, $lokasi, $harga_per_jam, $status_lapangan, $gambar_lapangan, $id_lapangan);

    // Eksekusi query
    if ($stmt->execute()) {
        // Redirect ke lapangan.php setelah berhasil
        header('Location: lapangan.php?active=lapangan');
        exit();
    } else {
        // Menampilkan error jika gagal
        echo "Gagal mengupdate data: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
}


// Hapus Lapangan
if (isset($_POST['deleteDataLapangan'])) {
    $id_lapangan = $_POST['id_lapangan'];

    // Hapus data terkait di tb_pesanan
    $hapusPesanan = "DELETE FROM tb_pesanan WHERE id_lapangan = ?";
    if ($stmtPesanan = $conn->prepare($hapusPesanan)) {
        $stmtPesanan->bind_param("i", $id_lapangan);
        $stmtPesanan->execute();
        $stmtPesanan->close();
    }

    // Ambil nama gambar dari database sebelum menghapus data lapangan
    $sql = "SELECT gambar_lapangan FROM tb_lapangan WHERE id_lapangan = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_lapangan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $lapangan = $result->fetch_assoc();
            $gambar_lapangan = $lapangan['gambar_lapangan']; // Ambil nama gambar

            // Tentukan path file gambar
            $targetDir = 'assets/img/lapangan/';
            $targetFile = $targetDir . $gambar_lapangan;

            // Hapus file gambar jika ada
            if (!empty($gambar_lapangan) && file_exists($targetFile)) {
                unlink($targetFile); // Menghapus file gambar
            }
        } else {
            echo "Data lapangan tidak ditemukan.";
            exit();
        }

        $stmt->close();
    }

    // Hapus data lapangan
    $hapusDataLapangan = "DELETE FROM tb_lapangan WHERE id_lapangan = ?";
    if ($stmtDataLapangan = $conn->prepare($hapusDataLapangan)) {
        $stmtDataLapangan->bind_param("i", $id_lapangan);
        if ($stmtDataLapangan->execute()) {
            header("Location: lapangan.php?active=lapangan");
            exit;
        } else {
            echo "Error executing query: " . $stmtDataLapangan->error;
        }
        $stmtDataLapangan->close();
    }
}

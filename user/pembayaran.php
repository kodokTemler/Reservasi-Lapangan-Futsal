<?php
include '../controller/koneksi.php';
session_start();

// Redirect jika pengguna belum login
if (!isset($_SESSION['user'])) {
    $_SESSION['pesan'] = [
        'title' => 'Login Diperlukan',
        'text' => 'Silakan login untuk melihat halaman pembayaran.',
        'icon' => 'warning'
    ];
    header("Location: login.php");
    exit();
}

// Cek apakah `id_pesanan` ada di URL
if (!isset($_GET['id_pesanan'])) {
    $_SESSION['pesan'] = [
        'title' => 'Error!',
        'text' => 'ID Pemesanan tidak ditemukan.',
        'icon' => 'error'
    ];
    header("Location: index.php");
    exit();
}

$id_pesanan = mysqli_real_escape_string($conn, $_GET['id_pesanan']);

// Ambil data lapangan dan pemesanan berdasarkan `id_pesanan`
$query = "SELECT l.nama_lapangan, l.tipe_lapangan, l.gambar_lapangan, p.tanggal_pesanan, p.jam_mulai, p.jam_selesai, p.total_biaya
          FROM tb_lapangan l
          JOIN tb_pesanan p ON p.id_lapangan = l.id_lapangan
          WHERE p.id_pesanan = '$id_pesanan' AND p.id_user = '{$_SESSION['id_user']}'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['pesan'] = [
        'title' => 'Error!',
        'text' => 'Data pemesanan tidak ditemukan.',
        'icon' => 'error'
    ];
    header("Location: index.php");
    exit();
}

$data = mysqli_fetch_assoc($result);

// Handle form submission for payment proof
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_bayar = $data['total_biaya'];

    // Ambil nilai metode pembayaran dari input form
    if (!isset($_POST['metode_pembayaran']) || empty($_POST['metode_pembayaran'])) {
        $_SESSION['pesan'] = [
            'title' => 'Error!',
            'text' => 'Silakan pilih metode pembayaran.',
            'icon' => 'error'
        ];
        header("Location: pembayaran.php?id_pesanan=$id_pesanan");
        exit();
    }
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

    // Proses upload bukti pembayaran
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === 0) {
        $uploadDir = '../admin/assets/img/buktiPembayaran/';
        $fileName = time() . '_' . basename($_FILES['bukti_pembayaran']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $filePath)) {
            // Insert atau update data pembayaran dengan bukti pembayaran
            $query = "INSERT INTO tb_pembayaran (id_pesanan, id_user, total_bayar, metode_pembayaran, bukti_pembayaran, status_pembayaran, tanggal_pembayaran)
                      VALUES ('$id_pesanan', '{$_SESSION['id_user']}', '$total_bayar', '$metode_pembayaran', '$fileName', 'Pending', NOW())";

            if (mysqli_query($conn, $query)) {
                $_SESSION['pesan'] = [
                    'title' => 'Success!',
                    'text' => 'Bukti pembayaran berhasil diunggah. Harap menunggu verifikasi.',
                    'icon' => 'success'
                ];
                header("Location: pembayaran.php?id_pesanan=$id_pesanan");
                exit();
            } else {
                $_SESSION['pesan'] = [
                    'title' => 'Error!',
                    'text' => 'Gagal menyimpan data pembayaran.',
                    'icon' => 'error'
                ];
            }
        } else {
            $_SESSION['pesan'] = [
                'title' => 'Error!',
                'text' => 'Gagal mengunggah bukti pembayaran.',
                'icon' => 'error'
            ];
        }
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Error!',
            'text' => 'File bukti pembayaran tidak valid.',
            'icon' => 'error'
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../admin/assets/img/logoFutsal.png">
    <title>Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-cover bg-center min-h-screen flex items-center justify-center" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../admin/assets/img/bg.jpg');">

    <div class="max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden transform transition-all duration-300 hover:scale-105">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="relative">
                <img src="../admin/assets/img/lapangan/<?php echo $data['gambar_lapangan']; ?>" alt="Lapangan Image" class="w-full h-full object-cover rounded-l-xl">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent opacity-50"></div>
            </div>

            <div class="p-8 space-y-6">
                <h2 class="text-3xl font-bold text-gray-800 tracking-wide text-center">Detail Pembayaran</h2>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 tracking-wide">Nama Pemesan</span>
                    <span class="font-semibold text-gray-900"><?php echo $_SESSION['user']; ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 tracking-wide">Tipe Lapangan</span>
                    <span class="font-semibold text-gray-900"><?php echo $data['tipe_lapangan']; ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 tracking-wide">Tanggal Pemesanan</span>
                    <span class="font-semibold text-gray-900"><?php echo $data['tanggal_pesanan']; ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 tracking-wide">Waktu</span>
                    <span class="font-semibold text-gray-900"><?php echo $data['jam_mulai']; ?> - <?php echo $data['jam_selesai']; ?></span>
                </div>
                <div class="border-t pt-4 mt-4 flex items-center justify-between">
                    <span class="text-lg font-semibold text-gray-900 tracking-wide">Total Bayar</span>
                    <span class="text-lg font-semibold text-green-600 tracking-wide">Rp <?php echo number_format($data['total_biaya'], 0, ',', '.'); ?></span>
                </div>

                <form action="" method="post" enctype="multipart/form-data" class="pt-4">
                    <!-- Dropdown untuk Metode Pembayaran -->
                    <label for="metode_pembayaran" class="block text-gray-600 mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required
                        class="block w-full text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300 p-2 mb-4 bg-gray-100 hover:bg-gray-200 transition duration-200">
                        <option value="" disabled selected>Pilih Metode Pembayaran</option>
                        <option value="BNI">BNI (123-456-7890)</option>
                        <option value="BRI">BRI (987-654-3210)</option>
                        <option value="DANA">DANA (0812-3456-7890)</option>
                    </select>

                    <label for="bukti_pembayaran" class="block text-gray-600 mb-2">Upload Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required
                        class="block w-full text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring focus:border-blue-300 p-2 mb-4
                        bg-gray-100 hover:bg-gray-200 transition duration-200">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 tracking-wide">Bayar Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?>
        <script>
            Swal.fire({
                title: '<?php echo $_SESSION['pesan']['title']; ?>',
                text: '<?php echo $_SESSION['pesan']['text']; ?>',
                icon: '<?php echo $_SESSION['pesan']['icon']; ?>',
                confirmButtonText: 'OK',
                willClose: () => {
                    window.location.href = 'index.php';
                }
            });
        </script>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>
</body>

</html>
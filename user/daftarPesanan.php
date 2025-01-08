<?php
session_start();
include '../controller/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Query untuk mengambil data pesanan berdasarkan id_user
$sql = "SELECT p.id_pesanan, p.tanggal_pesanan, p.jam_mulai, p.jam_selesai, p.total_biaya, p.status, l.nama_lapangan, t.status_pembayaran
        FROM tb_pesanan p 
        JOIN tb_pembayaran t on p.id_pesanan = t.id_pesanan
        JOIN tb_lapangan l ON p.id_lapangan = l.id_lapangan 
        WHERE p.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../admin/assets/img/logoFutsal.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title>Daftar Pesanan</title>
</head>

<body class="bg-gray-100 font-poppins">
    <nav class="fixed top-0 left-0 w-full bg-transparent shadow-md z-10">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="text-xl font-bold text-blue-700 flex items-center">
                <img src="../admin/assets/img/logoFutsal.png" alt="Logo" class="h-12 w-auto mr-2">
                Futsal SK-13
            </a>
            <div class="hidden md:flex space-x-4">
                <a href="index.php" class="font-semibold text-white bg-yellow-600 hover:bg-yellow-700 transition duration-300 px-4 py-2 rounded">Kembali</a>
                <a href="logout.php" class="font-semibold text-white bg-red-600 hover:bg-red-700 transition duration-300 px-4 py-2 rounded">Logout</a>
            </div>
            <button id="menu-toggle" class="md:hidden text-gray-700">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div id="menu" class="hidden md:hidden bg-white">
            <ul class="flex flex-col items-start p-4 space-y-2">
                <li><a href="index.php" class="font-semibold text-white bg-yellow-400 hover:bg-yellow-700 transition duration-300 px-4 py-2 rounded">Kembali</a></li>
                <li><a href="logout.php" class="font-semibold text-white bg-red-600 hover:bg-red-700 transition duration-300 px-4 py-2 rounded">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header id="home" class="header-bg flex flex-col justify-center items-center text-center">
        <div class="header-content">
            <h1 class="text-4xl font-semibold text-white">Daftar Pesanan Saya</h1>
            <p class="mt-2 text-lg text-white">Lihat dan kelola semua pesanan futsal Anda di sini</p>
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="overflow-y-auto max-h-96"> <!-- Make table scrollable with max height -->
                        <table class="w-full bg-white">
                            <thead class="bg-blue-600 text-white sticky top-0 z-10"> <!-- Make header sticky -->
                                <tr>
                                    <th class="py-3 px-4 text-center font-medium">No</th>
                                    <th class="py-3 px-4 text-center font-medium">Nama Lapangan</th>
                                    <th class="py-3 px-4 text-center font-medium">Tanggal Pesan</th>
                                    <th class="py-3 px-4 text-center font-medium">Jam Mulai</th>
                                    <th class="py-3 px-4 text-center font-medium">Jam Selesai</th>
                                    <th class="py-3 px-4 text-center font-medium">Total Biaya</th>
                                    <th class="py-3 px-4 text-center font-medium">Pembayaran</th>
                                    <th class="py-3 px-4 text-center font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr class="hover:bg-gray-100 transition duration-200">
                                            <td class="py-3 px-4 text-center"><?php echo $no++; ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['nama_lapangan']); ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['tanggal_pesanan']); ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['jam_mulai']); ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['jam_selesai']); ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo 'Rp ' . number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if ($row['status_pembayaran'] == 'Dikonfirmasi'): ?>
                                                    <span class="text-green-600 font-semibold"><?php echo htmlspecialchars($row['status_pembayaran']); ?></span>
                                                <?php else: ?>
                                                    <span class="text-red-600 font-semibold"><?php echo htmlspecialchars($row['status_pembayaran']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if ($row['status'] == 'Sudah Main'): ?>
                                                    <span class="text-green-600 font-semibold"><?php echo htmlspecialchars($row['status']); ?></span>
                                                <?php else: ?>
                                                    <span class="text-red-600 font-semibold"><?php echo htmlspecialchars($row['status']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">Tidak ada pesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </header>



    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
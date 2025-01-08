<?php
include '../controller/UserPemesanan.php';
// Ambil data pengguna dan lapangan

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../admin/assets/img/logoFutsal.png">
    <title>Pemesanan Lapangan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body class="bg-cover bg-center min-h-screen flex items-center justify-center" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../admin/assets/img/bg.jpg');"">

<?php if (isset($_SESSION['pesan'])): ?>
    <script>
        Swal.fire({
            title: '<?= $_SESSION['pesan']['title'] ?>',
            text: '<?= $_SESSION['pesan']['text'] ?>',
            icon: '<?= $_SESSION['pesan']['icon'] ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke halaman pembayaran setelah SweetAlert dikonfirmasi
                <?php if (isset($_SESSION['id_pesanan'])): ?>
                    window.location.href = 'pembayaran.php?id_pesanan=<?= $_SESSION['id_pesanan'] ?>';
                <?php else: ?>
                    window.location.href = 'index.php';
                <?php endif; ?>
            }
        });
    </script>
<?php unset($_SESSION['pesan']);
endif; ?>

    <div class=" container mx-auto flex flex-col items-center py-10">
    <h2 class="text-4xl font-bold text-center mb-6 text-white">Form Pemesanan Lapangan</h2>

    <div class="flex flex-col lg:flex-row bg-white rounded-lg shadow-lg border border-gray-200 w-full lg:w-3/4">
        <div class="w-full lg:w-1/2">
            <img src="../admin/assets/img/lapangan/<?= $lapangan['gambar_lapangan'] ?>" alt="<?= htmlspecialchars($lapangan['nama_lapangan']) ?>" class="object-cover w-full h-full rounded-l-lg shadow-lg border border-gray-300">
        </div>

        <div class="w-full lg:w-1/2 p-8 flex flex-col">
            <form method="POST" action="" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Nama User:</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user_name) ?>" class="mt-1 p-3 border border-gray-300 rounded-md w-full bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Lapangan:</label>
                    <input type="text" name="nama_lapangan" value="<?= htmlspecialchars($lapangan['nama_lapangan']) ?>" class="mt-1 p-3 border border-gray-300 rounded-md w-full bg-gray-100 cursor-not-allowed" readonly>
                    <input type="hidden" name="id_lapangan" value="<?= htmlspecialchars($id_lapangan) ?>">
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Harga per Jam:</label>
                    <input type="text" name="harga_per_jam" value="Rp <?= number_format($lapangan['harga_per_jam'], 0, ',', '.') ?>" class="mt-1 p-3 border border-gray-300 rounded-md w-full bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pemesanan:</label>
                    <input type="date" name="tanggal_pesanan" class="mt-1 p-3 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Jam Mulai:</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="mt-1 p-3 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700">Jam Selesai:</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="mt-1 p-3 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Biaya:</label>
                    <input type="text" id="biaya" name="biaya" class="mt-1 p-3 border border-gray-300 rounded-md w-full bg-gray-100 cursor-not-allowed" readonly>
                </div>
                <input type="hidden" name="status" value="Diproses">
                <div class="col-span-2 flex justify-between">
                    <button type="submit" name="pesanan" class="mt-4 bg-blue-600 text-white py-3 rounded-md w-1/2 mr-2">Pesan</button>
                    <a href="index.php" class="mt-4 bg-gray-600 text-white py-3 rounded-md w-1/2 text-center">Kembali</a>
                </div>

            </form>
        </div>
    </div>
    </div>

    <script>
        // Kalkulasi biaya otomatis
        document.getElementById('jam_mulai').addEventListener('change', calculateCost);
        document.getElementById('jam_selesai').addEventListener('change', calculateCost);

        function calculateCost() {
            var start = document.getElementById('jam_mulai').value;
            var end = document.getElementById('jam_selesai').value;
            var pricePerHour = <?= $lapangan['harga_per_jam'] ?>;

            if (start && end) {
                var startTime = new Date("1970-01-01 " + start);
                var endTime = new Date("1970-01-01 " + end);
                var hours = (endTime - startTime) / 36e5; // Hitung selisih jam
                if (hours > 0) {
                    document.getElementById('biaya').value = hours * pricePerHour;
                } else {
                    document.getElementById('biaya').value = 0;
                }
            } else {
                document.getElementById('biaya').value = 0;
            }
        }
    </script>

</body>

</html>
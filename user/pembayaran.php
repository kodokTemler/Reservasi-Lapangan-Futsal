<?php
// user/pembayaran.php
include '../controller/koneksi.php';
session_start();

// Redirect jika pengguna belum login
if (!isset($_SESSION['user']) || !isset($_SESSION['id_user'])) {
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="icon" type="image/png" href="../admin/assets/img/logoFutsal.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-1e1cQp3TBnL_xbTF"></script>
    <style>
        .left-img {
            min-height: 280px;
        }
    </style>
</head>

<body class="bg-cover bg-center min-h-screen flex items-center justify-center" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../admin/assets/img/bg.jpg');">
    <div class="max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden transform transition-all duration-300 hover:scale-105">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="relative left-img">
                <img src="../admin/assets/img/lapangan/<?php echo htmlspecialchars($data['gambar_lapangan']); ?>" alt="Lapangan Image" class="w-full h-full object-cover rounded-l-xl">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent opacity-50"></div>
            </div>

            <div class="p-8 space-y-6">
                <h2 class="text-3xl font-bold text-gray-800 tracking-wide text-center">Detail Pembayaran</h2>

                <div class="flex items-center justify-between"><span class="text-gray-600 tracking-wide">Nama Pemesan</span><span class="font-semibold text-gray-900"><?php echo htmlspecialchars($_SESSION['user']); ?></span></div>

                <div class="flex items-center justify-between"><span class="text-gray-600 tracking-wide">Tipe Lapangan</span><span class="font-semibold text-gray-900"><?php echo htmlspecialchars($data['tipe_lapangan']); ?></span></div>

                <div class="flex items-center justify-between"><span class="text-gray-600 tracking-wide">Tanggal Pemesanan</span><span class="font-semibold text-gray-900"><?php echo htmlspecialchars($data['tanggal_pesanan']); ?></span></div>

                <div class="flex items-center justify-between"><span class="text-gray-600 tracking-wide">Waktu</span><span class="font-semibold text-gray-900"><?php echo htmlspecialchars($data['jam_mulai']); ?> - <?php echo htmlspecialchars($data['jam_selesai']); ?></span></div>

                <div class="border-t pt-4 mt-4 flex items-center justify-between"><span class="text-lg font-semibold text-gray-900 tracking-wide">Total Bayar</span><span class="text-lg font-semibold text-green-600 tracking-wide">Rp <?php echo number_format($data['total_biaya'], 0, ',', '.'); ?></span></div>

                <button id="payBtn" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 tracking-wide">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payBtn').addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');

            Swal.fire({
                title: 'Mempersiapkan pembayaran...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new URLSearchParams();
            formData.append('id_pesanan', '<?php echo addslashes($id_pesanan); ?>');
            formData.append('id_user', '<?php echo addslashes($_SESSION['id_user']); ?>');
            formData.append('total_bayar', '<?php echo addslashes($data['total_biaya']); ?>');
            formData.append('nama', '<?php echo addslashes($_SESSION['user']); ?>');
            formData.append('email', '<?php echo addslashes($_SESSION['email'] ?? ''); ?>');

            fetch('../controller/create_snap_token.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            let msg = 'Terjadi kesalahan di server.';
                            try { const j = JSON.parse(text); msg = j.error || msg; } catch(e) {}
                            throw new Error(msg);
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    Swal.close();
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');

                    if (!data) {
                        Swal.fire('Error', 'Respons tidak dikenali dari server.', 'error');
                        return;
                    }
                    if (data.error) {
                        Swal.fire('Gagal', data.error, 'error');
                        return;
                    }

                    // Jika ada token -> Snap popup
                    if (data.token) {
                        snap.pay(data.token, {
                            onSuccess: function(result) {
                                window.location.href = 'daftarPesanan.php';
                            },
                            onPending: function(result) {
                                window.location.href = 'daftarPesanan.php';
                            },
                            onError: function(result) {
                                Swal.fire('Gagal', 'Pembayaran gagal. Silakan coba lagi.', 'error');
                            },
                            onClose: function() {
                                // user closed popup, tetap di halaman
                            }
                        });
                        return;
                    }

                    // Jika token null tapi ada redirect_url -> redirect (bank transfer flow)
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                        return;
                    }

                    Swal.fire('Gagal', 'Token atau redirect URL tidak diterima dari server.', 'error');
                })
                .catch(err => {
                    Swal.close();
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    console.error(err);
                    Swal.fire('Error', err.message || 'Terjadi kesalahan saat membuat transaksi.', 'error');
                });
        });
    </script>

    <?php if (isset($_SESSION['pesan'])): ?>
        <script>
            Swal.fire({
                title: '<?php echo addslashes($_SESSION['pesan']['title']); ?>',
                text: '<?php echo addslashes($_SESSION['pesan']['text']); ?>',
                icon: '<?php echo addslashes($_SESSION['pesan']['icon']); ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>
    <?php unset($_SESSION['pesan']);
    endif; ?>

</body>

</html>
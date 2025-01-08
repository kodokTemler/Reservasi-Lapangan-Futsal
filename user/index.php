<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

include '../controller/koneksi.php';
include '../controller/CrudUlasan.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Futsal Booking</title>
</head>

<body class="bg-gray-100">

    <nav id="navbar" class="navbar fixed w-full top-0 left-0 bg-transparent shadow-md z-10">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="#home" class="scroll-link text-xl font-bold text-blue-700 flex items-center">
                    <img src="../admin/assets/img/logoFutsal.png" alt="" class="h-12 w-auto mr-2">
                    Futsal SK-13
                </a>
            </div>
            <div class="hidden md:flex justify-center flex-grow">
                <ul class="flex space-x-8">
                    <li><a href="#home" class="scroll-link font-semibold text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Home</a></li>
                    <li><a href="#about" class="scroll-link font-semibold text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">About</a></li>
                    <li><a href="#lapangan" class="scroll-link font-semibold text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Lapangan</a></li>
                    <li><a href="#contact" class="scroll-link font-semibold text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Contact</a></li>
                </ul>
            </div>
            <div class="hidden md:flex space-x-4">
                <!-- Booking Button -->
                <a href="daftarPesanan.php" class="font-semibold text-white bg-green-600 hover:bg-green-700 transition duration-300 px-4 py-2 rounded">Daftar Pesanan</a>
                <!-- Logout Button -->
                <a href="logout.php" class="font-semibold text-white bg-red-600 hover:bg-red-700 transition duration-300 px-4 py-2 rounded">Logout</a>
            </div>
            <div class="md:hidden flex items-center">
                <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="menu" class="md:hidden hidden bg-white">
            <ul class="flex flex-col space-y-4 px-4 py-2">
                <li><a href="#home" class="scroll-link font-semibold block text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Home</a></li>
                <li><a href="#about" class="scroll-link font-semibold block text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">About</a></li>
                <li><a href="#lapangan" class="scroll-link font-semibold block text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Lapangan</a></li>
                <li><a href="#contact" class="scroll-link font-semibold block text-gray-700 hover:bg-blue-100 transition duration-300 px-2 py-1 rounded">Contact</a></li>
                <!-- Booking Button for Mobile -->
                <li>
                    <a href="daftarPesanan.php" class="font-semibold block text-white bg-green-600 hover:bg-green-700 transition duration-300 px-4 py-2 rounded text-center">Pemesanan</a>
                </li>
                <!-- Logout Button for Mobile -->
                <li>
                    <a href="logout.php" class="font-semibold block text-white bg-red-600 hover:bg-red-700 transition duration-300 px-4 py-2 rounded text-center">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Fullscreen Header Section -->
    <header id="home" class="header-bg flex flex-col justify-center items-center text-center text-white">
        <div class="header-content">
            <h1 class="text-5xl font-bold mb-4 animate__animated animate__fadeIn">Selamat Datang, <?= $_SESSION['user']; ?> di Futsal Sk-13</h1>
            <p class="text-lg mb-2 animate__animated animate__fadeIn animate__delay-1s">Website pemesanan lapangan futsal terbaik untuk Anda!</p>
            <p class="text-lg animate__animated animate__fadeIn animate__delay-2s">Pesan lapangan dengan mudah dan cepat hanya di sini!</p>
        </div>
    </header>

    <!-- Section About -->
    <section id="about" class="py-20 relative">
        <div class="absolute inset-0"></div> <!-- Overlay -->
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold mb-4 animate__animated animate__fadeIn text-black">Tentang Futsal Sk-13</h2>
                <p class="text-lg text-black animate__animated animate__fadeIn animate__delay-1s">
                    Futsal Sk-13 adalah tempat terbaik untuk menikmati permainan futsal yang menyenangkan dan kompetitif.
                    Kami menawarkan fasilitas lapangan berkualitas tinggi dan layanan pelanggan yang ramah.
                </p>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center space-x-0 md:space-x-10">
                <div class="md:w-1/2 animate__animated animate__fadeIn animate__delay-2s">
                    <img src="../admin/assets/img/tentang.jpeg" alt="About Futsal" class="w-full h-65 object-cover rounded-lg shadow-lg">
                </div>
                <div class="md:w-1/2 mt-8 md:mt-0 animate__animated animate__fadeIn animate__delay-3s">
                    <h3 class="text-2xl font-semibold mb-4 text-black">Fasilitas Kami</h3>
                    <p class="text-black mb-4">
                        Lapangan futsal kami dilengkapi dengan permukaan terbaik dan pencahayaan yang memadai untuk memberikan pengalaman bermain yang optimal.
                    </p>
                    <ul class="list-disc pl-5 text-black">
                        <li>Kualitas lapangan yang sangat baik</li>
                        <li>Ruang ganti yang bersih</li>
                        <li>Fasilitas parkir yang aman</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Lapangan -->
    <section id="lapangan" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-10">Lapangan Kami</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php
                $lapanganSql = "SELECT * FROM tb_lapangan";
                $lapanganRes = mysqli_query($conn, $lapanganSql);
                while ($lapanganData = mysqli_fetch_assoc($lapanganRes)) {
                    $id_lapangan = $lapanganData['id_lapangan'];
                    $nama_lapangan = $lapanganData['nama_lapangan'];
                    $tipe_lapangan = $lapanganData['tipe_lapangan'];
                    $lokasi = $lapanganData['lokasi'];
                    $harga_per_jam = $lapanganData['harga_per_jam'];
                    $status_lapangan = $lapanganData['status_lapangan'];
                    $gambar_lapangan = $lapanganData['gambar_lapangan'];
                ?>
                    <div class="bg-white rounded-lg shadow-lg p-5 mx-auto text-center">
                        <img src="../admin/assets/img/lapangan/<?= $gambar_lapangan ?>" alt="<?= $nama_lapangan ?>" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="text-2xl font-semibold mb-3"><?= $nama_lapangan ?></h3>

                        <!-- Lokasi -->
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            <span><?= $lokasi ?></span>
                        </div>

                        <!-- Harga per Jam -->
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-dollar-sign text-green-500 mr-2"></i>
                            <span>Rp <?= number_format($harga_per_jam, 0, ',', '.') ?>/jam</span>
                        </div>

                        <!-- Tipe Lapangan -->
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-futbol text-yellow-500 mr-2"></i>
                            <span>Tipe: <?= $tipe_lapangan ?></span>
                        </div>

                        <!-- Status Lapangan -->
                        <div class="flex items-center justify-center mb-4">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span>Status: <?= $status_lapangan ?></span>
                        </div>

                        <!-- Tombol Pesan Sekarang -->
                        <?php if ($status_lapangan === 'Pemeliharaan' || $status_lapangan === 'DiPesan') { ?>
                            <button class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed" disabled>Pesan Sekarang</button>
                        <?php } else { ?>
                            <a href="userPemesanan.php?id_lapangan=<?= $id_lapangan ?>">
                                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Pesan Sekarang</button>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="py-20 bg-gray-100 relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Contact Us</h2>
                <p class="text-lg text-gray-600">Hubungi kami untuk informasi lebih lanjut dan pemesanan lapangan.</p>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-10 justify-center">
                <!-- Google Maps Embed -->
                <div class="w-full md:w-1/2 mb-8 md:mb-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d402.2054356932466!2d119.44591416148243!3d-5.1418623104462275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee30039b6aab1%3A0x49fff07a94f0cda!2sSk13%20Zone!5e0!3m2!1sid!2sid!4v1732222556404!5m2!1sid!2sid"
                        width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <!-- Contact Form -->
                <div class="w-full md:w-1/2 bg-white rounded-lg shadow-lg p-8">
                    <form action="#" method="POST" class="space-y-3">
                        <div>
                            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama</label>
                            <input type="text" id="name" name="nama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama Anda" required>
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Anda" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                            <input type="tel" id="phone" name="no_telp" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nomor Telepon Anda" required>
                        </div>

                        <div>
                            <label for="reason" class="block text-gray-700 font-semibold mb-2">Ulasan</label>
                            <textarea id="reason" name="ulasan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan alasan Anda..." required></textarea>
                        </div>

                        <div>
                            <button type="submit" name="addUlasan" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">Kirim Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10 relative z-10"> <!-- Tambahkan z-10 pada footer untuk memastikan di atas gradient -->
        <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About Us Section -->
                <div class="space-y-4 animate__animated animate__fadeInUp">
                    <h2 class="text-2xl font-semibold">Tentang Kami</h2>
                    <p class="text-gray-400">Futsal Sk-13 menyediakan fasilitas futsal berkualitas tinggi dengan pemesanan yang mudah dan layanan pelanggan yang ramah. Temukan pengalaman bermain futsal terbaik bersama kami.</p>
                    <p class="text-gray-400">Alamat: Jalan Futsal No. 13, Kota Besar</p>
                </div>

                <!-- Quick Links Section -->
                <div class="space-y-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <h2 class="text-2xl font-semibold">Navigasi</h2>
                    <ul class="space-y-2">
                        <li><a href="#home" class="scroll-link text-gray-400 hover:text-blue-400 transition duration-300">Home</a></li>
                        <li><a href="#about" class="scroll-link text-gray-400 hover:text-blue-400 transition duration-300">About</a></li>
                        <li><a href="#lapangan" class="scroll-link text-gray-400 hover:text-blue-400 transition duration-300">Lapangan Kami</a></li>
                        <li><a href="#contact" class="scroll-link text-gray-400 hover:text-blue-400 transition duration-300">Contact</a></li>
                    </ul>
                </div>

                <!-- Social Media Section -->
                <div class="space-y-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <h2 class="text-2xl font-semibold">Ikuti Kami</h2>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 text-2xl">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 text-2xl">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 text-2xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-blue-400 transition duration-300 text-2xl">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-700 pt-4 text-center">
                <p class="text-gray-500">&copy; 2024 Futsal Sk-13. All rights reserved.</p>
            </div>
        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        // JavaScript for smooth scroll
        const scrollLinks = document.querySelectorAll('.scroll-link');

        scrollLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault(); // Mencegah perilaku default anchor link
                const targetId = link.getAttribute('href'); // Mendapatkan ID target
                const targetElement = document.querySelector(targetId); // Menemukan elemen target
                const offset = targetElement.getBoundingClientRect().top + window.scrollY; // Menghitung offset untuk scroll

                // Scroll ke elemen target dengan animasi halus
                window.scrollTo({
                    top: offset,
                    behavior: 'smooth'
                });
            });
        });

        // Toggle mobile menu
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');

        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 0) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        <?php if (isset($_SESSION['pesan'])): ?>
            Swal.fire({
                title: '<?= $_SESSION['pesan']['title'] ?>',
                text: '<?= $_SESSION['pesan']['text'] ?>',
                icon: '<?= $_SESSION['pesan']['icon'] ?>',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect after success or error
                    window.location.href = "index.php";
                }
            });
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>
    </script>
</body>

</html>
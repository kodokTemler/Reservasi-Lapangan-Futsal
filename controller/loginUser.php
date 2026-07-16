<?php
session_start();
include 'koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // pastikan PHPMailer sudah di-install dengan composer

if (isset($_POST['daftarUser'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $password = $_POST['password'];

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Buat token unik untuk verifikasi
    $token = bin2hex(random_bytes(32));

    // Kirim email verifikasi dulu, belum simpan ke DB
    $verifyLink = "http://localhost/AplikasiKonsentrasi/controller/verify.php?token=$token&nama=" . urlencode($nama) . "&email=" . urlencode($email) . "&no_telp=" . urlencode($no_telp) . "&password=" . urlencode($hashedPassword);

    $mail = new PHPMailer(true);

    try {
        // Konfigurasi Mailtrap
        $mail->isSMTP();
        $mail->Host       = 'smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '#'; // ganti dengan username Mailtrap
        $mail->Password   = '#'; // ganti dengan password Mailtrap
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 2525;

        // Pengirim & penerima
        $mail->setFrom('caluAdmin@domainmu.com', 'Calu Admin');
        $mail->addAddress($email, $nama);

        // Email HTML
        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Akun Anda - Futsal SK-13';
        $mail->Body = "
            <div style='font-family: \"Poppins\", Arial, sans-serif; background-color:#f7f9fc; padding:40px 0;'>
                <div style='max-width:600px; margin:0 auto; background:white; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); overflow:hidden;'>
                    
                    <div style='background:linear-gradient(135deg, #4CAF50, #2E8B57); color:white; text-align:center; padding:30px 20px;'>
                        <h1 style='margin:0; font-size:26px; letter-spacing:1px;'>Verifikasi Akun Anda</h1>
                    </div>

                    <div style='padding:30px 40px; color:#333;'>
                        <h2 style='margin-top:0;'>Hai, $nama</h2>
                        <p style='font-size:15px; line-height:1.6;'>
                            Terima kasih telah mendaftar di <b>Futsal SK-13</b>!<br>
                            Silakan klik tombol di bawah ini untuk memverifikasi akun Anda dan menyelesaikan pendaftaran:
                        </p>

                        <div style='text-align:center; margin:35px 0;'>
                            <a href='$verifyLink' 
                                style='background:linear-gradient(135deg, #4CAF50, #2E8B57); color:white; text-decoration:none; 
                                padding:14px 28px; border-radius:8px; font-weight:600; font-size:16px; 
                                display:inline-block; box-shadow:0 4px 10px rgba(76,175,80,0.3); transition:0.3s;'>
                                Verifikasi Akun
                            </a>
                        </div>

                        <p style='font-size:14px; color:#555; line-height:1.5;'>
                            Jika kamu tidak merasa mendaftar di <b>Futsal SK-13</b>, abaikan email ini saja.
                        </p>
                    </div>

                    <div style='background:#f0f0f0; text-align:center; padding:15px; font-size:13px; color:#777;'>
                        © " . date('Y') . " Futsal SK-13. Semua hak dilindungi.
                    </div>
                </div>
            </div>
        ";

        $mail->send();

        $_SESSION['pesan'] = [
            'title' => 'Berhasil!',
            'text' => 'Silakan cek email Anda untuk verifikasi akun.',
            'icon' => 'success'
        ];
    } catch (Exception $e) {
        $_SESSION['pesan'] = [
            'title' => 'Error!',
            'text' => 'Email verifikasi gagal dikirim. Silakan coba lagi.',
            'icon' => 'error'
        ];
    }

    header("Location: index.php");
    exit();
} elseif (isset($_POST['masukUser'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tb_user WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['user'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['no_telp'];
            $_SESSION['pesan'] = [
                'title' => 'Login Berhasil!',
                'text' => 'Selamat datang, ' . $user['nama'] . '!',
                'icon' => 'success',
                'redirect' => 'user/index.php' // Menyimpan URL redirect
            ];
        } else {
            $_SESSION['pesan'] = [
                'title' => 'Login Gagal!',
                'text' => 'Email dan Password yang Anda masukkan mungkin salah.',
                'icon' => 'error'
            ];
        }
    } else {
        $_SESSION['pesan'] = [
            'title' => 'Login Gagal!',
            'text' => 'Email dan Password yang Anda masukkan mungkin salah.',
            'icon' => 'error'
        ];
    }
    header("Location: index.php"); // Redirect ke halaman login agar pesan bisa muncul
    exit();
}

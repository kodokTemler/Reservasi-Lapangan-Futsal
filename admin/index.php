<?php
include '../controller/login.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="assets/img/logoFutsal.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/login.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <title>Login</title>
</head>

<body style="background-image: url('assets/img/bg.jpg'); background-repeat: no-repeat; background-position: center; background-size: cover; background-attachment: fixed;">
  <div class="login-page">
    <div class="form">
      <form class="login-form" method="post">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button name="login" type="submit">login</button>
      </form>
    </div>
  </div>
  <script type="module" src="assets/js/login.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
    <?php if (isset($_SESSION['pesan'])): ?>
      Swal.fire({
        title: '<?= $_SESSION['pesan']['title'] ?>',
        text: '<?= $_SESSION['pesan']['text'] ?>',
        icon: '<?= $_SESSION['pesan']['icon'] ?>',
        confirmButtonText: 'OK'
      }).then((result) => {
        <?php if (isset($_SESSION['pesan']['redirect'])): ?>
          // Redirect ke halaman user jika login berhasil
          if (result.isConfirmed) {
            window.location.href = '<?= $_SESSION['pesan']['redirect'] ?>';
          }
        <?php endif; ?>
      });
      <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>
  </script>
</body>

</html>
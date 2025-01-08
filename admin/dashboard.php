<?php
include '../controller/koneksi.php';

// Menghitung Jumlah Admin Yang Berada diDatabase
$sqlAdmin = "SELECT COUNT(*) AS totalAdmin FROM tb_admin";
$resCountAdmin = mysqli_query($conn, $sqlAdmin);
$countDataAdmin = mysqli_fetch_assoc($resCountAdmin);
$totalAdmin = $countDataAdmin['totalAdmin'];

// Menghitung Jumlah User Yang Berada diDatabase
$sqlUser = "SELECT COUNT(*) AS totalUser FROM tb_user";
$resCountUser = mysqli_query($conn, $sqlUser);
$countDataUser = mysqli_fetch_assoc($resCountUser);
$totalUser = $countDataUser['totalUser'];

// Menghitung Jumlah Lapangan Yang Berada diDatabase
$sqllapangan = "SELECT COUNT(*) AS totalLapangan FROM tb_lapangan";
$resCountLapangan = mysqli_query($conn, $sqllapangan);
$countDataLapangan = mysqli_fetch_assoc($resCountLapangan);
$totalLapangan = $countDataLapangan['totalLapangan'];

// Menghitung Jumlah Pesanan Yang Berada diDatabase
$sqlPesanan = "SELECT COUNT(*) AS totalPesanan FROM tb_pesanan";
$resCountPesanan = mysqli_query($conn, $sqlPesanan);
$countDataPesanan = mysqli_fetch_assoc($resCountPesanan);
$totalPesanan = $countDataPesanan['totalPesanan'];

// Mengambil data pemasukan berdasarkan bulan
$sqlIncome = "SELECT MONTH(tanggal_pembayaran) AS bulan, SUM(total_bayar) AS pemasukan
              FROM tb_pembayaran
              WHERE status_pembayaran = 'Dikonfirmasi'
              GROUP BY MONTH(tanggal_pembayaran)";
$resIncome = mysqli_query($conn, $sqlIncome);

$incomeData = [];
while ($row = mysqli_fetch_assoc($resIncome)) {
  $incomeData[(int)$row['bulan']] = (int)$row['pemasukan'];
}

// Mengisi bulan yang tidak ada pemasukan dengan nilai 0
$allMonths = range(1, 12);
foreach ($allMonths as $month) {
  if (!isset($incomeData[$month])) {
    $incomeData[$month] = 0;
  }
}
ksort($incomeData); // Mengurutkan data berdasarkan bulan

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Dashboard</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <!-- Link CSS -->
  <link rel="icon" type="image/png" href="assets/img/logoFutsal.png">
  <?php
  include 'components/link.php'
  ?>
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <?php
    require 'components/sidebar.php';
    ?>
    <!-- End Sidebar -->

    <div class="main-panel">
      <!-- Header -->
      <?php
      require 'components/header.php'
      ?>

      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Dashboard</h3>
              <h6 class="op-7 mb-2"> Selamat Datang, <?= $_SESSION['name'] ?></h6>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-primary bubble-shadow-small">
                        <i class="fas fa-user-check"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Jumlah Admin</p>
                        <h4 class="card-title"><?= $totalAdmin ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-info bubble-shadow-small">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Jumlah User</p>
                        <h4 class="card-title"><?= $totalUser ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-success bubble-shadow-small">
                        <i class="fas fa-futbol"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Jumlah Lapangan</p>
                        <h4 class="card-title"><?= $totalLapangan ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div class="icon-big text-center icon-secondary bubble-shadow-small">
                        <i class="far fa-check-circle"></i>
                      </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                      <div class="numbers">
                        <p class="card-category">Jumlah Pesanan</p>
                        <h4 class="card-title"><?= $totalPesanan ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Grafik Pemasukan -->
          <div class="row mt-5">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Grafik Pemasukan Bulanan</h4>
                  <canvas id="incomeChart" width="400" height="200"></canvas>
                </div>
              </div>
            </div>
          </div>
          <!-- End Grafik Pemasukan -->
        </div>
      </div>

      <!-- Footer -->
      <?php
      require 'components/footer.php'
      ?>
    </div>

    <!-- Custom template | don't include it in your project! -->
    <?php
    require 'components/settings.php'
    ?>
    <!-- End Custom template -->
  </div>
  <!--   Core JS Files   -->
  <?php
  require 'components/script.php'
  ?>

</body>

</html>
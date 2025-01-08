<?php
include '../controller/CrudPembayaran.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Dashboard</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <!-- Link CSS -->
    <link rel="icon" type="image/png" href="assets/img/logoFutsal.png">
    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
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
            require 'components/header.php';
            ?>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Tables</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="dashboard.php?active=dashboard">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="daftarPembayaran.php?active=pembayaran">Daftar Pembayaran</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="card-title">Pembayaran Table</div>
                                    <!-- Form Filter Tanggal -->
                                    <form action="daftarPembayaran.php" id="filterForm" class="d-flex gap-2" method="GET">
                                        <input type="hidden" name="active" value="pembayaran">
                                        <div>
                                            <input type="date" id="startDate" name="start_date" class="p-2 border border-gray-300 rounded-md w-full" value="<?= $_GET['start_date'] ?? '' ?>">
                                        </div>
                                        <div>
                                            <input type="date" id="endDate" name="end_date" class="p-2 border border-gray-300 rounded-md w-full" value="<?= $_GET['end_date'] ?? '' ?>">
                                        </div>
                                        <button type="submit" class="btn btn-outline-info btn-sm">
                                            <i class="fa fa-filter px-2"></i>Filter
                                        </button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama User</th>
                                                    <th scope="col">Status Pembayaran</th>
                                                    <th scope="col">Total Bayar</th>
                                                    <th scope="col">Tanggal Bayar</th>
                                                    <th scope="col">Metode Pembayaran</th>
                                                    <th scope="col">Bukti Pembayaran</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                // Default query
                                                $pembayaranSql = "SELECT tb_pembayaran.id_pesanan, tb_user.nama, tb_pembayaran.status_pembayaran, tb_pembayaran.total_bayar, tb_pembayaran.tanggal_pembayaran, tb_pembayaran.metode_pembayaran, tb_pembayaran.bukti_pembayaran FROM tb_pembayaran JOIN tb_user ON tb_pembayaran.id_user = tb_user.id_user";

                                                // Tambahkan filter tanggal jika disediakan
                                                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                                                    $start_date = $_GET['start_date'];
                                                    $end_date = $_GET['end_date'];
                                                    $pembayaranSql .= " WHERE tb_pembayaran.tanggal_pembayaran BETWEEN '$start_date' AND '$end_date'";
                                                }

                                                // Urutkan berdasarkan tanggal pembayaran
                                                $pembayaranSql .= " ORDER BY tb_pembayaran.tanggal_pembayaran DESC";

                                                $pembayaranRes = mysqli_query($conn, $pembayaranSql);
                                                $pembayaranCount = 1;

                                                // Periksa apakah ada data
                                                if (mysqli_num_rows($pembayaranRes) > 0) {
                                                    while ($pembayaranData = mysqli_fetch_assoc($pembayaranRes)) {
                                                        $id_pesanan = $pembayaranData['id_pesanan'];
                                                        $nama = $pembayaranData['nama'];
                                                        $status_pembayaran = $pembayaranData['status_pembayaran'];
                                                        $total_bayar = $pembayaranData['total_bayar'];
                                                        $tanggal_bayar = $pembayaranData['tanggal_pembayaran'];
                                                        $metode_pembayaran = $pembayaranData['metode_pembayaran'];
                                                        $bukti_pembayaran = $pembayaranData['bukti_pembayaran'];
                                                ?>
                                                        <tr>
                                                            <td><?= $pembayaranCount++ ?></td>
                                                            <td><?= $nama ?></td>
                                                            <td><?= $status_pembayaran ?></td>
                                                            <td>Rp. <?= $total_bayar ?></td>
                                                            <td><?= $tanggal_bayar ?></td>
                                                            <td><?= $metode_pembayaran ?></td>
                                                            <td>
                                                                <?php if ($bukti_pembayaran): ?>
                                                                    <a href="assets/img/buktiPembayaran/<?= $bukti_pembayaran ?>" target="_blank">Lihat Bukti</a>
                                                                <?php else: ?>
                                                                    Tidak ada bukti
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <!-- Modal for Edit Payment -->
                                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editRowModal<?= $id_pesanan; ?>" class="btn btn-link btn-success btn-lg" title="Edit Task">
                                                                        <i class="fa fa-check-circle"></i>
                                                                    </button>

                                                                    <!-- Modal for Delete Payment -->
                                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteRowModal<?= $id_pesanan ?>" class="btn btn-link btn-danger" title="Remove">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <!-- Modal for Editing Payment -->
                                                        <div class="modal fade" id="editRowModal<?= $id_pesanan ?>" tabindex="-1" role="dialog" aria-labelledby="addRowModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header border-0">
                                                                        <h5 class="modal-title" id="addRowModalLabel">
                                                                            <span class="fw-mediumbold">Edit</span>
                                                                            <span class="fw-light"> Pembayaran</span>
                                                                        </h5>
                                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post">
                                                                            <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan) ?>">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group form-group-default">
                                                                                        <label for="nama">Nama</label>
                                                                                        <input name="nama" type="text" id="nama" value="<?= htmlspecialchars($nama) ?>" readonly class="form-control" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group form-group-default">
                                                                                        <label for="status_pembayaran">Status Pembayaran</label>
                                                                                        <select name="status_pembayaran" class="form-control">
                                                                                            <option value="Dikonfirmasi" <?= ($status_pembayaran == 'Dikonfirmasi') ? 'selected' : '' ?>>Dikonfirmasi</option>
                                                                                            <option value="Pending" <?= ($status_pembayaran == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="modal-footer border-0">
                                                                                <button type="submit" name="editDataPembayaran" class="btn btn-primary">Update</button>
                                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal for Deleting Payment -->
                                                        <div class="modal fade" id="deleteRowModal<?= $id_pesanan ?>" tabindex="-1" role="dialog" aria-labelledby="addRowModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header border-0">
                                                                        <h5 class="modal-title" id="addRowModalLabel">
                                                                            <span class="fw-mediumbold">Delete</span>
                                                                            <span class="fw-light"> Pembayaran</span>
                                                                        </h5>
                                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post">
                                                                            <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
                                                                            <p>Apakah anda yakin mau menghapus pembayaran untuk <?= $nama ?> ?</p>
                                                                            <div class="modal-footer border-0">
                                                                                <button type="submit" name="hapusPembayaran" class="btn btn-primary">Delete</button>
                                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada data untuk rentang tanggal yang dipilih</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php
            require 'components/footer.php';
            ?>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <?php
        require 'components/settings.php';
        ?>
        <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });

            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</body>

</html>
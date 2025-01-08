<?php
include '../controller/CrudPemesanan.php';


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
            require 'components/header.php'
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
                                <a href="pemesanan.php?active=pemesanan">Daftar Pemesanan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="card-title">Pemesanan Table</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            id="basic-datatables"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama User</th>
                                                    <th scope="col">Tipe Lapangan</th>
                                                    <th scope="col">Tanggal Pesanan</th>
                                                    <th scope="col">Jam Mulai</th>
                                                    <th scope="col">Jam Selesai</th>
                                                    <th scope="col">Total Biaya</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $pesananSql = " SELECT tb_pesanan.id_pesanan, tb_user.nama, tb_lapangan.tipe_lapangan, tb_pesanan.tanggal_pesanan, tb_pesanan.jam_mulai, tb_pesanan.jam_selesai, tb_pesanan.total_biaya, tb_pesanan.status FROM tb_pesanan JOIN tb_user ON tb_pesanan.id_user = tb_user.id_user JOIN tb_lapangan ON tb_pesanan.id_lapangan = tb_lapangan.id_lapangan ";
                                                $pesananRes = mysqli_query($conn, $pesananSql);

                                                $pesananCount = 1;
                                                while ($pesananData = mysqli_fetch_assoc($pesananRes)) {
                                                    $id_pesanan = $pesananData['id_pesanan'];
                                                    $nama = $pesananData['nama']; // Nama user dari tabel tb_user
                                                    $tipe_lapangan = $pesananData['tipe_lapangan']; // Tipe lapangan dari tabel tb_lapangan
                                                    $tanggal_pesanan = $pesananData['tanggal_pesanan'];
                                                    $jam_mulai = $pesananData['jam_mulai'];
                                                    $jam_selesai = $pesananData['jam_selesai'];
                                                    $total_biaya = $pesananData['total_biaya'];
                                                    $status = $pesananData['status'];
                                                ?>
                                                    <tr>
                                                        <td><?= $pesananCount++ ?></td>
                                                        <td><?= $nama ?></td>
                                                        <td><?= $tipe_lapangan ?></td>
                                                        <td><?= $tanggal_pesanan ?></td>
                                                        <td><?= $jam_mulai ?></td>
                                                        <td><?= $jam_selesai ?></td>
                                                        <td>Rp. <?= $total_biaya ?></td>
                                                        <td><?= $status ?></td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editRowModal<?= $id_pesanan; ?>"
                                                                    title=""
                                                                    class="btn btn-link btn-primary btn-lg"
                                                                    data-original-title="Edit Task">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>

                                                                <form method="post">
                                                                    <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
                                                                    <input type="hidden" name="status" value="Sudah Main">
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-link btn-success btn-lg"
                                                                        name="statusPesanan">
                                                                        <i class="fa fa-check-circle"></i>
                                                                    </button>
                                                                </form>
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteRowModal<?= $id_pesanan ?>"
                                                                    title=""
                                                                    class="btn btn-link btn-danger"
                                                                    data-original-title="Remove">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- modal edit -->
                                                    <div
                                                        class="modal fade"
                                                        id="editRowModal<?= $id_pesanan ?>"
                                                        tabindex="-1"
                                                        role="dialog"
                                                        aria-labelledby="addRowModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title" id="addRowModalLabel">
                                                                        <span class="fw-mediumbold">Edit</span>
                                                                        <span class="fw-light"> Data</span>
                                                                    </h5>
                                                                    <button
                                                                        type="button"
                                                                        class="close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form method="post">
                                                                        <div class="row">
                                                                            <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan) ?>">
                                                                            <input type="hidden" name="id_user" value="<?= htmlspecialchars($id_user) ?>">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="nama_lapangan">Nama</label>
                                                                                    <input
                                                                                        name="nama"
                                                                                        type="text"
                                                                                        id="nama"
                                                                                        value="<?= htmlspecialchars($nama) ?>" readonly
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="tipe_lapangan">Tipe Lapangan</label>
                                                                                    <select name="id_lapangan" id="tipe_lapangan" class="form-control">
                                                                                        <option value="">--Pilih Lapangan--</option>
                                                                                        <?php foreach ($lapanganOptions as $lapangan): ?>
                                                                                            <option value="<?= htmlspecialchars($lapangan['id_lapangan']) ?>" <?= (isset($tipe_lapangan) && $tipe_lapangan == $lapangan['tipe_lapangan']) ? 'selected' : '' ?>>
                                                                                                <?= htmlspecialchars($lapangan['tipe_lapangan'], ENT_QUOTES) ?>
                                                                                            </option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="tanggal_pesanan">Tanggal Pemesanan</label>
                                                                                    <input
                                                                                        name="tanggal_pesanan"
                                                                                        type="date"
                                                                                        value="<?= htmlspecialchars($tanggal_pesanan) ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="jam_mulai">Jam Mulai</label>
                                                                                    <input
                                                                                        name="jam_mulai"
                                                                                        type="time"
                                                                                        value="<?= htmlspecialchars($jam_mulai) ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="jam_selesai">Jam Selesai</label>
                                                                                    <input
                                                                                        name="jam_selesai"
                                                                                        type="time"
                                                                                        value="<?= htmlspecialchars($jam_selesai) ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer border-0">
                                                                            <button type="submit" name="editDataPesanan" class="btn btn-primary">
                                                                                Update
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- modal delete -->
                                                    <div
                                                        class="modal fade"
                                                        id="deleteRowModal<?= $id_pesanan ?>"
                                                        tabindex="-1"
                                                        role="dialog"
                                                        aria-labelledby="addRowModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title" id="addRowModalLabel">
                                                                        <span class="fw-mediumbold">Delete</span>
                                                                        <span class="fw-light"> Data</span>
                                                                    </h5>
                                                                    <button
                                                                        type="button"
                                                                        class="close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <form method="post">
                                                                            <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">

                                                                            <p>Apakah anda yakin mau menghapus <?= $nama ?> ?</p>
                                                                            <div class="modal-footer border-0">
                                                                                <button
                                                                                    type="submit"
                                                                                    name="hapusPesanan"
                                                                                    class="btn btn-primary">
                                                                                    Add
                                                                                </button>
                                                                                <button
                                                                                    type="button"
                                                                                    class="btn btn-danger"
                                                                                    data-bs-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
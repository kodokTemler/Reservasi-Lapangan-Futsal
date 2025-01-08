<?php
include '../controller/CrudLapangan.php';

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
                                <a href="dashboard.php">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="lapangan.php?active=lapangan">Daftar Lapangan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="card-title">Lapangan Table</div>
                                    <button
                                        class="btn btn-primary btn-round ms-auto"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addRowModal">
                                        <i class="fa fa-plus"></i>
                                        Add Lapangan
                                    </button>
                                </div>

                                <!-- Modal Tambah Lapangan -->
                                <div
                                    class="modal fade"
                                    id="addRowModal"
                                    tabindex="-1"
                                    role="dialog"
                                    aria-labelledby="addRowModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title" id="addRowModalLabel">
                                                    <span class="fw-mediumbold">Tambah</span>
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
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label for="nama_lapangan">Nama Lapangan</label>
                                                                <input
                                                                    name="nama_lapangan"
                                                                    type="text"
                                                                    id="nama_lapangan"
                                                                    class="form-control"
                                                                    required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group form-group-default">
                                                                <label for="tipe_lapangan">Tipe Lapangan</label>
                                                                <select name="tipe_lapangan" id="tipe_lapangan" class="form-control" required>
                                                                    <option value="">--Pilih Lapangan--</option>
                                                                    <option value="Semen">Semen</option>
                                                                    <option value="Rumput Sintetis">Rumput Sintetis</option>
                                                                    <option value="Vinyl">Vinyl</option>
                                                                    <option value="Parquette">Parquette</option>
                                                                    <option value="Parket Kayu">Parket Kayu</option>
                                                                    <option value="Karpet Plastik">Karpet Plastik</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group form-group-default">
                                                                <label for="lokasi_lapangan">Lokasi Lapangan</label>
                                                                <input
                                                                    name="lokasi"
                                                                    type="text"
                                                                    id="lokasi"
                                                                    class="form-control"
                                                                    required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group form-group-default">
                                                                <label for="harga_per_jam">Harga Perjam</label>
                                                                <input
                                                                    name="harga_per_jam"
                                                                    type="text"
                                                                    id="harga_per_jam"
                                                                    class="form-control"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group form-group-default">
                                                                <label for="status_lapangan">Status Lapangan</label>
                                                                <select name="status_lapangan" id="status_lapangan" class="form-control" required>
                                                                    <option value="">--Pilih Status Lapangan--</option>
                                                                    <option value="Tersedia">Tersedia</option>
                                                                    <option value="DiPesan">DiPesan</option>
                                                                    <option value="Pemeliharaan">Pemeliharaan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label for="gambar_lapangan">Gambar Lapangan</label>
                                                                <input
                                                                    name="gambar_lapangan"
                                                                    type="file"
                                                                    id="gambar_lapangan"
                                                                    class="form-control"
                                                                    required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="submit" name="tambahDataLapangan" class="btn btn-primary">
                                                            Add
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
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            id="basic-datatables"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama Lapangan</th>
                                                    <th scope="col">Tipe</th>
                                                    <th scope="col">Lokasi Lapangan</th>
                                                    <th scope="col">Harga Perjam</th>
                                                    <th scope="col">Status Lapangan</th>
                                                    <th scope="col">Gambar Lapangan</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $lapanganSql = "SELECT * FROM tb_lapangan";
                                                $lapanganRes = mysqli_query($conn, $lapanganSql);
                                                $lapanganCount = 1;
                                                while ($lapanganData = mysqli_fetch_assoc($lapanganRes)) {
                                                    $id_lapangan = $lapanganData['id_lapangan'];
                                                    $nama_lapangan = $lapanganData['nama_lapangan'];
                                                    $tipe_lapangan = $lapanganData['tipe_lapangan'];
                                                    $lokasi = $lapanganData['lokasi'];
                                                    $harga_per_jam = $lapanganData['harga_per_jam'];
                                                    $status_lapangan = $lapanganData['status_lapangan'];
                                                    $gambar_lapangan = $lapanganData['gambar_lapangan'];
                                                ?>
                                                    <tr>
                                                        <td><?= $lapanganCount++ ?></td>
                                                        <td><?= $nama_lapangan ?></td>
                                                        <td><?= $tipe_lapangan ?></td>
                                                        <td><?= $lokasi ?></td>
                                                        <td>Rp. <?= $harga_per_jam ?></td>
                                                        <td><?= $status_lapangan ?></td>
                                                        <td><img src="assets/img/lapangan/<?= $gambar_lapangan ?>" alt="<?= $nama_lapangan ?>"
                                                                style="width: 100px; height: auto" /></td>
                                                        <td>
                                                            <div class="form-button-action d-flex justify-content-center">
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editRowModal<?= $id_lapangan; ?>"
                                                                    title=""
                                                                    class="btn btn-link btn-primary btn-lg"
                                                                    data-original-title="Edit Task">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteRowModal<?= $id_lapangan ?>"
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
                                                        id="editRowModal<?= $id_lapangan ?>"
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
                                                                    <form method="post" enctype="multipart/form-data">
                                                                        <div class="row">
                                                                            <input type="hidden" name="id_lapangan" value="<?= $id_lapangan ?>">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="nama_lapangan">Nama Lapangan</label>
                                                                                    <input
                                                                                        name="nama_lapangan"
                                                                                        type="text"
                                                                                        id="nama_lapangan"
                                                                                        value="<?= $nama_lapangan ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="tipe_lapangan">Tipe Lapangan</label>
                                                                                    <select name="tipe_lapangan" id="tipe_lapangan" class="form-control">
                                                                                        <option value="" disabled>--Pilih Lapangan--</option>
                                                                                        <option value="Rumput" <?= $tipe_lapangan == 'Rumput' ? 'selected' : '' ?>>Rumput</option>
                                                                                        <option value="Rumput Sintetis" <?= $tipe_lapangan == 'Rumput Sintetis' ? 'selected' : '' ?>>Rumput Sintetis</option>
                                                                                        <option value="Vinyl" <?= $tipe_lapangan == 'Vinyl' ? 'selected' : '' ?>>Vinyl</option>
                                                                                        <option value="Parquette" <?= $tipe_lapangan == 'Parquette' ? 'selected' : '' ?>>Parquette</option>
                                                                                        <option value="Parket Kayu" <?= $tipe_lapangan == 'Parket Kayu' ? 'selected' : '' ?>>Parket Kayu</option>
                                                                                        <option value="Karpet Plastik" <?= $tipe_lapangan == 'Karpet Plastik' ? 'selected' : '' ?>>Karpet Plastik</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="lokasi_lapangan">Lokasi Lapangan</label>
                                                                                    <input
                                                                                        name="lokasi"
                                                                                        type="text"
                                                                                        id="lokasi"
                                                                                        value="<?= $lokasi ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="harga_per_jam">Harga Perjam</label>
                                                                                    <input
                                                                                        name="harga_per_jam"
                                                                                        type="text"
                                                                                        id="harga_per_jam"
                                                                                        value="<?= $harga_per_jam ?>"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="status_lapangan">Status Lapangan</label>
                                                                                    <select name="status_lapangan" id="status_lapangan" class="form-control">
                                                                                        <option value="">--Pilih Status Lapangan--</option>
                                                                                        <option value="Tersedia" <?= $status_lapangan == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                                                                        <option value="DiPesan" <?= $status_lapangan == 'DiPesan' ? 'selected' : '' ?>>DiPesan</option>
                                                                                        <option value="Pemeliharaan" <?= $status_lapangan == 'Pemeliharaan' ? 'selected' : '' ?>>Pemeliharaan</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-group form-group-default">
                                                                                    <label for="gambar_lapangan">Gambar Lapangan</label>
                                                                                    <input
                                                                                        name="gambar_lapangan"
                                                                                        type="file"
                                                                                        id="gambar_lapangan"
                                                                                        class="form-control" />
                                                                                    <div class="d-flex justify-content-center">
                                                                                        <img src="assets/img/lapangan/<?= $gambar_lapangan ?>" alt="<?= htmlspecialchars($nama_lapangan) ?>" class="img-fluid mt-3" style="max-width: 150px; height: auto;" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer border-0">
                                                                            <button type="submit" name="editDataLapangan" class="btn btn-primary">
                                                                                Add
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
                                                        id="deleteRowModal<?= $id_lapangan ?>"
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
                                                                            <input type="hidden" name="id_lapangan" value="<?= $id_lapangan ?>">
                                                                            <p>Apakah anda yakin mau menghapus <?= $nama_lapangan ?> ?</p>
                                                                            <div class="modal-footer border-0">
                                                                                <button
                                                                                    type="submit"
                                                                                    name="deleteDataLapangan"
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
<?php
include '../controller/CrudUser.php';

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
                                <a href="user.php?active=user">User</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="card-title">User Table</div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">No Telp</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $userSql = "SELECT * FROM tb_user";
                                            $userRes = mysqli_query($conn, $userSql);
                                            $userCount = 1;
                                            while ($userData = mysqli_fetch_assoc($userRes)) {
                                                $id_user = $userData['id_user'];
                                                $nama = $userData['nama'];
                                                $email = $userData['email'];
                                                $no_telp = $userData['no_telp'];
                                            ?>
                                                <tr>
                                                    <td><?= $userCount++ ?></td>
                                                    <td><?= $nama ?></td>
                                                    <td><?= $email ?></td>
                                                    <td><?= $no_telp ?></td>
                                                    <td>
                                                        <div class="form-button-action d-flex justify-content-center">
                                                            <button
                                                                type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editRowModal<?= $id_user; ?>"
                                                                title=""
                                                                class="btn btn-link btn-primary btn-lg"
                                                                data-original-title="Edit Task">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteRowModal<?= $id_user ?>"
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
                                                    id="editRowModal<?= $id_user ?>"
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
                                                                <p class="small">
                                                                    Create a new row using this form, make sure you fill them all
                                                                </p>
                                                                <div class="row">
                                                                    <form method="post">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group form-group-default">
                                                                                <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                                                                <label>Username</label>
                                                                                <input
                                                                                    name="nama"
                                                                                    type="text"
                                                                                    class="form-control" value="<?= $nama ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group form-group-default">
                                                                                <label>Email</label>
                                                                                <input
                                                                                    name="email"
                                                                                    type="email"
                                                                                    class="form-control" value="<?= $email ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group form-group-default">
                                                                                <label>No Telp</label>
                                                                                <input
                                                                                    name="no_telp"
                                                                                    type="text"
                                                                                    inputmode="numeric"
                                                                                    class="form-control" value="<?= $no_telp ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer border-0">
                                                                            <button
                                                                                type="submit"
                                                                                name="editDataUser"
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

                                                <!-- modal delete -->
                                                <div
                                                    class="modal fade"
                                                    id="deleteRowModal<?= $id_user ?>"
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
                                                                        <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                                                        <p>Apakah anda yakin mau menghapus <?= $nama ?> ?</p>
                                                                        <div class="modal-footer border-0">
                                                                            <button
                                                                                type="submit"
                                                                                name="deleteDataUser"
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
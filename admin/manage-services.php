<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* DELETE SERVICE */
    if (isset($_GET['delid'])) {

        $sid = $_GET['delid'];

        mysqli_query($con, "DELETE FROM tblservices WHERE ID ='$sid'");

        echo "<script>alert('Service deleted successfully');</script>";
        echo "<script>window.location.href='manage-services.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Services | GlamourSoft</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }

        /* MAIN WRAPPER */
        .page-wrapper {
            padding: 100px 25px 30px 305px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .page-wrapper.full-width {
            padding-left: 25px;
        }

        /* TITLE */
        .page-title h2 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-title p {
            color: #777;
        }

        /* CARD */
        .table-card {
            background: #fff;
            border-radius: 22px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        /* TABLE */
        .table thead {
            background: #e91e63;
            color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        /* BUTTONS */
        .btn-edit {
            background: #2196f3;
            color: #fff;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
        }

        .btn-delete {
            background: #f44336;
            color: #fff;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.9;
            color: #fff;
        }

        @media (max-width: 991px) {
            .page-wrapper {
                padding: 100px 15px 20px 15px;
            }
        }

    </style>

</head>

<body>

<!-- HEADER -->
<?php include_once('includes/header.php'); ?>

<!-- SIDEBAR -->
<?php include_once('includes/sidebar.php'); ?>

<!-- CONTENT -->
<div class="page-wrapper" id="main-content">

    <!-- TITLE -->
    <div class="page-title mb-4">

        <h2>
            Manage Services
        </h2>

        <p>
            View, edit and delete salon services
        </p>

    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <h5 class="mb-3">
            All Services
        </h5>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                $ret = mysqli_query($con, "SELECT * FROM tblservices");
                $cnt = 1;

                while ($row = mysqli_fetch_array($ret)) {
                ?>

                    <tr>

                        <td><?php echo $cnt; ?></td>

                        <td><?php echo $row['ServiceName']; ?></td>

                        <td>Rs. <?php echo $row['Cost']; ?></td>

                        <td><?php echo $row['CreationDate']; ?></td>

                        <td>

                            <a href="edit-services.php?editid=<?php echo $row['ID']; ?>"
                               class="btn-edit">

                                <i class="bi bi-pencil-square"></i>
                                Edit

                            </a>

                            <a href="manage-services.php?delid=<?php echo $row['ID']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Are you sure you want to delete?')">

                                <i class="bi bi-trash"></i>
                                Delete

                            </a>

                        </td>

                    </tr>

                <?php
                    $cnt++;
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- FOOTER -->
<?php include_once('includes/footer.php'); ?>

</body>
</html>
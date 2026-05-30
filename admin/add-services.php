<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {

        $sername = $_POST['sername'];
        $serdesc = $_POST['serdesc'];
        $cost = $_POST['cost'];

        $image = $_FILES["image"]["name"];

        // IMAGE EXTENSION
        $extension = substr($image, strlen($image) - 4, strlen($image));

        // ALLOWED EXTENSIONS
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");

        if (!in_array($extension, $allowed_extensions)) {

            echo "<script>alert('Invalid format. Only JPG / JPEG / PNG / GIF allowed');</script>";

        } else {

            // RENAME IMAGE
            $newimage = md5($image) . time() . $extension;

            // UPLOAD IMAGE
            move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                "images/" . $newimage
            );

            // INSERT
            $query = mysqli_query(
                $con,
                "INSERT INTO tblservices
                (ServiceName, ServiceDescription, Cost, Image)
                VALUES
                ('$sername','$serdesc','$cost','$newimage')"
            );

            if ($query) {

                echo "<script>alert('Service has been added successfully');</script>";
                echo "<script>window.location.href='add-services.php'</script>";

            } else {

                echo "<script>alert('Something went wrong');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Services | GlamourSoft</title>

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

        /* MAIN CONTENT */
        .page-wrapper {
            padding: 100px 25px 30px 305px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .page-wrapper.full-width {
            padding-left: 25px;
        }

        /* PAGE TITLE */
        .page-title {
            margin-bottom: 30px;
        }

        .page-title h2 {
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
        }

        .page-title p {
            color: #777;
            margin: 0;
        }

        /* FORM CARD */
        .form-card {
            background: #fff;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .form-card h4 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #222;
        }

        /* FORM */
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
        }

        .form-control {
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid #ddd;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #e91e63;
        }

        textarea.form-control {
            min-height: 130px;
            resize: none;
        }

        /* BUTTON */
        .submit-btn {
            background: linear-gradient(135deg,#e91e63,#ff4f81);
            border: none;
            color: white;
            padding: 14px 30px;
            border-radius: 14px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(233,30,99,0.25);
        }

        /* MOBILE */
        @media (max-width: 991px) {

            .page-wrapper {
                padding: 100px 15px 20px 15px;
            }

            .form-card {
                padding: 25px;
            }
        }

    </style>

</head>

<body>

    <!-- HEADER -->
    <?php include_once('includes/header.php'); ?>

    <!-- SIDEBAR -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- PAGE CONTENT -->
    <div class="page-wrapper" id="main-content">

        <!-- TITLE -->
        <div class="page-title">

            <h2>
                Add Services
            </h2>

            <p>
                Create and manage parlour services
            </p>

        </div>

        <!-- FORM -->
        <div class="form-card">

            <h4>
                Parlour Service Details
            </h4>

            <form method="post"
                  enctype="multipart/form-data">

                <!-- SERVICE NAME -->
                <div class="mb-4">

                    <label class="form-label">
                        Service Name
                    </label>

                    <input type="text"
                           name="sername"
                           class="form-control"
                           placeholder="Enter service name"
                           required>

                </div>

                <!-- DESCRIPTION -->
                <div class="mb-4">

                    <label class="form-label">
                        Service Description
                    </label>

                    <textarea name="serdesc"
                              class="form-control"
                              placeholder="Write service description..."
                              required></textarea>

                </div>

                <!-- COST -->
                <div class="mb-4">

                    <label class="form-label">
                        Service Cost
                    </label>

                    <input type="text"
                           name="cost"
                           class="form-control"
                           placeholder="Enter service cost"
                           required>

                </div>

                <!-- IMAGE -->
                <div class="mb-4">

                    <label class="form-label">
                        Service Image
                    </label>

                    <input type="file"
                           name="image"
                           class="form-control"
                           required>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                        name="submit"
                        class="submit-btn">

                    <i class="bi bi-plus-circle"></i>
                    Add Service

                </button>

            </form>

        </div>

    </div>

    <!-- FOOTER -->
    <?php include_once('includes/footer.php'); ?>

</body>
</html>
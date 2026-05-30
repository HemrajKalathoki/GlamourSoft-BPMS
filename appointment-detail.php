<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid']==0)) {
    header('location:logout.php');
} else {

?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Appointment Details | GlamourSoft
    </title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Existing CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">

    <style>

        body{
            font-family:'Poppins',sans-serif;
            background:#f8fafc;
            overflow-x:hidden;
        }

        .page-banner{
            background:
            linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
            url('assets/images/b1.jpg') center/cover no-repeat;
            min-height:40vh;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            color:#fff;
        }

        .page-banner h1{
            font-size:3rem;
            font-weight:700;
        }

        .breadcrumb-box{
            background:#fff;
            padding:15px 0;
            border-bottom:1px solid #eee;
        }

        .breadcrumb-box a{
            text-decoration:none;
            color:#e91e63;
            font-weight:500;
        }

        .appointment-card{
            background:#fff;
            border-radius:24px;
            box-shadow:0 10px 40px rgba(0,0,0,0.08);
            overflow:hidden;
        }

        .card-header-custom{
            background:linear-gradient(135deg,#e91e63,#ff4f8b);
            color:#fff;
            padding:35px;
        }

        .card-header-custom h3{
            margin:0;
            font-weight:700;
        }

        .appointment-body{
            padding:40px;
        }

        .info-box{
            background:#f8fafc;
            border-radius:16px;
            padding:20px;
            height:100%;
            border:1px solid #edf2f7;
            transition:.3s;
        }

        .info-box:hover{
            transform:translateY(-4px);
            box-shadow:0 10px 25px rgba(0,0,0,0.06);
        }

        .info-label{
            font-size:14px;
            color:#6b7280;
            margin-bottom:8px;
        }

        .info-value{
            font-size:17px;
            font-weight:600;
            color:#111827;
        }

        .status-badge{
            padding:10px 18px;
            border-radius:50px;
            font-size:14px;
            font-weight:600;
            display:inline-block;
        }

        .status-pending{
            background:#fff7ed;
            color:#ea580c;
        }

        .status-selected{
            background:#ecfdf5;
            color:#059669;
        }

        .status-rejected{
            background:#fef2f2;
            color:#dc2626;
        }

        .summary-card{
            background:#fff;
            border-radius:20px;
            padding:25px;
            box-shadow:0 8px 30px rgba(0,0,0,0.06);
            text-align:center;
        }

        .summary-icon{
            width:70px;
            height:70px;
            background:#fff0f5;
            color:#e91e63;
            border-radius:20px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:30px;
            margin:auto;
            margin-bottom:20px;
        }

        #movetop{
            position:fixed;
            bottom:25px;
            right:25px;
            width:50px;
            height:50px;
            border:none;
            border-radius:50%;
            background:#e91e63;
            color:#fff;
            display:none;
            z-index:999;
        }

        @media(max-width:768px){

            .page-banner h1{
                font-size:2rem;
            }

            .appointment-body{
                padding:25px;
            }

        }

    </style>

</head>

<body>

<?php include_once('includes/header.php'); ?>

<!-- PAGE BANNER -->
<section class="page-banner">

    <div class="container">

        <h1>
            Appointment Details
        </h1>

        <p class="mt-3">
            GlamourSoft Beauty Management System
        </p>

    </div>

</section>

<!-- BREADCRUMB -->
<div class="breadcrumb-box">

    <div class="container">

        <a href="index.php">Home</a>
        /
        <span>Appointment Details</span>

    </div>

</div>

<!-- APPOINTMENT DETAILS -->
<section class="py-5">

    <div class="container">

        <?php

        $cid=$_GET['aptnumber'];

        $ret=mysqli_query($con,"SELECT 
            tbluser.FirstName,
            tbluser.LastName,
            tbluser.Email,
            tbluser.MobileNumber,
            tblbook.ID as bid,
            tblbook.AptNumber,
            tblbook.AptDate,
            tblbook.AptTime,
            tblbook.Message,
            tblbook.BookingDate,
            tblbook.Remark,
            tblbook.Status,
            tblbook.RemarkDate
            FROM tblbook
            JOIN tbluser
            ON tbluser.ID=tblbook.UserID
            WHERE tblbook.AptNumber='$cid'");

        while ($row=mysqli_fetch_array($ret)) {

        ?>

        <!-- SUMMARY CARDS -->
        <div class="row g-4 mb-4">

            <div class="col-md-4">

                <div class="summary-card">

                    <div class="summary-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <h5>Appointment Number</h5>

                    <p class="mb-0 fw-bold text-dark">
                        #<?php echo htmlentities($row['AptNumber']); ?>
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="summary-card">

                    <div class="summary-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <h5>Appointment Time</h5>

                    <p class="mb-0 fw-bold text-dark">
                        <?php echo htmlentities($row['AptTime']); ?>
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="summary-card">

                    <div class="summary-icon">
                        <i class="bi bi-patch-check"></i>
                    </div>

                    <h5>Status</h5>

                    <?php

                    $status = $row['Status'];

                    if($status==""){
                        echo "<span class='status-badge status-pending'>Pending</span>";
                    }
                    elseif($status=="Selected"){
                        echo "<span class='status-badge status-selected'>Confirmed</span>";
                    }
                    elseif($status=="Rejected"){
                        echo "<span class='status-badge status-rejected'>Rejected</span>";
                    }

                    ?>

                </div>

            </div>

        </div>

        <!-- MAIN CARD -->
        <div class="appointment-card">

            <div class="card-header-custom">

                <h3>
                    Customer Appointment Information
                </h3>

            </div>

            <div class="appointment-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Customer Name
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['FirstName']); ?>
                                <?php echo htmlentities($row['LastName']); ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Email Address
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['Email']); ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Mobile Number
                            </div>

                            <div class="info-value">
                                +977 <?php echo htmlentities($row['MobileNumber']); ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Appointment Date
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['AptDate']); ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Booking Date
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['BookingDate']); ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-label">
                                Appointment Status
                            </div>

                            <div class="info-value">

                                <?php

                                if($status==""){
                                    echo "<span class='status-badge status-pending'>Pending</span>";
                                }
                                elseif($status=="Selected"){
                                    echo "<span class='status-badge status-selected'>Confirmed</span>";
                                }
                                elseif($status=="Rejected"){
                                    echo "<span class='status-badge status-rejected'>Rejected</span>";
                                }

                                ?>

                            </div>

                        </div>

                    </div>

                    <?php if($row['Message']!=""){ ?>

                    <div class="col-12">

                        <div class="info-box">

                            <div class="info-label">
                                Customer Message
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['Message']); ?>
                            </div>

                        </div>

                    </div>

                    <?php } ?>

                    <?php if($row['Remark']!=""){ ?>

                    <div class="col-12">

                        <div class="info-box">

                            <div class="info-label">
                                Admin Remark
                            </div>

                            <div class="info-value">
                                <?php echo htmlentities($row['Remark']); ?>
                            </div>

                        </div>

                    </div>

                    <?php } ?>

                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</section>

<?php include_once('includes/footer.php'); ?>

<!-- MOVE TOP -->
<button onclick="topFunction()" id="movetop">

    <i class="bi bi-arrow-up"></i>

</button>

<script>

window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {

    if (document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20) {

        document.getElementById("movetop").style.display = "block";

    } else {

        document.getElementById("movetop").style.display = "none";

    }

}

function topFunction() {

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

}

</script>

</body>
</html>

<?php } ?>

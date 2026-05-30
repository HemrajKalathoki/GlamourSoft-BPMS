<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
}

/* TOTAL CUSTOMERS */
$query1 = mysqli_query($con, "SELECT * FROM tbluser");
$totalcust = mysqli_num_rows($query1);

/* TOTAL APPOINTMENTS */
$query2 = mysqli_query($con, "SELECT * FROM tblbook");
$totalappointment = mysqli_num_rows($query2);

/* ACCEPTED APPOINTMENTS */
$query3 = mysqli_query($con, "SELECT * FROM tblbook WHERE Status='Selected'");
$totalaccept = mysqli_num_rows($query3);

/* REJECTED APPOINTMENTS */
$query4 = mysqli_query($con, "SELECT * FROM tblbook WHERE Status='Rejected'");
$totalreject = mysqli_num_rows($query4);

/* SERVICES */
$query5 = mysqli_query($con, "SELECT * FROM tblservices");
$totalservices = mysqli_num_rows($query5);

/* TODAY SALES */
$todaysale = 0;

$query6 = mysqli_query($con, "
    SELECT tblservices.Cost
    FROM tblinvoice
    JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
    WHERE DATE(PostingDate)=CURDATE()
");

while ($row = mysqli_fetch_array($query6)) {
    $todaysale += $row['Cost'];
}

/* YESTERDAY SALES */
$yesterdaysale = 0;

$query7 = mysqli_query($con, "
    SELECT tblservices.Cost
    FROM tblinvoice
    JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
    WHERE DATE(PostingDate)=CURDATE()-1
");

while ($row7 = mysqli_fetch_array($query7)) {
    $yesterdaysale += $row7['Cost'];
}

/* LAST 7 DAYS SALES */
$sevendayssale = 0;

$query8 = mysqli_query($con, "
    SELECT tblservices.Cost
    FROM tblinvoice
    JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
    WHERE DATE(PostingDate) >= (DATE(NOW()) - INTERVAL 7 DAY)
");

while ($row8 = mysqli_fetch_array($query8)) {
    $sevendayssale += $row8['Cost'];
}

/* TOTAL SALES */
$totalsale = 0;

$query9 = mysqli_query($con, "
    SELECT tblservices.Cost
    FROM tblinvoice
    JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
");

while ($row9 = mysqli_fetch_array($query9)) {
    $totalsale += $row9['Cost'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | GlamourSoft</title>

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

      
	.dashboard-wrapper {
		width: 100%;
		min-height: 100vh;
		padding: 100px 25px 30px 305px;
		transition: all 0.3s ease;
	}

	.dashboard-wrapper.full-width {
		padding-left: 25px !important;
	}

        /* PAGE TITLE */
        .page-title {
            margin-bottom: 30px;
        }

        .page-title h2 {
            font-weight: 700;
            color: #222;
        }

        .page-title p {
            color: #777;
            margin: 0;
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 22px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            height: 100%;
        }

	  .stat-card {
	animation: fadeUp 0.5s ease;
	}

	@keyframes fadeUp {

	from {
		opacity: 0;
		transform: translateY(15px);
	}

	to {
		opacity: 1;
		transform: translateY(0);
	}
	}

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 65px;
            height: 65px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 20px;
        }

        .bg-pink {
            background: linear-gradient(135deg,#e91e63,#ff4f81);
        }

        .bg-blue {
            background: linear-gradient(135deg,#2196f3,#42a5f5);
        }

        .bg-green {
            background: linear-gradient(135deg,#4caf50,#66bb6a);
        }

        .bg-red {
            background: linear-gradient(135deg,#f44336,#ef5350);
        }

        .bg-purple {
            background: linear-gradient(135deg,#673ab7,#7e57c2);
        }

        .bg-orange {
            background: linear-gradient(135deg,#ff9800,#ffa726);
        }

        .card-title-small {
            color: #888;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 700;
            color: #222;
            margin-bottom: 0;
        }

        .card-label {
            color: #555;
            font-weight: 500;
        }

        @media (max-width: 991px) {

            .dashboard-wrapper {
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

    <!-- PAGE CONTENT -->
    <div class="dashboard-wrapper">

        <!-- PAGE TITLE -->
        <div class="page-title">

            <h2>
                Dashboard
            </h2>

            <p>
                Welcome back, Admin 👋
            </p>

        </div>

        <!-- STATS -->
        <div class="row g-4">

            <!-- CUSTOMERS -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-pink">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="card-title-small">
                        Total
                    </div>

                    <h3 class="card-value">
                        <?php echo $totalcust; ?>
                    </h3>

                    <div class="card-label">
                        Customers
                    </div>

                </div>

            </div>

            <!-- APPOINTMENTS -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-blue">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>

                    <div class="card-title-small">
                        Total
                    </div>

                    <h3 class="card-value">
                        <?php echo $totalappointment; ?>
                    </h3>

                    <div class="card-label">
                        Appointments
                    </div>

                </div>

            </div>

            <!-- ACCEPTED -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <div class="card-title-small">
                        Total
                    </div>

                    <h3 class="card-value">
                        <?php echo $totalaccept; ?>
                    </h3>

                    <div class="card-label">
                        Accepted Appointments
                    </div>

                </div>

            </div>

            <!-- REJECTED -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-red">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>

                    <div class="card-title-small">
                        Total
                    </div>

                    <h3 class="card-value">
                        <?php echo $totalreject; ?>
                    </h3>

                    <div class="card-label">
                        Rejected Appointments
                    </div>

                </div>

            </div>

            <!-- SERVICES -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-purple">
                        <i class="bi bi-scissors"></i>
                    </div>

                    <div class="card-title-small">
                        Total
                    </div>

                    <h3 class="card-value">
                        <?php echo $totalservices; ?>
                    </h3>

                    <div class="card-label">
                        Services
                    </div>

                </div>

            </div>

            <!-- TODAY SALES -->
            <div class="col-lg-4 col-md-6">

                <div class="stat-card">

                    <div class="stat-icon bg-orange">
                        <i class="bi bi-currency-dollar"></i>
                    </div>

                    <div class="card-title-small">
                        Today
                    </div>

                    <h3 class="card-value">
                        Rs. <?php echo $todaysale; ?>
                    </h3>

                    <div class="card-label">
                        Sales
                    </div>

                </div>

            </div>

            <!-- YESTERDAY SALES -->
            <div class="col-lg-6">

                <div class="stat-card">

                    <div class="stat-icon bg-blue">
                        <i class="bi bi-graph-down-arrow"></i>
                    </div>

                    <div class="card-title-small">
                        Yesterday
                    </div>

                    <h3 class="card-value">
                        Rs. <?php echo $yesterdaysale; ?>
                    </h3>

                    <div class="card-label">
                        Sales
                    </div>

                </div>

            </div>

            <!-- LAST 7 DAYS -->
            <div class="col-lg-6">

                <div class="stat-card">

                    <div class="stat-icon bg-pink">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>

                    <div class="card-title-small">
                        Last 7 Days
                    </div>

                    <h3 class="card-value">
                        Rs. <?php echo $sevendayssale; ?>
                    </h3>

                    <div class="card-label">
                        Sales
                    </div>

                </div>

            </div>

            <!-- TOTAL SALES -->
            <div class="col-12">

                <div class="stat-card">

                    <div class="stat-icon bg-green">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div class="card-title-small">
                        Overall
                    </div>

                    <h3 class="card-value">
                        Rs. <?php echo $totalsale; ?>
                    </h3>

                    <div class="card-label">
                        Total Revenue
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <?php include_once('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
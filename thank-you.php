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

    <title>Beauty Parlour Management System | Appointment Confirmation</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff0f5, #f8f9ff);
            min-height: 100vh;
        }

        .thankyou-card{
            border: none;
            border-radius: 22px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .thankyou-header{
            background: linear-gradient(135deg, #ff4d6d, #ff758f);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .check-icon{
            width: 80px;
            height: 80px;
            background: white;
            color: #ff4d6d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 15px auto;
        }

        .appointment-box{
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            color: #333;
        }

        .btn-custom{
            background: #ff4d6d;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            border: none;
        }

        .btn-custom:hover{
            background: #e63956;
            color: white;
        }
    </style>

</head>

<body>

<?php include_once('includes/header.php');?>

<!-- HERO / CONTENT -->
<section class="py-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="card thankyou-card">

                    <!-- Header -->
                    <div class="thankyou-header">
                        <div class="check-icon">
                            ✔
                        </div>

                        <h2 class="mb-2">Appointment Confirmed</h2>
                        <p class="mb-0">Thank you for choosing our beauty service</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body text-center p-5">

                        <h5 class="mb-3">Your booking was successful 🎉</h5>

                        <p class="text-muted mb-4">
                            We have received your appointment request. Our team will contact you soon for confirmation.
                        </p>

                        <div class="appointment-box mb-4">
                            Your Appointment No:
                            <div class="fs-4 text-danger mt-2">
                                <?php echo $_SESSION['aptno']; ?>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">

                            <a href="index.php" class="btn btn-custom">
                                Go to Home
                            </a>

                            <a href="book-appointment.php" class="btn btn-outline-dark rounded-pill px-4">
                                Book Another
                            </a>

                        </div>

                        <hr class="my-4">

                        <small class="text-muted">
                            Location: Ghorahi, Dang, Nepal 🇳🇵
                        </small>

                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php include_once('includes/footer.php');?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php } ?>
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login']))
{
    $emailcon = $_POST['emailcont'];
    $password = md5($_POST['password']);

    $query = mysqli_query($con,"SELECT ID 
    FROM tbluser 
    WHERE (Email='$emailcon' || MobileNumber='$emailcon') 
    && Password='$password'");

    $ret = mysqli_fetch_array($query);

    if($ret > 0){

        $_SESSION['bpmsuid'] = $ret['ID'];

        echo "<script>
        alert('Login Successful');
        window.location.href='index.php';
        </script>";

    } else {

        echo "<script>
        alert('Invalid Email or Password');
        </script>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Login | GlamourSoft Beauty Management
    </title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Existing CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        body{
            font-family:'Poppins',sans-serif;
            background:#f8fafc;
            overflow-x:hidden;
        }

        .login-section{
            min-height:100vh;
            background:
            linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
            url('assets/images/b1.jpg') center/cover no-repeat;
            display:flex;
            align-items:center;
            padding:120px 0 80px;
        }

        .login-card{
            background:rgba(255,255,255,0.12);
            backdrop-filter:blur(14px);
            border:1px solid rgba(255,255,255,0.2);
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,0.25);
        }

        .login-left{
            padding:60px;
            color:#fff;
            height:100%;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .brand-title{
            font-size:3rem;
            font-weight:700;
            line-height:1.2;
            margin-bottom:20px;
        }

        .brand-subtitle{
            color:#f3f4f6;
            line-height:1.9;
            margin-bottom:30px;
        }

        .feature-item{
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:20px;
        }

        .feature-icon{
            width:50px;
            height:50px;
            border-radius:14px;
            background:rgba(255,255,255,0.15);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
        }

        .login-right{
            background:#fff;
            padding:60px 45px;
            height:100%;
        }

        .login-title{
            font-size:2rem;
            font-weight:700;
            color:#111827;
            margin-bottom:10px;
        }

        .login-desc{
            color:#6b7280;
            margin-bottom:35px;
        }

        .form-label{
            font-weight:500;
            margin-bottom:10px;
            color:#374151;
        }

        .form-control{
            height:55px;
            border-radius:14px;
            border:1px solid #e5e7eb;
            padding-left:18px;
            font-size:15px;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:#e91e63;
        }

        .login-btn{
            width:100%;
            height:55px;
            border:none;
            border-radius:14px;
            background:linear-gradient(135deg,#e91e63,#ff4f8b);
            color:#fff;
            font-weight:600;
            transition:.3s;
        }

        .login-btn:hover{
            transform:translateY(-2px);
            box-shadow:0 10px 25px rgba(233,30,99,0.35);
        }

        .link-style{
            text-decoration:none;
            color:#e91e63;
            font-weight:500;
        }

        .contact-card{
            background:#fff;
            border-radius:18px;
            padding:18px;
            display:flex;
            gap:15px;
            align-items:center;
            margin-bottom:20px;
            box-shadow:0 6px 20px rgba(0,0,0,0.05);
        }

        .contact-icon{
            width:50px;
            height:50px;
            background:#fff0f5;
            color:#e91e63;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
        }

        .contact-card h6{
            margin-bottom:5px;
            font-weight:600;
        }

        .contact-card p{
            margin:0;
            color:#6b7280;
            font-size:14px;
        }

        @media(max-width:991px){

            .login-left{
                padding:40px;
            }

            .login-right{
                padding:40px 30px;
            }

            .brand-title{
                font-size:2.2rem;
            }

        }

    </style>

</head>

<body>

<?php include_once('includes/header.php'); ?>

<!-- LOGIN SECTION -->
<section class="login-section">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-11">

                <div class="login-card">

                    <div class="row g-0">

                        <!-- LEFT SIDE -->
                        <div class="col-lg-6">

                            <div class="login-left">

                                <h1 class="brand-title">
                                    GlamourSoft <br>
                                    Beauty Management
                                </h1>

                                <p class="brand-subtitle">

                                    Modern salon and beauty parlour management
                                    system from Ghorahi, Dang, Nepal.

                                    Manage appointments, customers,
                                    services, billing, and beauty operations
                                    professionally.

                                </p>

                                <div class="feature-item">

                                    <div class="feature-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>

                                    <div>
                                        Smart Appointment Booking
                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">
                                        <i class="bi bi-people"></i>
                                    </div>

                                    <div>
                                        Customer Management System
                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>

                                    <div>
                                        Billing & Invoice Management
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-lg-6">

                            <div class="login-right">

                                <h2 class="login-title">
                                    Welcome Back
                                </h2>

                                <p class="login-desc">
                                    Login to continue your beauty management journey.
                                </p>

                                <!-- CONTACT INFO -->
                                <?php

                                $ret=mysqli_query($con,"select * from tblpage where PageType='contactus'");

                                while ($row=mysqli_fetch_array($ret)) {

                                ?>

                                <div class="contact-card">

                                    <div class="contact-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>

                                    <div>

                                        <h6>Call Us</h6>

                                        <p>
                                            +977 <?php echo htmlentities($row['MobileNumber']); ?>
                                        </p>

                                    </div>

                                </div>

                                <div class="contact-card">

                                    <div class="contact-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>

                                    <div>

                                        <h6>Email Address</h6>

                                        <p>
                                            <?php echo htmlentities($row['Email']); ?>
                                        </p>

                                    </div>

                                </div>

                                <div class="contact-card">

                                    <div class="contact-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>

                                    <div>

                                        <h6>Location</h6>

                                        <p>
                                            Ghorahi, Dang, Nepal
                                        </p>

                                    </div>

                                </div>

                                <?php } ?>

                                <!-- LOGIN FORM -->
                                <form method="post" class="mt-4">

                                    <div class="mb-4">

                                        <label class="form-label">
                                            Email or Mobile Number
                                        </label>

                                        <input
                                        type="text"
                                        class="form-control"
                                        name="emailcont"
                                        placeholder="Enter email or mobile number"
                                        required>

                                    </div>

                                    <div class="mb-4">

                                        <label class="form-label">
                                            Password
                                        </label>

                                        <input
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="Enter password"
                                        required>

                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">

                                        <div class="form-check">

                                            <input class="form-check-input"
                                            type="checkbox"
                                            id="remember">

                                            <label class="form-check-label"
                                            for="remember">

                                                Remember Me

                                            </label>

                                        </div>

                                        <a href="forgot-password.php"
                                        class="link-style">

                                            Forgot Password?

                                        </a>

                                    </div>

                                    <button
                                    type="submit"
                                    name="login"
                                    class="login-btn">

                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Login Now

                                    </button>

                                </form>

                                <div class="text-center mt-4">

                                    <p class="mb-0">

                                        Don't have an account?

                                        <a href="signup.php"
                                        class="link-style">

                                            Create Account

                                        </a>

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include_once('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        About Us | GlamourSoft Beauty Management
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
            overflow-x:hidden;
            background:#fff;
        }

        .page-banner{
            background:
            linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
            url('assets/images/b2.jpg') center/cover no-repeat;
            min-height:50vh;
            display:flex;
            align-items:center;
            color:#fff;
            text-align:center;
        }

        .page-banner h1{
            font-size:3.5rem;
            font-weight:700;
        }

        .breadcrumb-custom{
            background:#fff5f8;
            padding:15px 0;
        }

        .breadcrumb-custom a{
            text-decoration:none;
            color:#e91e63;
            font-weight:500;
        }

        .section-padding{
            padding:90px 0;
        }

        .section-title{
            font-size:2.5rem;
            font-weight:700;
            margin-bottom:20px;
            color:#111827;
        }

        .about-image{
            border-radius:25px;
            overflow:hidden;
            box-shadow:0 10px 35px rgba(0,0,0,0.12);
        }

        .about-image img{
            width:100%;
            transition:.5s;
        }

        .about-image:hover img{
            transform:scale(1.05);
        }

        .service-badge{
            background:#fff0f5;
            color:#e91e63;
            padding:12px 18px;
            border-radius:12px;
            display:flex;
            align-items:center;
            gap:10px;
            font-weight:500;
            transition:.3s;
        }

        .service-badge:hover{
            background:#e91e63;
            color:#fff;
            transform:translateY(-4px);
        }

        .stats-card{
            background:#fff;
            border-radius:20px;
            padding:35px;
            text-align:center;
            box-shadow:0 8px 30px rgba(0,0,0,0.08);
            transition:.3s;
            height:100%;
        }

        .stats-card:hover{
            transform:translateY(-8px);
        }

        .stats-card h2{
            color:#e91e63;
            font-size:2.7rem;
            font-weight:700;
        }

        .mission-box{
            background:#111827;
            color:#fff;
            padding:50px;
            border-radius:25px;
        }

        .mission-box p{
            color:#d1d5db;
            line-height:1.9;
        }

        .feature-item{
            display:flex;
            gap:18px;
            margin-bottom:25px;
        }

        .feature-icon{
            width:60px;
            height:60px;
            background:#fff0f5;
            color:#e91e63;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:16px;
            font-size:24px;
            flex-shrink:0;
        }

        .team-card{
            background:#fff;
            border-radius:22px;
            overflow:hidden;
            box-shadow:0 8px 30px rgba(0,0,0,0.08);
            transition:.3s;
            text-align:center;
        }

        .team-card:hover{
            transform:translateY(-8px);
        }

        .team-card img{
            height:320px;
            width:100%;
            object-fit:cover;
        }

        .team-content{
            padding:25px;
        }

        .team-content h5{
            font-weight:600;
        }

        .team-content span{
            color:#e91e63;
            font-size:14px;
        }

        @media(max-width:768px){

            .page-banner h1{
                font-size:2.4rem;
            }

            .section-title{
                font-size:2rem;
            }

            .mission-box{
                padding:30px;
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
            About GlamourSoft
        </h1>

        <p class="mt-3">
            Modern Beauty Management Platform from Ghorahi, Dang, Nepal
        </p>

    </div>

</section>

<!-- BREADCRUMB -->
<div class="breadcrumb-custom">

    <div class="container">

        <a href="index.php">Home</a>
        /
        <span>About Us</span>

    </div>

</div>

<!-- ABOUT SECTION -->
<section class="section-padding">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="about-image">

                    <img src="assets/images/b2.jpg" alt="About">

                </div>

            </div>

            <div class="col-lg-6">

                <h2 class="section-title">
                    Beauty & Confidence Starts Here
                </h2>

                <p class="text-muted mb-4" style="line-height:1.9;">

                    GlamourSoft Beauty Management System is a modern salon
                    management platform developed for beauty parlours,
                    salons, and spa businesses in Nepal.

                    Our system helps manage appointments, customers,
                    beauty services, billing, employees, and reports
                    efficiently.

                </p>

                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="service-badge">
                            <i class="bi bi-scissors"></i>
                            Hair Styling
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="service-badge">
                            <i class="bi bi-stars"></i>
                            Facial Care
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="service-badge">
                            <i class="bi bi-flower1"></i>
                            Bridal Makeup
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="service-badge">
                            <i class="bi bi-heart"></i>
                            Spa & Massage
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ABOUT FROM DATABASE -->
<section class="section-padding bg-light">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <?php
                $ret=mysqli_query($con,"select * from tblpage where PageType='aboutus'");
                while($row=mysqli_fetch_array($ret)){
                ?>

                <h2 class="section-title">
                    <?php echo htmlentities($row['PageTitle']); ?>
                </h2>

                <p class="text-muted" style="line-height:1.9;">
                    <?php echo htmlentities($row['PageDescription']); ?>
                </p>

                <?php } ?>

            </div>

            <div class="col-lg-6">

                <div class="about-image">

                    <img src="assets/images/b3.jpg" alt="Salon">

                </div>

            </div>

        </div>

    </div>

</section>

<!-- STATS -->
<section class="section-padding">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-3">

                <div class="stats-card">

                    <h2>5K+</h2>
                    <p>Happy Customers</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card">

                    <h2>120+</h2>
                    <p>Beauty Services</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card">

                    <h2>15+</h2>
                    <p>Expert Staff</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stats-card">

                    <h2>99%</h2>
                    <p>Customer Satisfaction</p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- MISSION -->
<section class="section-padding">

    <div class="container">

        <div class="mission-box">

            <div class="row g-5 align-items-center">

                <div class="col-lg-6">

                    <h2 class="mb-4">
                        Our Mission
                    </h2>

                    <p>

                        Our mission is to modernize beauty parlour
                        operations in Nepal using technology-driven
                        management solutions.

                        GlamourSoft helps salon owners efficiently manage
                        appointments, inventory, staff, billing, and customer
                        relationships through a user-friendly system.

                    </p>

                </div>

                <div class="col-lg-6">

                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <h5>Appointment Management</h5>
                            <p class="mb-0 text-light">
                                Easy online and offline appointment booking.
                            </p>
                        </div>

                    </div>

                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>
                            <h5>Customer Management</h5>
                            <p class="mb-0 text-light">
                                Maintain customer records and service history.
                            </p>
                        </div>

                    </div>

                    <div class="feature-item">

                        <div class="feature-icon">
                            <i class="bi bi-receipt"></i>
                        </div>

                        <div>
                            <h5>Billing System</h5>
                            <p class="mb-0 text-light">
                                Professional invoice and payment handling.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- TEAM -->
<section class="section-padding bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Our Beauty Experts
            </h2>

            <p class="text-muted">
                Experienced professionals from Ghorahi, Dang
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="team-card">

                    <img src="assets/images/5.jpg" alt="Team">

                    <div class="team-content">

                        <h5>Samjhana Thapa</h5>
                        <span>Senior Hair Stylist</span>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="team-card">

                    <img src="assets/images/6.jpg" alt="Team">

                    <div class="team-content">

                        <h5>Sarita KC</h5>
                        <span>Beauty Therapist</span>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="team-card">

                    <img src="assets/images/b1.jpg" alt="Team">

                    <div class="team-content">

                        <h5>Pratima Rana</h5>
                        <span>Makeup Artist</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include_once('includes/footer.php'); ?>

<!-- MOVE TOP -->
<button onclick="topFunction()" id="movetop">

    <i class="bi bi-arrow-up"></i>

</button>

<style>

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

</style>

<script>

window.onscroll = function(){
    scrollFunction();
};

function scrollFunction(){

    if(document.body.scrollTop > 20 ||
       document.documentElement.scrollTop > 20){

        document.getElementById("movetop").style.display="block";

    }else{

        document.getElementById("movetop").style.display="none";

    }

}

function topFunction(){

    window.scrollTo({
        top:0,
        behavior:'smooth'
    });

}

</script>

</body>
</html>

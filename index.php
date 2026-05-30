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

    <title>GlamourSoft | Beauty Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>

        :root{
            --primary:#e91e63;
            --secondary:#ff4f81;
            --dark:#1d1d1d;
            --light:#ffffff;
            --gray:#f8f9fa;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins',sans-serif;
            overflow-x:hidden;
            background:#fff;
            color:#333;
        }

        html{
            scroll-behavior:smooth;
        }

        /* HERO */

        .hero-section{
            min-height:100vh;
            background:
            linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
            url('assets/images/5.jpg') center/cover no-repeat;
            display:flex;
            align-items:center;
            position:relative;
            overflow:hidden;
        }

        .hero-badge{
            background:rgba(255,255,255,0.12);
            backdrop-filter:blur(10px);
            border:1px solid rgba(255,255,255,0.2);
            color:#fff;
            display:inline-block;
            padding:10px 18px;
            border-radius:50px;
            margin-bottom:25px;
            font-size:14px;
            letter-spacing:1px;
        }

        .hero-title{
            font-size:4.5rem;
            font-weight:700;
            color:#fff;
            line-height:1.2;
            margin-bottom:20px;
        }

        .hero-title span{
            color:#ff80ab;
        }

        .hero-subtitle{
            font-size:1.2rem;
            color:#ddd;
            max-width:650px;
            margin-bottom:35px;
            line-height:1.8;
        }

        .btn-main{
            background:var(--primary);
            color:#fff;
            padding:14px 34px;
            border-radius:50px;
            border:none;
            font-weight:600;
            transition:.3s;
            text-decoration:none;
        }

        .btn-main:hover{
            background:#c2185b;
            color:#fff;
            transform:translateY(-3px);
        }

        .btn-outline-custom{
            border:2px solid #fff;
            color:#fff;
            padding:14px 34px;
            border-radius:50px;
            font-weight:600;
            margin-left:15px;
            transition:.3s;
            text-decoration:none;
        }

        .btn-outline-custom:hover{
            background:#fff;
            color:#111;
        }

        /* SECTION */

        .section-padding{
            padding:100px 0;
        }

        .section-title{
            font-size:2.8rem;
            font-weight:700;
            margin-bottom:15px;
            color:#111;
        }

        .section-subtitle{
            color:#777;
            max-width:700px;
            margin:auto;
            line-height:1.8;
        }

        /* CARDS */

        .service-card{
            background:#fff;
            border:none;
            border-radius:25px;
            overflow:hidden;
            transition:.4s;
            height:100%;
            box-shadow:0 10px 30px rgba(0,0,0,0.06);
        }

        .service-card:hover{
            transform:translateY(-10px);
            box-shadow:0 20px 40px rgba(0,0,0,0.12);
        }

        .service-card img{
            height:260px;
            object-fit:cover;
        }

        .service-card .card-body{
            padding:30px;
        }

        .service-icon{
            width:70px;
            height:70px;
            background:#fde7ef;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:20px;
            margin-bottom:20px;
            color:var(--primary);
            font-size:30px;
        }

        /* STATS */

        .stats-section{
            background:linear-gradient(135deg,var(--primary),#ff5d8f);
            color:#fff;
            border-radius:30px;
            padding:60px 40px;
        }

        .stat-box h2{
            font-size:3rem;
            font-weight:700;
        }

        .stat-box p{
            opacity:.9;
        }

        /* FEATURES */

        .feature-box{
            padding:35px;
            border-radius:25px;
            background:#fff;
            transition:.3s;
            height:100%;
            box-shadow:0 5px 25px rgba(0,0,0,0.05);
        }

        .feature-box:hover{
            transform:translateY(-8px);
        }

        .feature-icon{
            width:80px;
            height:80px;
            border-radius:50%;
            background:#fde7ef;
            color:var(--primary);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:32px;
            margin-bottom:25px;
        }

        /* TESTIMONIAL */

        .testimonial-card{
            background:#fff;
            border-radius:25px;
            padding:35px;
            box-shadow:0 10px 30px rgba(0,0,0,0.06);
            height:100%;
        }

        .testimonial-card img{
            width:70px;
            height:70px;
            border-radius:50%;
            object-fit:cover;
        }

        /* CTA */

        .cta-section{
            background:
            linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
            url('assets/images/b1.jpg') center/cover no-repeat;
            border-radius:35px;
            padding:90px 40px;
            color:#fff;
            text-align:center;
        }

        /* MOVE TOP */

        #movetop{
            display:none;
            position:fixed;
            bottom:25px;
            right:25px;
            z-index:999;
            border:none;
            background:var(--primary);
            color:#fff;
            width:55px;
            height:55px;
            border-radius:50%;
            box-shadow:0 5px 20px rgba(0,0,0,0.25);
        }

        /* RESPONSIVE */

        @media(max-width:992px){

            .hero-title{
                font-size:3rem;
            }

            .section-title{
                font-size:2.2rem;
            }

        }

        @media(max-width:768px){

            .hero-title{
                font-size:2.4rem;
            }

            .hero-subtitle{
                font-size:1rem;
            }

            .btn-outline-custom{
                margin-left:0;
                margin-top:15px;
                display:inline-block;
            }

        }

    </style>

</head>

<body>

<?php include_once('includes/header.php'); ?>

<!-- HERO -->
<section class="hero-section">

    <div class="container">

        <div class="row">

            <div class="col-lg-8" data-aos="fade-right">

                <div class="hero-badge">
                    ✨ Premium Beauty & Salon Management Platform
                </div>

                <h1 class="hero-title">
                    Welcome to <span>GlamourSoft</span><br>
                    Beauty Management
                </h1>

                <p class="hero-subtitle">
                    Transform your beauty experience with our professional salon
                    services, appointment management, luxury treatments,
                    expert stylists, and modern beauty care solutions.
                </p>

                <div class="d-flex flex-wrap">

                    <a href="book-appointment.php" class="btn-main">
                        <i class="bi bi-calendar-check"></i>
                        Book Appointment
                    </a>

                    <a href="services.php" class="btn-outline-custom">
                        Explore Services
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- SERVICES -->
<section class="section-padding bg-light">

    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">

            <h2 class="section-title">
                Our Premium Services
            </h2>

            <p class="section-subtitle">
                Experience world-class salon treatments designed to make you
                look elegant, confident, and beautiful.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4 col-md-6" data-aos="zoom-in">

                <div class="service-card">

                    <img src="assets/images/5.jpg" alt="Hair Styling">

                    <div class="card-body">

                        <div class="service-icon">
                            <i class="bi bi-scissors"></i>
                        </div>

                        <h4>Hair Styling</h4>

                        <p class="text-muted">
                            Professional haircuts, coloring, rebonding,
                            keratin treatment, and premium hair care.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 col-md-6" data-aos="zoom-in">

                <div class="service-card">

                    <img src="assets/images/6.jpg" alt="Skin Care">

                    <div class="card-body">

                        <div class="service-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>

                        <h4>Skin Care</h4>

                        <p class="text-muted">
                            Glow-enhancing facials, skincare therapies,
                            cleanup, whitening, and rejuvenation services.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 col-md-6" data-aos="zoom-in">

                <div class="service-card">

                    <img src="assets/images/b1.jpg" alt="Spa">

                    <div class="card-body">

                        <div class="service-icon">
                            <i class="bi bi-flower1"></i>
                        </div>

                        <h4>Spa & Relaxation</h4>

                        <p class="text-muted">
                            Relaxing body massage, spa therapy,
                            stress relief treatments, and wellness care.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- STATS -->
<section class="section-padding">

    <div class="container">

        <div class="stats-section">

            <div class="row text-center">

                <div class="col-md-3 stat-box">
                    <h2>10K+</h2>
                    <p>Happy Customers</p>
                </div>

                <div class="col-md-3 stat-box">
                    <h2>150+</h2>
                    <p>Beauty Services</p>
                </div>

                <div class="col-md-3 stat-box">
                    <h2>25+</h2>
                    <p>Expert Staff</p>
                </div>

                <div class="col-md-3 stat-box">
                    <h2>5★</h2>
                    <p>Customer Rating</p>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- WHY CHOOSE -->
<section class="section-padding bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Why Choose GlamourSoft?
            </h2>

            <p class="section-subtitle">
                We combine technology, beauty expertise, and customer care
                to provide the best salon experience.
            </p>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="feature-box">

                    <div class="feature-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h4>Expert Professionals</h4>

                    <p class="text-muted">
                        Certified beauty specialists with years of
                        professional salon experience.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-box">

                    <div class="feature-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <h4>Premium Products</h4>

                    <p class="text-muted">
                        We use branded and safe beauty products
                        for all treatments and services.
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="feature-box">

                    <div class="feature-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>

                    <h4>Trusted Quality</h4>

                    <p class="text-muted">
                        Thousands of satisfied customers trust
                        our salon services and management system.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- TESTIMONIALS -->
<section class="section-padding">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                What Clients Say
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="testimonial-card">

                    <div class="d-flex align-items-center mb-3">

                        <img src="https://i.pravatar.cc/100?img=1">

                        <div class="ms-3">
                            <h5 class="mb-0">Sophia</h5>
                            <small class="text-muted">Customer</small>
                        </div>

                    </div>

                    <p class="text-muted">
                        “Amazing experience! The salon environment,
                        staff behavior, and service quality are exceptional.”
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="testimonial-card">

                    <div class="d-flex align-items-center mb-3">

                        <img src="https://i.pravatar.cc/100?img=5">

                        <div class="ms-3">
                            <h5 class="mb-0">Emma</h5>
                            <small class="text-muted">Customer</small>
                        </div>

                    </div>

                    <p class="text-muted">
                        “Booking appointments was smooth and the
                        beauty treatments were premium quality.”
                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="testimonial-card">

                    <div class="d-flex align-items-center mb-3">

                        <img src="https://i.pravatar.cc/100?img=9">

                        <div class="ms-3">
                            <h5 class="mb-0">Olivia</h5>
                            <small class="text-muted">Customer</small>
                        </div>

                    </div>

                    <p class="text-muted">
                        “Professional management system and excellent
                        customer support. Highly recommended.”
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- CTA -->
<section class="section-padding">

    <div class="container">

        <div class="cta-section" data-aos="zoom-in">

            <h2 class="display-5 fw-bold mb-4">
                Ready To Experience Luxury Beauty Care?
            </h2>

            <p class="mb-4 fs-5">
                Book your appointment today and let our beauty experts
                transform your style and confidence.
            </p>

            <a href="book-appointment.php" class="btn-main">
                <i class="bi bi-calendar-heart"></i>
                Get Appointment
            </a>

        </div>

    </div>

</section>

<?php include_once('includes/footer.php'); ?>

<!-- MOVE TOP -->
<button onclick="topFunction()" id="movetop">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>

    AOS.init({
        duration:1000,
        once:true
    });

    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {

        const btn = document.getElementById("movetop");

        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {

            btn.style.display = "block";

        } else {

            btn.style.display = "none";
        }
    }

    function topFunction() {

        window.scrollTo({
            top:0,
            behavior:'smooth'
        });

    }

</script>

</body>
</html>

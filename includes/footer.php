<?php
$contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
$contact = mysqli_fetch_array($contactQuery);

$aboutQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='aboutus'");
$about = mysqli_fetch_array($aboutQuery);
?>

<!-- FOOTER -->
<footer class="footer-section">

    <div class="container">

        <div class="row g-5">

            <!-- BRAND INFO -->
            <div class="col-lg-4 col-md-6">

                <div class="footer-brand">

                    <h2>
                        <i class="bi bi-scissors"></i>
                        GlamourSoft
                    </h2>

                    <p>
                        GlamourSoft Beauty Management System is a modern salon
                        management platform developed for beauty parlours,
                        salons, and spa businesses in Nepal.
                    </p>

                    <div class="footer-social">

                        <a href="#">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-tiktok"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-youtube"></i>
                        </a>

                    </div>

                </div>

            </div>

            <!-- QUICK LINKS -->
            <div class="col-lg-2 col-md-6">

                <h5 class="footer-title">
                    Quick Links
                </h5>

                <ul class="footer-links">

                    <li>
                        <a href="index.php">
                            <i class="bi bi-chevron-right"></i>
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="about.php">
                            <i class="bi bi-chevron-right"></i>
                            About
                        </a>
                    </li>

                    <li>
                        <a href="services.php">
                            <i class="bi bi-chevron-right"></i>
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="contact.php">
                            <i class="bi bi-chevron-right"></i>
                            Contact
                        </a>
                    </li>

                    <li>
                        <a href="book-appointment.php">
                            <i class="bi bi-chevron-right"></i>
                            Book Appointment
                        </a>
                    </li>

                </ul>

            </div>

            <!-- OUR SERVICES -->
            <div class="col-lg-3 col-md-6">

                <h5 class="footer-title">
                    Beauty Services
                </h5>

                <ul class="footer-links">

                    <li>
                        <a href="#">
                            <i class="bi bi-check-circle"></i>
                            Bridal Makeup
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="bi bi-check-circle"></i>
                            Facial & Cleanup
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="bi bi-check-circle"></i>
                            Hair Smoothening
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="bi bi-check-circle"></i>
                            Threading & Waxing
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <i class="bi bi-check-circle"></i>
                            Spa & Massage
                        </a>
                    </li>

                </ul>

            </div>

            <!-- CONTACT INFO -->
            <div class="col-lg-3 col-md-6">

                <h5 class="footer-title">
                    Contact Us
                </h5>

                <div class="footer-contact">

                    <div class="contact-box">

                        <div class="icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div class="text">
                            <p>
                                <?php echo htmlentities($contact['PageDescription']); ?>
                            </p>
                        </div>

                    </div>

                    <div class="contact-box">

                        <div class="icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>

                        <div class="text">
                            <a href="tel:+977<?php echo htmlentities($contact['MobileNumber']); ?>">
                                +977 <?php echo htmlentities($contact['MobileNumber']); ?>
                            </a>
                        </div>

                    </div>

                    <div class="contact-box">

                        <div class="icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>

                        <div class="text">
                            <a href="mailto:<?php echo htmlentities($contact['Email']); ?>">
                                <?php echo htmlentities($contact['Email']); ?>
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ABOUT SECTION -->
        <!-- <div class="footer-about mt-5">

            <div class="row">

                <div class="col-lg-12">

                    <div class="about-card">

                        <h4>
                            <?php echo htmlentities($about['PageTitle']); ?>
                        </h4>

                        <p>
                            <?php echo htmlentities($about['PageDescription']); ?>
                        </p>

                    </div>

                </div>

            </div>

        </div> -->

        <!-- FOOTER BOTTOM -->
        <div class="footer-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <p class="mb-0">
                        © <?php echo date('Y'); ?>
                        GlamourSoft - Beauty Management
                    </p>

                </div>

                <div class="col-md-6 text-md-end">

                    <p class="mb-0">
                        Developed in Ghorahi, Dang, Nepal 🇳🇵
                    </p>

                </div>

            </div>

        </div>

    </div>

</footer>

<!-- FOOTER STYLE -->
<style>

    .footer-section{
        background:#111827;
        color:#d1d5db;
        padding:90px 0 25px;
        position:relative;
    }

    .footer-brand h2{
        color:#fff;
        font-weight:700;
        margin-bottom:20px;
    }

    .footer-brand h2 i{
        color:#e91e63;
        margin-right:10px;
    }

    .footer-brand p{
        line-height:1.9;
        color:#9ca3af;
    }

    .footer-social{
        display:flex;
        gap:12px;
        margin-top:25px;
    }

    .footer-social a{
        width:42px;
        height:42px;
        background:rgba(255,255,255,0.08);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        transition:.3s;
        text-decoration:none;
    }

    .footer-social a:hover{
        background:#e91e63;
        transform:translateY(-4px);
    }

    .footer-title{
        color:#fff;
        margin-bottom:25px;
        font-weight:600;
        position:relative;
    }

    .footer-title::after{
        content:'';
        position:absolute;
        width:40px;
        height:3px;
        background:#e91e63;
        left:0;
        bottom:-10px;
        border-radius:10px;
    }

    .footer-links{
        list-style:none;
        padding:0;
        margin:0;
    }

    .footer-links li{
        margin-bottom:14px;
    }

    .footer-links a{
        color:#9ca3af;
        text-decoration:none;
        transition:.3s;
    }

    .footer-links a:hover{
        color:#fff;
        padding-left:6px;
    }

    .footer-links i{
        color:#e91e63;
        margin-right:8px;
    }

    .contact-box{
        display:flex;
        align-items:flex-start;
        margin-bottom:20px;
    }

    .contact-box .icon{
        width:42px;
        height:42px;
        background:rgba(255,255,255,0.08);
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-right:15px;
        color:#e91e63;
        flex-shrink:0;
    }

    .contact-box .text p,
    .contact-box .text a{
        margin:0;
        color:#9ca3af;
        text-decoration:none;
        line-height:1.8;
    }

    .contact-box .text a:hover{
        color:#fff;
    }

    .footer-about{
        margin-top:60px;
    }

    .about-card{
        background:rgba(255,255,255,0.04);
        padding:30px;
        border-radius:20px;
        border:1px solid rgba(255,255,255,0.05);
    }

    .about-card h4{
        color:#fff;
        margin-bottom:15px;
    }

    .about-card p{
        color:#9ca3af;
        line-height:1.9;
        margin:0;
    }

    .footer-bottom{
        border-top:1px solid rgba(255,255,255,0.08);
        margin-top:50px;
        padding-top:25px;
        color:#9ca3af;
        font-size:14px;
    }

    @media(max-width:768px){

        .footer-section{
            text-align:center;
        }

        .footer-title::after{
            left:50%;
            transform:translateX(-50%);
        }

        .footer-social{
            justify-content:center;
        }

        .contact-box{
            flex-direction:column;
            align-items:center;
        }

        .contact-box .icon{
            margin-right:0;
            margin-bottom:10px;
        }

    }

</style>

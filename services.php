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

    <title>Beauty Parlour Management System | Services</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Existing CSS -->
    <link rel="stylesheet" href="css/style-starter.css">

    <style>
        :root {
            --primary-color: #e91e63;
            --primary-dark: #c2185b;
            --light-pink: #fff0f5;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: var(--text-dark);
        }

        /* HERO SECTION */
        .services-hero {
            position: relative;
            padding: 110px 0;
            background:
                    linear-gradient(rgba(0, 0, 0, 0.55),
                    rgba(0, 0, 0, 0.55)),
                    url('https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .services-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
        }

        .services-hero p {
            color: rgba(255,255,255,0.85);
            max-width: 650px;
            margin: 0 auto;
            font-size: 1.05rem;
        }

        /* SECTION HEADER */
        .section-badge {
            display: inline-block;
            background: rgba(233, 30, 99, 0.1);
            color: var(--primary-color);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .section-subtitle {
            color: var(--text-light);
            max-width: 700px;
            margin: auto;
        }

        /* SERVICE CARD */
        .service-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
            transition: all 0.35s ease;
            height: 100%;
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .service-image-wrapper {
            overflow: hidden;
            position: relative;
        }

        .service-image-wrapper::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(0,0,0,0.3),
                    transparent);
        }

        .service-img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover .service-img {
            transform: scale(1.08);
        }

        .service-content {
            padding: 24px;
        }

        .service-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            background: var(--light-pink);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .service-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .service-description {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .service-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .book-btn {
            border-radius: 50px;
            padding: 10px 22px;
            background: var(--primary-color);
            border: none;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .book-btn:hover {
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
        }

        /* EMPTY STATE */
        .empty-state {
            padding: 70px 30px;
            background: #fff;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .empty-state i {
            font-size: 70px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            .services-hero {
                padding: 80px 0;
            }

            .services-hero h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .service-img {
                height: 220px;
            }
        }
    </style>
</head>

<body>

<?php include_once('includes/header.php'); ?>

<!-- HERO SECTION -->
<section class="services-hero text-center">
    <div class="container">
        <h1>Luxury Beauty Services</h1>

        <p class="mt-3">
            Enhance your confidence with our premium beauty, skincare,
            haircare, and wellness treatments designed by professionals.
        </p>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="py-5">
    <div class="container py-lg-4">

        <!-- SECTION TITLE -->
        <div class="text-center mb-5">

            <span class="section-badge">
                Our Professional Services
            </span>

            <h2 class="section-title">
                Discover Beauty & Wellness
            </h2>

            <p class="section-subtitle">
                We provide high-quality salon and beauty treatments
                tailored to your style, comfort, and confidence.
            </p>
        </div>

        <div class="row g-4">

            <?php
            $ret = mysqli_query($con, "SELECT * FROM tblservices");

            if(mysqli_num_rows($ret) > 0)
            {
                while($row = mysqli_fetch_array($ret))
                {
            ?>

            <div class="col-xl-4 col-md-6">

                <div class="card service-card shadow-sm">

                    <!-- IMAGE -->
                    <div class="service-image-wrapper">

                        <?php if(!empty($row['Image'])) { ?>

                            <img src="admin/images/<?php echo $row['Image']; ?>"
                                 class="service-img"
                                 alt="<?php echo $row['ServiceName']; ?>">

                        <?php } else { ?>

                            <img src="https://via.placeholder.com/600x400?text=Beauty+Service"
                                 class="service-img"
                                 alt="Service Image">

                        <?php } ?>

                    </div>

                    <!-- CONTENT -->
                    <div class="service-content">

                        <!-- ICON -->
                        <div class="service-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <!-- TITLE -->
                        <h4 class="service-title">
                            <?php echo $row['ServiceName']; ?>
                        </h4>

                        <!-- DESCRIPTION -->
                        <p class="service-description">
                            <?php echo substr($row['ServiceDescription'], 0, 120); ?>...
                        </p>

                        <!-- FOOTER -->
                        <div class="service-footer">

                            <div class="service-price">
                                $<?php echo $row['Cost']; ?>
                            </div>

                            <a href="appointment.php"
                               class="btn book-btn">
                                Book Now
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <?php
                }
            }
            else
            {
            ?>

            <div class="col-12">
                <div class="empty-state">

                    <i class="bi bi-scissors"></i>

                    <h3 class="fw-bold mb-3">
                        No Services Available
                    </h3>

                    <p class="text-muted mb-0">
                        Services will be added soon. Please check back later.
                    </p>

                </div>
            </div>

            <?php } ?>

        </div>

    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
{
    $fname=$_POST['firstname'];
    $lname=$_POST['lastname'];
    $contno=$_POST['mobilenumber'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $ret=mysqli_query($con, "select Email from tbluser where Email='$email' || MobileNumber='$contno'");
    $result=mysqli_fetch_array($ret);

    if($result>0)
    {
        echo "<script>alert('This email or Contact Number already associated with another account!.');</script>";
    }
    else
    {
        $query=mysqli_query($con, "insert into tbluser(FirstName, LastName, MobileNumber, Email, Password) value('$fname', '$lname','$contno', '$email', '$password' )");

        if ($query)
        {
            echo "<script>alert('You have successfully registered.');</script>";
            echo '<script>window.location.href="login.php"</script>';
        }
        else
        {
            echo "<script>alert('Something Went Wrong. Please try again.');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Beauty Parlour Management System | Signup Page</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <style>

        :root{
            --primary:#e91e63;
            --primary-dark:#c2185b;
            --light-pink:#fff0f5;
            --text-dark:#1f2937;
            --text-light:#6b7280;
        }

        body{
            font-family:'Poppins', sans-serif;
            background:#f8fafc;
            color:var(--text-dark);
        }

        /* HERO SECTION */

        .signup-hero{
            position:relative;
            background:
                    linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                    url('https://images.unsplash.com/photo-1522337660859-02fbefca4702?q=80&w=1600&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            padding:110px 0;
            text-align:center;
        }

        .signup-hero h1{
            color:#fff;
            font-size:3rem;
            font-weight:700;
        }

        .signup-hero p{
            color:rgba(255,255,255,0.85);
            max-width:650px;
            margin:auto;
            margin-top:15px;
            font-size:1.05rem;
        }

        /* MAIN SECTION */

        .signup-section{
            position:relative;
            margin-top:-70px;
            z-index:10;
            padding-bottom:70px;
        }

        .signup-wrapper{
            background:#fff;
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 25px 70px rgba(0,0,0,0.08);
        }

        /* LEFT SIDE */

        .contact-side{
            background:linear-gradient(135deg, var(--primary), #ff4f93);
            padding:50px 40px;
            height:100%;
            color:#fff;
        }

        .contact-side h3{
            font-size:2rem;
            font-weight:700;
            margin-bottom:15px;
        }

        .contact-side p{
            color:rgba(255,255,255,0.88);
        }

        .contact-box{
            display:flex;
            align-items:flex-start;
            margin-top:35px;
        }

        .contact-icon{
            width:55px;
            height:55px;
            min-width:55px;
            border-radius:16px;
            background:rgba(255,255,255,0.18);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:20px;
            margin-right:18px;
        }

        .contact-content h6{
            font-weight:600;
            margin-bottom:5px;
        }

        .contact-content p,
        .contact-content a{
            color:rgba(255,255,255,0.9);
            text-decoration:none;
            margin:0;
            font-size:15px;
            line-height:1.7;
        }

        /* FORM SIDE */

        .form-side{
            padding:50px;
        }

        .form-side h2{
            font-size:2rem;
            font-weight:700;
            margin-bottom:10px;
        }

        .form-subtitle{
            color:var(--text-light);
            margin-bottom:35px;
        }

        .form-label{
            font-weight:600;
            margin-bottom:10px;
        }

        .form-control{
            height:56px;
            border-radius:14px;
            border:1px solid #e5e7eb;
            padding:12px 18px;
            font-size:15px;
            box-shadow:none !important;
        }

        .form-control:focus{
            border-color:var(--primary);
        }

        .input-wrapper{
            position:relative;
        }

        .form-icon{
            position:absolute;
            top:50%;
            right:18px;
            transform:translateY(-50%);
            color:#9ca3af;
        }

        .signup-btn{
            width:100%;
            height:56px;
            border:none;
            border-radius:16px;
            background:var(--primary);
            color:#fff;
            font-size:16px;
            font-weight:600;
            transition:0.3s ease;
            margin-top:10px;
        }

        .signup-btn:hover{
            background:var(--primary-dark);
            transform:translateY(-2px);
        }

        .login-text{
            margin-top:25px;
            text-align:center;
            color:var(--text-light);
        }

        .login-text a{
            color:var(--primary);
            font-weight:600;
            text-decoration:none;
        }

        /* MOVE TOP BUTTON */

        #movetop{
            position:fixed;
            bottom:20px;
            right:20px;
            z-index:99;
            border:none;
            outline:none;
            background:var(--primary);
            color:white;
            cursor:pointer;
            padding:12px 15px;
            border-radius:50%;
            font-size:18px;
            display:none;
        }

        #movetop:hover{
            background:var(--primary-dark);
        }

        /* RESPONSIVE */

        @media(max-width:991px){

            .signup-hero{
                padding:90px 0;
            }

            .signup-hero h1{
                font-size:2.3rem;
            }

            .form-side{
                padding:35px;
            }

            .contact-side{
                padding:35px;
            }
        }

        @media(max-width:767px){

            .signup-section{
                margin-top:-40px;
            }

            .signup-hero{
                padding:70px 0;
            }

            .form-side{
                padding:28px;
            }

            .contact-side{
                padding:28px;
            }
        }

    </style>

</head>

<body id="home">

<?php include_once('includes/header.php'); ?>

<!-- HERO SECTION -->

<section class="signup-hero">

    <div class="container">

        <h1>Create Your Account</h1>

        <p>
            Join our beauty community and book premium salon services with ease.
        </p>

    </div>

</section>

<!-- SIGNUP SECTION -->

<section class="signup-section">

    <div class="container">

        <div class="signup-wrapper">

            <div class="row g-0">

                <!-- LEFT SIDE -->

                <div class="col-lg-5">

                    <div class="contact-side">

                        <h3>Get In Touch</h3>

                        <p>
                            Have questions or need assistance?
                            Reach out to our beauty support team anytime.
                        </p>

                        <?php

                        $ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");

                        while ($row=mysqli_fetch_array($ret)) {

                        ?>

                        <!-- PHONE -->

                        <div class="contact-box">

                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>

                            <div class="contact-content">

                                <h6>Call Us</h6>

                                <p>
                                    +<?php echo $row['MobileNumber']; ?>
                                </p>

                            </div>

                        </div>

                        <!-- EMAIL -->

                        <div class="contact-box">

                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>

                            <div class="contact-content">

                                <h6>Email Us</h6>

                                <a href="mailto:<?php echo $row['Email']; ?>">
                                    <?php echo $row['Email']; ?>
                                </a>

                            </div>

                        </div>

                        <!-- ADDRESS -->

                        <div class="contact-box">

                            <div class="contact-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div class="contact-content">

                                <h6>Address</h6>

                                <p>
                                    <?php echo $row['PageDescription']; ?>
                                </p>

                            </div>

                        </div>

                        <!-- TIMING -->

                        <div class="contact-box">

                            <div class="contact-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>

                            <div class="contact-content">

                                <h6>Working Hours</h6>

                                <p>
                                    <?php echo $row['Timing']; ?>
                                </p>

                            </div>

                        </div>

                        <?php } ?>

                    </div>

                </div>

                <!-- RIGHT SIDE -->

                <div class="col-lg-7">

                    <div class="form-side">

                        <h2>Signup</h2>

                        <p class="form-subtitle">
                            Fill in your details to create your beauty account.
                        </p>

                        <form method="post"
                              name="signup"
                              onsubmit="return checkpass();">

                            <div class="row">

                                <!-- FIRST NAME -->

                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        First Name
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="text"
                                               class="form-control"
                                               name="firstname"
                                               id="firstname"
                                               placeholder="Enter first name"
                                               required>

                                        <i class="bi bi-person form-icon"></i>

                                    </div>

                                </div>

                                <!-- LAST NAME -->

                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        Last Name
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="text"
                                               class="form-control"
                                               name="lastname"
                                               id="lastname"
                                               placeholder="Enter last name"
                                               required>

                                        <i class="bi bi-person form-icon"></i>

                                    </div>

                                </div>

                                <!-- MOBILE -->

                                <div class="col-12 mb-4">

                                    <label class="form-label">
                                        Mobile Number
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="text"
                                               class="form-control"
                                               placeholder="Enter mobile number"
                                               required
                                               name="mobilenumber"
                                               pattern="[0-9]+"
                                               maxlength="10">

                                        <i class="bi bi-phone form-icon"></i>

                                    </div>

                                </div>

                                <!-- EMAIL -->

                                <div class="col-12 mb-4">

                                    <label class="form-label">
                                        Email Address
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="email"
                                               class="form-control"
                                               placeholder="Enter email address"
                                               required
                                               name="email">

                                        <i class="bi bi-envelope form-icon"></i>

                                    </div>

                                </div>

                                <!-- PASSWORD -->

                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        Password
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="password"
                                               class="form-control"
                                               name="password"
                                               placeholder="Enter password"
                                               required>

                                        <i class="bi bi-lock form-icon"></i>

                                    </div>

                                </div>

                                <!-- REPEAT PASSWORD -->

                                <div class="col-md-6 mb-4">

                                    <label class="form-label">
                                        Repeat Password
                                    </label>

                                    <div class="input-wrapper">

                                        <input type="password"
                                               class="form-control"
                                               name="repeatpassword"
                                               placeholder="Repeat password"
                                               required>

                                        <i class="bi bi-shield-lock form-icon"></i>

                                    </div>

                                </div>

                            </div>

                            <!-- BUTTON -->

                            <button type="submit"
                                    class="signup-btn"
                                    name="submit">

                                Create Account

                            </button>

                            <!-- LOGIN -->

                            <div class="login-text">

                                Already have an account?
                                <a href="login.php">
                                    Login Here
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include_once('includes/footer.php'); ?>

<!-- JS -->

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script>

$(function () {
    $('.navbar-toggler').click(function () {
        $('body').toggleClass('noscroll');
    })
});

</script>

<!-- PASSWORD MATCH -->

<script type="text/javascript">

function checkpass()
{
    if(document.signup.password.value != document.signup.repeatpassword.value)
    {
        alert('Password and Repeat Password field does not match');

        document.signup.repeatpassword.focus();

        return false;
    }

    return true;
}

</script>

<!-- MOVE TOP -->

<button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-long-arrow-up"></span>
</button>

<script>

window.onscroll = function () {
    scrollFunction()
};

function scrollFunction()
{
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
    {
        document.getElementById("movetop").style.display = "block";
    }
    else
    {
        document.getElementById("movetop").style.display = "none";
    }
}

function topFunction()
{
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

</script>

</body>
</html>

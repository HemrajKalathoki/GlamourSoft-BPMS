<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {

if (isset($_POST['submit'])) {

    $uid = $_SESSION['bpmsuid'];
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];

    $query = mysqli_query(
        $con,
        "update tbluser 
         set FirstName='$fname', LastName='$lname' 
         where ID='$uid'"
    );

    if ($query) {
        echo '<script>alert("Profile updated successfully.")</script>';
        echo '<script>window.location.href=profile.php</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.")</script>';
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <style>

        :root{
            --primary:#e91e63;
            --primary-dark:#c2185b;
            --text:#111827;
            --muted:#6b7280;
            --bg:#f8fafc;
        }

        body{
            font-family:'Poppins', sans-serif;
            background:var(--bg);
        }

        /* HERO */

        .profile-hero{
            background:linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                       url('https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=1600&auto=format&fit=crop');
            background-size:cover;
            background-position:center;
            padding:100px 0;
            text-align:center;
            color:#fff;
        }

        .profile-hero h1{
            font-size:2.8rem;
            font-weight:700;
        }

        .profile-wrapper{
            margin-top:-70px;
            padding-bottom:70px;
        }

        .profile-card{
            background:#fff;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 25px 60px rgba(0,0,0,0.08);
        }

        /* LEFT PANEL */

        .profile-side{
            background:linear-gradient(135deg, var(--primary), #ff4f93);
            color:#fff;
            padding:40px;
        }

        .profile-side h3{
            font-weight:700;
            margin-bottom:10px;
        }

        .profile-side p{
            opacity:0.9;
            font-size:14px;
        }

        .info-box{
            margin-top:30px;
            display:flex;
            gap:15px;
        }

        .info-icon{
            width:45px;
            height:45px;
            min-width:45px;
            border-radius:12px;
            background:rgba(255,255,255,0.2);
            display:flex;
            align-items:center;
            justify-content:center;
        }

        /* FORM */

        .profile-form{
            padding:40px;
        }

        .form-title{
            font-size:1.8rem;
            font-weight:700;
            margin-bottom:20px;
        }

        .form-control{
            height:52px;
            border-radius:14px;
            border:1px solid #e5e7eb;
        }

        .form-control:focus{
            border-color:var(--primary);
            box-shadow:none;
        }

        .btn-save{
            width:100%;
            height:52px;
            border:none;
            border-radius:14px;
            background:var(--primary);
            color:#fff;
            font-weight:600;
            transition:0.3s;
        }

        .btn-save:hover{
            background:var(--primary-dark);
            transform:translateY(-2px);
        }

        /* MOVE TOP */

        #movetop{
            position:fixed;
            bottom:20px;
            right:20px;
            background:var(--primary);
            color:#fff;
            border:none;
            padding:12px 14px;
            border-radius:50%;
            display:none;
        }

    </style>

</head>

<body>

<?php include_once('includes/header.php'); ?>

<!-- HERO -->

<section class="profile-hero">
    <div class="container">
        <h1>My Profile</h1>
        <p>Manage your personal information</p>
    </div>
</section>

<!-- CONTENT -->

<section class="profile-wrapper">
    <div class="container">

        <div class="profile-card">
            <div class="row g-0">

                <!-- LEFT SIDE -->
                <div class="col-lg-4">
                    <div class="profile-side">

                        <h3>Account Info</h3>
                        <p>Your profile details and contact information</p>

                        <?php
                        $uid = $_SESSION['bpmsuid'];
                        $ret = mysqli_query($con, "select * from tbluser where ID='$uid'");
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>

                        <div class="info-box">
                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <small>Username</small><br>
                                <strong><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></strong>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <small>Email</small><br>
                                <strong><?php echo $row['Email']; ?></strong>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div>
                                <small>Phone</small><br>
                                <strong><?php echo $row['MobileNumber']; ?></strong>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-lg-8">

                    <div class="profile-form">

                        <div class="form-title">Edit Profile</div>

                        <form method="post">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text"
                                           class="form-control"
                                           name="firstname"
                                           value="<?php echo $row['FirstName']; ?>"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text"
                                           class="form-control"
                                           name="lastname"
                                           value="<?php echo $row['LastName']; ?>"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text"
                                           class="form-control"
                                           value="<?php echo $row['MobileNumber']; ?>"
                                           readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text"
                                           class="form-control"
                                           value="<?php echo $row['Email']; ?>"
                                           readonly>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Registration Date</label>
                                    <input type="text"
                                           class="form-control"
                                           value="<?php echo $row['RegDate']; ?>"
                                           readonly>
                                </div>

                            </div>

                            <button type="submit"
                                    name="submit"
                                    class="btn-save">
                                Save Changes
                            </button>

                        </form>

                        <?php } ?>

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
    });
});
</script>

<!-- MOVE TOP -->
<button onclick="topFunction()" id="movetop">↑</button>

<script>
window.onscroll = function () {
    document.getElementById("movetop").style.display =
        (document.documentElement.scrollTop > 20) ? "block" : "none";
};

function topFunction() {
    document.documentElement.scrollTop = 0;
}
</script>

</body>
</html>

<?php } ?>

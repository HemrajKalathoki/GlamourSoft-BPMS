<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login']))
{
    $adminuser = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query(
        $con,
        "select ID from tbladmin where UserName='$adminuser' && Password='$password'"
    );

    $ret = mysqli_fetch_array($query);

    if($ret > 0)
    {
        $_SESSION['bpmsaid'] = $ret['ID'];
        header('location:dashboard.php');
    }
    else
    {
        $msg = "Invalid Credentials.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | BPMS</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <style>

        :root{
            --primary:#e91e63;
            --primary-dark:#c2185b;
            --bg:#0f172a;
        }

        body{
            font-family:'Poppins', sans-serif;
            background: radial-gradient(circle at top, #1e293b, #0f172a);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        /* CARD */

        .login-card{
            width:100%;
            max-width:420px;
            background:#fff;
            border-radius:22px;
            padding:40px;
            box-shadow:0 25px 80px rgba(0,0,0,0.35);
        }

        .brand{
            text-align:center;
            margin-bottom:25px;
        }

        .brand h2{
            font-weight:700;
            color:#111827;
        }

        .brand p{
            color:#6b7280;
            font-size:14px;
        }

        /* INPUTS */

        .form-control{
            height:52px;
            border-radius:14px;
            border:1px solid #e5e7eb;
            padding-left:44px;
        }

        .form-control:focus{
            border-color:var(--primary);
            box-shadow:none;
        }

        .input-wrapper{
            position:relative;
            margin-bottom:18px;
        }

        .input-icon{
            position:absolute;
            top:50%;
            left:14px;
            transform:translateY(-50%);
            color:#9ca3af;
        }

        /* BUTTON */

        .btn-login{
            width:100%;
            height:52px;
            border:none;
            border-radius:14px;
            background:var(--primary);
            color:#fff;
            font-weight:600;
            transition:0.3s;
        }

        .btn-login:hover{
            background:var(--primary-dark);
            transform:translateY(-2px);
        }

        /* LINKS */

        .links{
            display:flex;
            justify-content:space-between;
            margin-top:15px;
            font-size:14px;
        }

        .links a{
            text-decoration:none;
            color:var(--primary);
            font-weight:500;
        }

        .error{
            color:#dc2626;
            font-size:14px;
            text-align:center;
            margin-bottom:10px;
        }

        /* RESPONSIVE */

        @media(max-width:480px){
            .login-card{
                margin:20px;
                padding:30px;
            }
        }

    </style>

</head>

<body>

<div class="login-card">

    <!-- BRAND -->

    <div class="brand">
        <h2>BPMS Admin</h2>
        <p>Sign in to manage system</p>
    </div>

    <!-- ERROR -->

    <?php if($msg){ ?>
        <div class="error">
            <?php echo $msg; ?>
        </div>
    <?php } ?>

    <!-- FORM -->

    <form method="post">

        <!-- USERNAME -->

        <div class="input-wrapper">

            <i class="bi bi-person input-icon"></i>

            <input type="text"
                   class="form-control"
                   name="username"
                   placeholder="Username"
                   required>

        </div>

        <!-- PASSWORD -->

        <div class="input-wrapper">

            <i class="bi bi-lock input-icon"></i>

            <input type="password"
                   class="form-control"
                   name="password"
                   placeholder="Password"
                   required>

        </div>

        <!-- BUTTON -->

        <button type="submit"
                name="login"
                class="btn-login">

            Sign In

        </button>

        <!-- LINKS -->

        <div class="links">

            <a href="../index.php">Home</a>

            <a href="forgot-password.php">Forgot Password?</a>

        </div>

    </form>

</div>

</body>
</html>

<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

if(isset($_POST['submit']))
  {
  	$bpmsaid=$_SESSION['bpmsaid'];
     $pagetitle=$_POST['pagetitle'];
$pagedes=$_POST['pagedes'];
     
    $query=mysqli_query($con,"update tblpage set PageTitle='$pagetitle',PageDescription='$pagedes' where  PageType='aboutus'");
    if ($query) {
    $msg="About Us has been updated.";
  }
  else
    {
      $msg="Something Went Wrong. Please try again";
    }

  
}
  ?>
<!DOCTYPE HTML>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About Us | GlamourSoft</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<script>
bkLib.onDomLoaded(function () {
    nicEditors.allTextAreas();
});
</script>

<style>

body{
    font-family:'Poppins',sans-serif;
    background:#f5f7fb;
}

.dashboard-wrapper{
    padding:100px 25px 30px 305px;
    transition:.3s;
    min-height:100vh;
}

.dashboard-wrapper.full-width{
    padding-left:25px;
}

.page-title{
    margin-bottom:30px;
}

.page-title h2{
    font-weight:700;
    color:#222;
}

.page-title p{
    color:#777;
    margin:0;
}

.form-card{
    background:#fff;
    border-radius:22px;
    padding:30px;
    box-shadow:0 8px 25px rgba(0,0,0,.05);
}

.form-label{
    font-weight:600;
    color:#444;
}

.form-control{
    border-radius:12px;
    padding:12px 15px;
}

.btn-primary-custom{
    background:linear-gradient(135deg,#e91e63,#ff4f81);
    border:none;
    color:#fff;
    border-radius:12px;
    padding:12px 30px;
    font-weight:600;
    transition:.3s;
}

.btn-primary-custom:hover{
    transform:translateY(-2px);
    color:#fff;
}

.alert{
    border-radius:12px;
}

@media(max-width:991px){

    .dashboard-wrapper{
        padding:100px 15px 20px;
    }

}

</style>

</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<div class="dashboard-wrapper">

    <div class="page-title">
        <h2>About Us</h2>
        <p>Manage the About Us page content.</p>
    </div>

    <div class="form-card">

        <?php if($msg){ ?>

            <div class="alert alert-success mb-4">
                <?php echo $msg; ?>
            </div>

        <?php } ?>

        <form method="post">

            <?php

            $ret=mysqli_query(
                $con,
                "SELECT * FROM tblpage WHERE PageType='aboutus'"
            );

            while($row=mysqli_fetch_array($ret)){

            ?>

            <div class="mb-4">

                <label class="form-label">
                    Page Title
                </label>

                <input type="text"
                       name="pagetitle"
                       class="form-control"
                       value="<?php echo $row['PageTitle']; ?>"
                       required>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Page Description
                </label>

                <textarea name="pagedes"
                          rows="10"
                          class="form-control"><?php echo $row['PageDescription']; ?></textarea>

            </div>

            <?php } ?>

            <button type="submit"
                    name="submit"
                    class="btn btn-primary-custom">

                <i class="bi bi-save me-2"></i>
                Update About Us

            </button>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php } ?>
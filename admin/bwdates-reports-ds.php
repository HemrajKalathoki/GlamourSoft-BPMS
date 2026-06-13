
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} else {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | B/W Reports</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Existing Project CSS -->
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/custom.css" rel="stylesheet">

<!-- Existing JS -->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>

<style>

:root{
    --primary-color:#e91e63;
    --secondary-color:#ff4f81;
    --bg-color:#f8f9fc;
    --card-bg:#ffffff;
    --text-dark:#1f2937;
    --text-muted:#6b7280;
}

/* PAGE LAYOUT */
.report-page{
    margin-left:280px;
    padding:110px 30px 40px;
    min-height:100vh;
    background:var(--bg-color);
    transition:all .3s ease;
}

@media(max-width:991px){
    .report-page{
        margin-left:0;
        padding:100px 15px 30px;
    }
}

/* PAGE HEADER */
.page-header{
    margin-bottom:25px;
}

.page-header h1{
    font-size:30px;
    font-weight:700;
    color:var(--text-dark);
    margin-bottom:8px;
}

.page-header p{
    color:var(--text-muted);
    margin:0;
    font-size:15px;
}

/* CARD */
.report-card{
    max-width:900px;
    background:var(--card-bg);
    border-radius:22px;
    overflow:hidden;
    box-shadow:
        0 10px 25px rgba(0,0,0,.05),
        0 2px 8px rgba(0,0,0,.04);
}

.report-card-header{
    padding:25px 30px;
    border-bottom:1px solid #f1f1f1;
    background:#fff;
}

.report-card-header h4{
    margin:0;
    font-size:20px;
    font-weight:600;
    color:var(--text-dark);
}

.report-card-body{
    padding:30px;
}

/* FORM */
.form-label-custom{
    display:block;
    margin-bottom:10px;
    font-weight:600;
    color:#374151;
}

.form-control-custom{
    width:100%;
    height:56px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    padding:12px 16px;
    font-size:15px;
    transition:.3s ease;
    background:#fff;
}

.form-control-custom:focus{
    outline:none;
    border-color:var(--primary-color);
    box-shadow:0 0 0 4px rgba(233,30,99,.12);
}

.btn-report{
    height:56px;
    padding:0 32px;
    border:none;
    border-radius:14px;
    background:linear-gradient(
        135deg,
        var(--primary-color),
        var(--secondary-color)
    );
    color:#fff;
    font-weight:600;
    font-size:15px;
    transition:.3s ease;
}

.btn-report:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 24px rgba(233,30,99,.25);
}

.message-box{
    text-align:center;
    color:red;
    margin-bottom:20px;
    font-size:15px;
}

.form-row{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.form-col{
    flex:1;
    min-width:250px;
}

.action-area{
    margin-top:30px;
}

</style>

</head>

<body>

<div class="main-content">

    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php');?>

    <!-- Header -->
    <?php include_once('includes/header.php');?>

    <!-- Main Content -->
    <div id="page-wrapper">

        <div class="report-page">

            <!-- Page Heading -->
            <div class="page-header">

                <h1>Between Dates Reports</h1>

                <p>
                    Generate appointment reports by selecting a custom date range.
                </p>

            </div>

            <!-- Card -->
            <div class="report-card">

                <div class="report-card-header">
                    <h4>Select Date Range</h4>
                </div>

                <div class="report-card-body">

                    <form method="post"
                          name="bwdatesreport"
                          action="bwdates-reports-details.php"
                          enctype="multipart/form-data">

                        <div class="message-box">
                            <?php if($msg){ echo $msg; } ?>
                        </div>

                        <div class="form-row">

                            <div class="form-col">

                                <label class="form-label-custom">
                                    From Date
                                </label>

                                <input
                                    type="date"
                                    name="fromdate"
                                    id="fromdate"
                                    class="form-control-custom"
                                    value=""
                                    required="true">

                            </div>

                            <div class="form-col">

                                <label class="form-label-custom">
                                    To Date
                                </label>

                                <input
                                    type="date"
                                    name="todate"
                                    id="todate"
                                    class="form-control-custom"
                                    value=""
                                    required="true">

                            </div>

                        </div>

                        <div class="action-area">

                            <button
                                type="submit"
                                name="submit"
                                class="btn-report">

                                Generate Report

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include_once('includes/footer.php');?>

</div>

<!-- Classie -->
<script src="js/classie.js"></script>

<script>
var menuLeft = document.getElementById('cbp-spmenu-s1'),
    showLeftPush = document.getElementById('showLeftPush'),
    body = document.body;

if(showLeftPush){
    showLeftPush.onclick = function() {
        classie.toggle(this, 'active');

        if(menuLeft){
            classie.toggle(menuLeft, 'cbp-spmenu-open');
        }

        disableOther('showLeftPush');
    };
}

function disableOther(button) {
    if(button !== 'showLeftPush') {
        classie.toggle(showLeftPush, 'disabled');
    }
}
</script>

<!-- Existing Scripts -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

</body>
</html>
<?php } ?>


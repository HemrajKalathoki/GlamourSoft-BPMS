<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── POST handler: update remark + status — LOGIC UNCHANGED ── */
    if (isset($_POST['submit'])) {
        $cid    = $_GET['viewid'];
        $remark = $_POST['remark'];
        $status = $_POST['status'];
        $query  = mysqli_query($con, "update tblbook set Remark='$remark',Status='$status' where ID='$cid'");
        if ($query) {
            echo '<script>alert("All remark has been updated.")</script>';
            echo "<script type='text/javascript'> document.location ='all-appointment.php'; </script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || View Appointment</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Base ── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #fdf6f9;
            color: #3d2233;
        }

        /* ── Layout wrapper ── */
        .dashboard-wrapper {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
        }

        .page-content {
            padding: 32px 28px;
        }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #e91e63;
            margin-bottom: 2px;
        }

        .page-header-left p {
            font-size: 0.85rem;
            color: #9e6b7e;
            margin: 0;
        }

        .breadcrumb-nav {
            font-size: 0.8rem;
            color: #9e6b7e;
        }

        .breadcrumb-nav a {
            color: #e91e63;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-nav a:hover { text-decoration: underline; }

        /* ── Two-column grid ── */
        .apt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .apt-grid { grid-template-columns: 1fr; }
        }

        /* ── Shared card styles ── */
        .card-bpms {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f3e0ea;
            box-shadow: 0 2px 20px rgba(233,30,99,.07);
            overflow: hidden;
        }

        .card-bpms-header {
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-bpms-header.pink {
            background: linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);
        }

        .card-bpms-header.purple {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        }

        .card-bpms-header.green {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        .card-bpms-header h5 {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
        }

        .card-bpms-header i {
            color: rgba(255,255,255,.85);
            font-size: 1.1rem;
        }

        .card-body-bpms {
            padding: 24px;
        }

        /* ── Info rows ── */
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid #faeef4;
        }

        .info-row:last-child { border-bottom: none; }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fdf0f5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #e91e63;
            font-size: 0.95rem;
        }

        .info-icon.green  { background: #ecfdf5; color: #059669; }
        .info-icon.blue   { background: #eff6ff; color: #3b82f6; }
        .info-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .info-icon.amber  { background: #fffbeb; color: #d97706; }

        .info-label {
            font-size: 0.75rem;
            color: #9e6b7e;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.9rem;
            color: #3d2233;
            font-weight: 500;
        }

        /* ── Apt number highlight ── */
        .apt-number-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fdf0f5;
            color: #e91e63;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 5px 14px;
            border-radius: 8px;
            border: 1px solid #f3c6d9;
        }

        /* ── Customer avatar block ── */
        .customer-hero {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            background: #fff9fb;
            border-bottom: 1px solid #f3e0ea;
        }

        .avatar-lg {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .customer-hero-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #3d2233;
            line-height: 1.3;
        }

        .customer-hero-email {
            font-size: 0.82rem;
            color: #9e6b7e;
            margin-top: 2px;
        }

        /* ── Status pills ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .status-pill.pending  { background: #fff8e1; color: #d97706; border: 1px solid #fde68a; }
        .status-pill.accepted { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .status-pill.rejected { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        /* ── Remark form ── */
        .form-label-bpms {
            font-size: 0.8rem;
            font-weight: 600;
            color: #9e6b7e;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-bpms {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #eedde8;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: #3d2233;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            resize: vertical;
        }

        .form-control-bpms:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 3px rgba(233,30,99,.1);
        }

        .form-group-bpms { margin-bottom: 18px; }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 28px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover { opacity: .9; transform: translateY(-1px); }

        /* ── Remark display (already submitted) ── */
        .remark-box {
            background: #fdf6f9;
            border: 1.5px solid #f3e0ea;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 0.88rem;
            color: #3d2233;
            line-height: 1.6;
            min-height: 60px;
        }

        /* ── Back button ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: #fff;
            border: 1.5px solid #eedde8;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            color: #9e6b7e;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back:hover {
            background: #fdf0f5;
            border-color: #e91e63;
            color: #e91e63;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .dashboard-wrapper { margin-left: 0; }
        }

        @media (max-width: 576px) {
            .page-content { padding: 20px 14px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Header / Navbar -->
    <?php include_once('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="dashboard-wrapper">
        <div class="page-content">

            <!-- ── Page Header ── -->
            <div class="page-header">
                <div class="page-header-left">
                    <h2><i class="bi bi-eye me-2"></i>View Appointment</h2>
                    <p>Full appointment details and admin action panel</p>
                </div>
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <a href="all-appointment.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i> All Appointments
                    </a>
                    <nav class="breadcrumb-nav">
                        <a href="dashboard.php"><i class="bi bi-house-door me-1"></i>Dashboard</a>
                        <span class="mx-2">/</span>
                        <a href="all-appointment.php">Appointments</a>
                        <span class="mx-2">/</span>
                        <span>View</span>
                    </nav>
                </div>
            </div>

            <?php
            /* ── Fetch appointment details — QUERY UNCHANGED ── */
            $cid = $_GET['viewid'];
            $ret = mysqli_query($con, "SELECT tbluser.FirstName, tbluser.LastName, tbluser.Email,
                        tbluser.MobileNumber, tblbook.ID as bid, tblbook.AptNumber,
                        tblbook.AptDate, tblbook.AptTime, tblbook.Message,
                        tblbook.BookingDate, tblbook.Remark, tblbook.Status, tblbook.RemarkDate
                   FROM tblbook
                   JOIN tbluser ON tbluser.ID = tblbook.UserID
                   WHERE tblbook.ID='$cid'");
            $cnt = 1;

            while ($row = mysqli_fetch_array($ret)) :
                /* Avatar initials */
                $initials = strtoupper(
                    substr($row['FirstName'], 0, 1) .
                    substr($row['LastName'],  0, 1)
                );

                /* Status styling — mirrors original logic */
                $status_val = $row['Status'];
                if ($status_val == '')         { $pill_class = 'pending';  $status_text = 'Not Updated Yet'; }
                elseif ($status_val == 'Selected') { $pill_class = 'accepted'; $status_text = 'Selected / Approved'; }
                elseif ($status_val == 'Rejected') { $pill_class = 'rejected'; $status_text = 'Rejected'; }
                else                           { $pill_class = 'pending';  $status_text = htmlspecialchars($status_val); }
            ?>

            <!-- ── Two-column grid ── -->
            <div class="apt-grid">

                <!-- ── LEFT: Appointment Details card ── -->
                <div class="card-bpms">
                    <div class="card-bpms-header pink">
                        <i class="bi bi-calendar3-event-fill"></i>
                        <h5>Appointment Details</h5>
                    </div>

                    <!-- Customer hero strip -->
                    <div class="customer-hero">
                        <div class="avatar-lg"><?php echo $initials; ?></div>
                        <div>
                            <div class="customer-hero-name">
                                <?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                            </div>
                            <div class="customer-hero-email">
                                <i class="bi bi-envelope-fill me-1" style="color:#e91e63;"></i>
                                <?php echo htmlspecialchars($row['Email']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-bpms">

                        <!-- Apt Number -->
                        <div class="info-row">
                            <div class="info-icon"><i class="bi bi-tag-fill"></i></div>
                            <div>
                                <div class="info-label">Appointment Number</div>
                                <span class="apt-number-badge">
                                    <i class="bi bi-hash"></i>
                                    <?php echo htmlspecialchars($row['AptNumber']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="info-row">
                            <div class="info-icon blue"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <div class="info-label">Mobile Number</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['MobileNumber']); ?></div>
                            </div>
                        </div>

                        <!-- Appointment Date -->
                        <div class="info-row">
                            <div class="info-icon purple"><i class="bi bi-calendar-event-fill"></i></div>
                            <div>
                                <div class="info-label">Appointment Date</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['AptDate']); ?></div>
                            </div>
                        </div>

                        <!-- Appointment Time -->
                        <div class="info-row">
                            <div class="info-icon amber"><i class="bi bi-clock-fill"></i></div>
                            <div>
                                <div class="info-label">Appointment Time</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['AptTime']); ?></div>
                            </div>
                        </div>

                        <!-- Booking / Apply Date -->
                        <div class="info-row">
                            <div class="info-icon green"><i class="bi bi-calendar-check-fill"></i></div>
                            <div>
                                <div class="info-label">Applied / Booking Date</div>
                                <div class="info-value"><?php echo htmlspecialchars($row['BookingDate']); ?></div>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="info-row">
                            <div class="info-icon"><i class="bi bi-info-circle-fill"></i></div>
                            <div>
                                <div class="info-label">Current Status</div>
                                <span class="status-pill <?php echo $pill_class; ?>">
                                    <span class="status-dot"></span>
                                    <?php echo $status_text; ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($row['Message'])) : ?>
                        <!-- Message -->
                        <div class="info-row">
                            <div class="info-icon purple"><i class="bi bi-chat-text-fill"></i></div>
                            <div>
                                <div class="info-label">Customer Message</div>
                                <div class="info-value" style="font-size:0.85rem; color:#5c3a4e; line-height:1.5;">
                                    <?php echo htmlspecialchars($row['Message']); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div><!-- /.card-body-bpms -->
                </div><!-- /.card-bpms LEFT -->

                <!-- ── RIGHT: Action / Remark card ── -->
                <div>

                    <?php if ($row['Status'] == "") : ?>
                    <!-- ── STATUS EMPTY: show the remark + status form — LOGIC UNCHANGED ── -->
                    <div class="card-bpms">
                        <div class="card-bpms-header purple">
                            <i class="bi bi-pencil-square"></i>
                            <h5>Admin Action — Update Status</h5>
                        </div>
                        <div class="card-body-bpms">

                            <form name="submit" method="post" enctype="multipart/form-data">

                                <!-- Remark textarea -->
                                <div class="form-group-bpms">
                                    <label class="form-label-bpms">
                                        <i class="bi bi-chat-left-text me-1"></i>Remark / Note
                                    </label>
                                    <textarea
                                        name="remark"
                                        rows="5"
                                        class="form-control-bpms"
                                        placeholder="Enter your remark or reason here…"
                                        required></textarea>
                                </div>

                                <!-- Status select -->
                                <div class="form-group-bpms">
                                    <label class="form-label-bpms">
                                        <i class="bi bi-toggle2-on me-1"></i>Update Status
                                    </label>
                                    <select name="status" class="form-control-bpms" required>
                                        <option value="">— Select Status —</option>
                                        <option value="Selected">✅ Approved</option>
                                        <option value="Rejected">❌ Rejected</option>
                                    </select>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" name="submit" class="btn-submit">
                                    <i class="bi bi-check2-circle"></i>
                                    Submit Update
                                </button>

                            </form>
                        </div>
                    </div>

                    <?php else : ?>
                    <!-- ── STATUS SET: show remark / status / remark date — LOGIC UNCHANGED ── -->
                    <div class="card-bpms">
                        <div class="card-bpms-header <?php echo ($row['Status'] == 'Selected') ? 'green' : 'pink'; ?>">
                            <i class="bi bi-clipboard2-check-fill"></i>
                            <h5>Admin Remark & Decision</h5>
                        </div>
                        <div class="card-body-bpms">

                            <!-- Remark -->
                            <div class="info-row">
                                <div class="info-icon purple"><i class="bi bi-chat-square-text-fill"></i></div>
                                <div style="flex:1;">
                                    <div class="info-label">Remark</div>
                                    <div class="remark-box"><?php echo htmlspecialchars($row['Remark']); ?></div>
                                </div>
                            </div>

                            <!-- Final Status -->
                            <div class="info-row">
                                <div class="info-icon"><i class="bi bi-patch-check-fill"></i></div>
                                <div>
                                    <div class="info-label">Final Status</div>
                                    <span class="status-pill <?php echo $pill_class; ?>">
                                        <span class="status-dot"></span>
                                        <?php echo $status_text; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Remark Date -->
                            <div class="info-row">
                                <div class="info-icon green"><i class="bi bi-calendar2-check-fill"></i></div>
                                <div>
                                    <div class="info-label">Remark / Decision Date</div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['RemarkDate']); ?></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- /.RIGHT -->

            </div><!-- /.apt-grid -->

            <?php endwhile; ?>

        </div><!-- /.page-content -->

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
    </div><!-- /.dashboard-wrapper -->

</body>
</html>
<?php } ?>
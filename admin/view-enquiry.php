<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    $enq_vid = intval($_GET['viewid'] ?? 0);
    if (!$enq_vid) { header('location:unreadenq.php'); exit; }

    /* ── Delete handler (runs before fetch so we exit before any query/var conflict) ── */
    if (isset($_GET['delid'])) {
        $enq_did = intval($_GET['delid']);
        mysqli_query($con, "DELETE FROM tblcontact WHERE ID='$enq_did'");
        header('location:readenq.php');
        exit;
    }

    /* ── Mark as read (original logic preserved) ── */
    mysqli_query($con, "UPDATE tblcontact SET IsRead='1' WHERE ID='$enq_vid'");

    /* ── Fetch enquiry ──
       All variables use $enq_ prefix so includes/header.php, sidebar.php,
       and footer.php can never overwrite them (they commonly reuse generic
       names like $email, $row, $ret, $date for the logged-in admin's data). ── */
    $enq_ret = mysqli_query($con, "SELECT * FROM tblcontact WHERE ID='$enq_vid'");
    $enq_row = mysqli_fetch_array($enq_ret);
    if (!$enq_row) { header('location:unreadenq.php'); exit; }

    $enq_fullName = htmlspecialchars($enq_row['FirstName'] . ' ' . $enq_row['LastName']);
    $enq_initial  = strtoupper(substr($enq_row['FirstName'], 0, 1));
    $enq_email    = htmlspecialchars($enq_row['Email']);
    $enq_phone    = !empty($enq_row['Phone']) ? htmlspecialchars($enq_row['Phone']) : '—';
    $enq_date     = htmlspecialchars($enq_row['EnquiryDate']);
    $enq_message  = nl2br(htmlspecialchars($enq_row['Message']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS | View Enquiry — <?php echo $enq_fullName; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:        #e91e63;
            --primary-dark:   #c2185b;
            --secondary:      #ff4f81;
            --primary-light:  #fce4ec;
            --primary-muted:  #fdf0f5;
            --border-soft:    #f3e0ea;
            --text-dark:      #1f2937;
            --text-muted:     #9e9e9e;
            --surface:        #ffffff;
            --page-bg:        #fdf6fb;
            --success:        #10b981;
            --success-light:  #d1fae5;
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
            --info:           #3b82f6;
            --info-light:     #dbeafe;
            --slate:          #64748b;
            --slate-light:    #f1f5f9;
            --radius-card:    16px;
            --radius-btn:     10px;
            --shadow-card:    0 2px 20px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        /* ── Layout — matches 280px sidebar + 70px topbar ── */
        .dashboard-wrapper {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
            transition: margin-left .3s;
        }
        .dashboard-wrapper.full-width { margin-left: 0; }
        @media (max-width: 991px) { .dashboard-wrapper { margin-left: 0; } }

        .page-content { padding: 2rem 2rem 3rem; }
        @media (max-width: 768px) { .page-content { padding: 1.25rem 1rem 2rem; } }

        /* ── Breadcrumb ── */
        .bpms-breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12.5px; color: var(--text-muted);
            margin-bottom: 1.5rem; flex-wrap: wrap;
        }
        .bpms-breadcrumb a {
            color: var(--primary); text-decoration: none; font-weight: 500;
        }
        .bpms-breadcrumb a:hover { text-decoration: underline; }
        .bpms-breadcrumb .current { color: var(--primary); font-weight: 600; }
        .bpms-breadcrumb .sep { font-size: .7rem; opacity: .4; }

        /* ── Hero card ── */
        .enquiry-hero {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            padding: 28px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        /* Decorative gradient accent strip */
        .enquiry-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), #fb923c);
        }

        .hero-left { display: flex; align-items: center; gap: 18px; }

        .hero-avatar {
            width: 72px; height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(233,30,99,.28);
            letter-spacing: -1px;
        }

        .hero-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem; font-weight: 700;
            color: var(--text-dark); margin-bottom: 6px; line-height: 1.2;
        }

        .hero-meta {
            display: flex; align-items: center; gap: 14px;
            flex-wrap: wrap; margin-bottom: 10px;
        }
        .hero-meta-item {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 13px; color: var(--text-muted);
        }
        .hero-meta-item i { color: var(--primary); font-size: .8rem; }
        .hero-meta-item a { color: var(--text-muted); text-decoration: none; }
        .hero-meta-item a:hover { color: var(--primary); }

        .status-read {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--success-light); color: #065f46;
            font-size: 11.5px; font-weight: 600;
            padding: 4px 12px; border-radius: 20px;
        }
        .status-read i { font-size: .7rem; }

        /* ── Action buttons ── */
        .hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 7px;
            background: transparent;
            border: 1.5px solid var(--border-soft);
            border-radius: var(--radius-btn);
            padding: 9px 18px;
            font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            color: var(--text-dark); text-decoration: none;
            transition: border-color .2s, background .2s, color .2s, transform .15s;
        }
        .btn-back:hover {
            border-color: var(--primary); color: var(--primary);
            background: var(--primary-muted); transform: translateY(-1px);
        }

        .btn-reply {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--info-light); color: var(--info);
            border: none; border-radius: var(--radius-btn);
            padding: 9px 18px; font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
        }
        .btn-reply:hover { background: var(--info); color: #fff; transform: translateY(-1px); }

        .btn-delete {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--danger-light); color: var(--danger);
            border: none; border-radius: var(--radius-btn);
            padding: 9px 18px; font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
        }
        .btn-delete:hover { background: var(--danger); color: #fff; transform: translateY(-1px); }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 1.25rem;
        }
        @media (max-width: 900px)  { .info-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px)  { .info-grid { grid-template-columns: 1fr; } }

        .info-card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: var(--shadow-card);
            display: flex; align-items: flex-start; gap: 14px;
            transition: box-shadow .2s, transform .2s;
        }
        .info-card:hover {
            box-shadow: 0 6px 24px rgba(233,30,99,.11);
            transform: translateY(-2px);
        }

        .info-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.05rem; flex-shrink: 0;
        }
        .info-icon.pink  { background: var(--primary-light); color: var(--primary); }
        .info-icon.blue  { background: var(--info-light);    color: var(--info); }
        .info-icon.green { background: var(--success-light); color: var(--success); }
        .info-icon.slate { background: var(--slate-light);   color: var(--slate); }

        .info-label {
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: var(--text-muted); margin-bottom: 6px;
        }
        .info-value {
            font-size: 13.5px; font-weight: 600; color: var(--text-dark);
            word-break: break-all; line-height: 1.4;
        }
        .info-value a { color: var(--text-dark); text-decoration: none; }
        .info-value a:hover { color: var(--primary); }

        /* ── Message card ── */
        .bpms-card {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .bpms-card-header {
            padding: 14px 22px;
            border-bottom: 1px solid var(--border-soft);
            background: var(--primary-muted);
            display: flex; align-items: center; gap: 10px;
        }
        .card-header-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: var(--primary-light);
            display: flex; align-items: center; justify-content: center;
        }
        .card-header-icon i { font-size: .95rem; color: var(--primary); }
        .bpms-card-header h5 {
            font-size: 14px; font-weight: 600;
            color: var(--text-dark); margin: 0; flex: 1;
        }
        .card-badge {
            font-size: 11px; font-weight: 600;
            color: var(--primary); background: var(--primary-light);
            padding: 3px 10px; border-radius: 20px;
        }

        /* ── Message body ── */
        .message-body { padding: 28px 28px 0; }

        .message-bubble {
            background: var(--page-bg);
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 28px 28px 24px;
            position: relative;
            font-size: 14.5px;
            line-height: 1.85;
            color: var(--text-dark);
        }

        /* Opening quote mark */
        .message-bubble::before {
            content: '\201C';
            font-family: 'Playfair Display', serif;
            font-size: 5rem; line-height: 1;
            color: var(--primary-light);
            position: absolute;
            top: -10px; left: 18px;
            pointer-events: none;
            user-select: none;
        }

        .message-bubble .bubble-text { position: relative; z-index: 1; }

        /* ── Meta footer strip ── */
        .message-footer {
            display: flex; align-items: center; gap: 6px;
            flex-wrap: wrap;
            padding: 18px 28px 22px;
            border-top: 1px solid var(--border-soft);
            margin-top: 22px;
        }
        .meta-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-muted);
            color: #7b1e4a;
            font-size: 12px; font-weight: 500;
            padding: 4px 12px; border-radius: 20px;
        }
        .meta-pill i { font-size: .75rem; color: var(--primary); }
        .meta-divider { color: var(--border-soft); font-size: .7rem; }

        /* ── Reply CTA strip ── */
        .reply-cta {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
            background: linear-gradient(135deg, var(--primary-light), #fff0f6);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-card);
            padding: 20px 24px;
            margin-top: 1.25rem;
        }
        .reply-cta-left { display: flex; align-items: center; gap: 12px; }
        .reply-cta-icon {
            width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 5px 14px rgba(233,30,99,.28);
        }
        .reply-cta h6 {
            font-size: 14px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px;
        }
        .reply-cta p { font-size: 12.5px; color: var(--text-muted); }

        .btn-reply-cta {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--primary); color: #fff;
            border: none; border-radius: var(--radius-btn);
            padding: 10px 22px; font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            white-space: nowrap;
        }
        .btn-reply-cta:hover {
            background: var(--primary-dark); color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(233,30,99,.3);
        }

        /* ── Delete confirm modal ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: 20px;
            padding: 32px 28px; max-width: 380px; width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            animation: popIn .25s ease;
        }
        @keyframes popIn {
            from { transform: scale(.88); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal-icon {
            width: 60px; height: 60px; border-radius: 16px;
            background: var(--danger-light); color: var(--danger);
            font-size: 1.6rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .modal-box h5 { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .modal-box p  { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; }
        .modal-name   { color: var(--primary); font-weight: 600; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }
        .modal-cancel {
            flex: 1; padding: 10px; border-radius: 10px;
            border: 1.5px solid var(--border-soft);
            background: transparent; font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            color: var(--text-dark); transition: background .2s;
        }
        .modal-cancel:hover { background: #f5f5f5; }
        .modal-confirm {
            flex: 1; padding: 10px; border-radius: 10px;
            border: none; font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            background: var(--danger); color: #fff;
            transition: background .2s;
        }
        .modal-confirm:hover { background: #dc2626; }
    </style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<!-- ── Delete confirm modal ── -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="bi bi-trash3-fill"></i></div>
        <h5>Delete Enquiry?</h5>
        <p>
            You're about to permanently delete the enquiry from
            <span class="modal-name"><?php echo $enq_fullName; ?></span>.
            This action cannot be undone.
        </p>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <a href="view-enquiry.php?viewid=<?php echo $enq_vid; ?>&delid=<?php echo $enq_vid; ?>"
               class="modal-confirm">
                Yes, Delete
            </a>
        </div>
    </div>
</div>

<!-- ── Dashboard wrapper ── -->
<div class="dashboard-wrapper" id="dashboard-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <nav class="bpms-breadcrumb">
            <i class="bi bi-house-door" style="font-size:.8rem;"></i>
            <a href="dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right sep"></i>
            <span>Enquiry</span>
            <i class="bi bi-chevron-right sep"></i>
            <a href="readenq.php">Read Enquiries</a>
            <i class="bi bi-chevron-right sep"></i>
            <span class="current">View Enquiry</span>
        </nav>

        <!-- ── Hero card ── -->
        <div class="enquiry-hero">
            <div class="hero-left">
                <div class="hero-avatar"><?php echo $enq_initial; ?></div>
                <div>
                    <div class="hero-name"><?php echo $enq_fullName; ?></div>
                    <div class="hero-meta">
                        <span class="hero-meta-item">
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:<?php echo $enq_email; ?>"><?php echo $enq_email; ?></a>
                        </span>
                        <?php if ($enq_phone !== '—'): ?>
                        <span class="hero-meta-item">
                            <i class="bi bi-telephone"></i>
                            <?php echo $enq_phone; ?>
                        </span>
                        <?php endif; ?>
                        <span class="hero-meta-item">
                            <i class="bi bi-calendar3"></i>
                            <?php echo $enq_date; ?>
                        </span>
                    </div>
                    <span class="status-read">
                        <i class="bi bi-check-circle-fill"></i>
                        Marked as Read
                    </span>
                </div>
            </div>
            <div class="hero-actions">
                <a href="javascript:history.back()" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
                <a href="mailto:<?php echo $enq_email; ?>" class="btn-reply">
                    <i class="bi bi-reply-fill"></i>
                    Reply
                </a>
                <button class="btn-delete" onclick="openModal()">
                    <i class="bi bi-trash3"></i>
                    Delete
                </button>
            </div>
        </div>

        <!-- ── Info grid ── -->
        <div class="info-grid">

            <div class="info-card">
                <div class="info-icon pink"><i class="bi bi-person-fill"></i></div>
                <div>
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo $enq_fullName; ?></div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon blue"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <a href="mailto:<?php echo $enq_email; ?>"><?php echo $enq_email; ?></a>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon green"><i class="bi bi-telephone-fill"></i></div>
                <div>
                    <div class="info-label">Phone Number</div>
                    <div class="info-value"><?php echo $enq_phone; ?></div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon slate"><i class="bi bi-calendar3"></i></div>
                <div>
                    <div class="info-label">Enquiry Date</div>
                    <div class="info-value"><?php echo $enq_date; ?></div>
                </div>
            </div>

        </div>

        <!-- ── Message card ── -->
        <div class="bpms-card">

            <div class="bpms-card-header">
                <div class="card-header-icon"><i class="bi bi-chat-quote-fill"></i></div>
                <h5>Customer Message</h5>
                <span class="card-badge">
                    <i class="bi bi-envelope-open" style="font-size:.75rem;margin-right:3px;"></i>
                    Read
                </span>
            </div>

            <div class="message-body">
                <div class="message-bubble">
                    <div class="bubble-text"><?php echo $enq_message; ?></div>
                </div>
            </div>

            <div class="message-footer">
                <span class="meta-pill">
                    <i class="bi bi-person"></i>
                    <?php echo $enq_fullName; ?>
                </span>
                <span class="meta-divider">·</span>
                <span class="meta-pill">
                    <i class="bi bi-calendar3"></i>
                    <?php echo $enq_date; ?>
                </span>
                <span class="meta-divider">·</span>
                <span class="meta-pill">
                    <i class="bi bi-check2-circle"></i>
                    Reviewed
                </span>
            </div>

        </div>

        <!-- ── Reply CTA ── -->
        <div class="reply-cta">
            <div class="reply-cta-left">
                <div class="reply-cta-icon"><i class="bi bi-reply-fill"></i></div>
                <div>
                    <h6>Ready to respond?</h6>
                    <p>Send a reply directly to <?php echo $enq_fullName; ?> at <?php echo $enq_email; ?></p>
                </div>
            </div>
            <a href="mailto:<?php echo $enq_email; ?>?subject=Re: Your Enquiry" class="btn-reply-cta">
                <i class="bi bi-send-fill"></i>
                Send Reply
            </a>
        </div>

    </div><!-- /page-content -->
</div><!-- /dashboard-wrapper -->

<?php include_once('includes/footer.php'); ?>

<script>
function openModal()  { document.getElementById('deleteModal').classList.add('show');    }
function closeModal() { document.getElementById('deleteModal').classList.remove('show'); }

/* Close on backdrop click */
document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>
<?php } ?>
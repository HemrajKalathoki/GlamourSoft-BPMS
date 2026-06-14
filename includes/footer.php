<?php
/* ── contact info ── */
$contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus' LIMIT 1");
$contact      = mysqli_fetch_assoc($contactQuery);

/* ── real services from DB (max 6 for footer) ── */
$svcQuery      = mysqli_query($con, "SELECT ID, ServiceName FROM tblservices ORDER BY ID ASC LIMIT 6");
$footerSvcs    = [];
while ($s = mysqli_fetch_assoc($svcQuery)) {
    $footerSvcs[] = $s;
}
?>

<!-- PRE-FOOTER CTA STRIP -->
<div class="pre-footer-cta">
    <div class="container">
        <div class="pf-inner">
            <div class="pf-text">
                <h4>Ready for Your Glow-Up?</h4>
                <p>Book your session today — walk in, walk out confident.</p>
            </div>
            <div class="pf-actions">
                <a href="book-appointment.php" class="pf-btn-primary">
                    <i class="bi bi-calendar-heart"></i>
                    Book Appointment
                </a>
                <a href="services.php" class="pf-btn-ghost">
                    <i class="bi bi-grid-3x3-gap"></i>
                    View Services
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MAIN FOOTER -->
<footer class="footer-section">

    <!-- top gradient accent line -->
    <div class="footer-top-line"></div>

    <div class="container">

        <div class="row g-5">

            <!-- ── BRAND ── -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">

                    <a href="index.php" class="footer-logo">
                        <i class="bi bi-scissors"></i>
                        Glamour<span>Soft</span>
                    </a>

                    <p>
                        GlamourSoft is a modern beauty parlour management
                        platform built for salons, spas, and wellness businesses
                        across Nepal. Your confidence is our craft.
                    </p>

                    <!-- ⚠ Update these href values with your real social profile URLs -->
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook" title="Follow us on Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" aria-label="Instagram" title="Follow us on Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" aria-label="TikTok" title="Follow us on TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="#" aria-label="YouTube" title="Subscribe on YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <!-- ── QUICK LINKS ── -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="bi bi-chevron-right"></i>Home</a></li>
                    <li><a href="about.php"><i class="bi bi-chevron-right"></i>About Us</a></li>
                    <li><a href="services.php"><i class="bi bi-chevron-right"></i>Services</a></li>
                    <li><a href="contact.php"><i class="bi bi-chevron-right"></i>Contact</a></li>
                    <li><a href="book-appointment.php"><i class="bi bi-chevron-right"></i>Book Appointment</a></li>
                    <?php if (empty($_SESSION['bpmsuid'])): ?>
                    <li><a href="login.php"><i class="bi bi-chevron-right"></i>Login</a></li>
                    <li><a href="signup.php"><i class="bi bi-chevron-right"></i>Register</a></li>
                    <?php else: ?>
                    <li><a href="booking-history.php"><i class="bi bi-chevron-right"></i>My Bookings</a></li>
                    <li><a href="invoice-history.php"><i class="bi bi-chevron-right"></i>My Invoices</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- ── SERVICES FROM DB ── -->
            <div class="col-lg-3 col-md-6 col-6">
                <h5 class="footer-title">Our Services</h5>
                <ul class="footer-links">
                    <?php if (!empty($footerSvcs)): ?>
                        <?php foreach ($footerSvcs as $svc): ?>
                        <li>
                            <a href="services.php">
                                <i class="bi bi-check-circle"></i>
                                <?php echo htmlspecialchars($svc['ServiceName']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a href="services.php"><i class="bi bi-check-circle"></i>View All Services</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- ── CONTACT ── -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Get In Touch</h5>
                <div class="footer-contact">

                    <?php if (!empty($contact['PageDescription'])): ?>
                    <div class="contact-box">
                        <div class="fc-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="fc-text">
                            <span class="fc-label">Address</span>
                            <p><?php echo htmlspecialchars($contact['PageDescription']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($contact['MobileNumber'])): ?>
                    <div class="contact-box">
                        <div class="fc-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div class="fc-text">
                            <span class="fc-label">Phone</span>
                            <a href="tel:+977<?php echo htmlspecialchars($contact['MobileNumber']); ?>">
                                +977 <?php echo htmlspecialchars($contact['MobileNumber']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($contact['Email'])): ?>
                    <div class="contact-box">
                        <div class="fc-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div class="fc-text">
                            <span class="fc-label">Email</span>
                            <a href="mailto:<?php echo htmlspecialchars($contact['Email']); ?>">
                                <?php echo htmlspecialchars($contact['Email']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($contact['Timing'])): ?>
                    <div class="contact-box">
                        <div class="fc-icon"><i class="bi bi-clock-fill"></i></div>
                        <div class="fc-text">
                            <span class="fc-label">Hours</span>
                            <p><?php echo htmlspecialchars($contact['Timing']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div><!-- /.row -->

        <!-- FOOTER BOTTOM -->
        <div class="footer-bottom">
            <div class="row align-items-center g-2">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; <?php echo date('Y'); ?>
                        <a href="index.php" class="footer-bottom-brand">GlamourSoft</a>
                        &mdash; Beauty Management System
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="bi bi-heart-fill" style="color:#e91e63;font-size:.75rem;"></i>
                        Crafted in Dang, Nepal &nbsp;🇳🇵
                    </p>
                </div>
            </div>
        </div>

    </div>
</footer>

<style>
    /* ─── PRE-FOOTER CTA ──────────────────────────────────── */
    .pre-footer-cta {
        background: linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);
        padding: 44px 0;
    }
    .pf-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }
    .pf-text h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }
    .pf-text p {
        color: rgba(255,255,255,.82);
        margin: 0;
        font-size: .92rem;
    }
    .pf-actions { display: flex; gap: 12px; flex-wrap: wrap; }

    .pf-btn-primary {
        display: inline-flex;
        align-items: center; gap: 7px;
        background: #fff;
        color: #e91e63;
        border-radius: 30px;
        padding: 12px 26px;
        font-size: .88rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(0,0,0,.15);
        transition: transform .2s, box-shadow .2s;
        white-space: nowrap;
    }
    .pf-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0,0,0,.2);
        color: #c2185b;
    }

    .pf-btn-ghost {
        display: inline-flex;
        align-items: center; gap: 7px;
        background: rgba(255,255,255,.15);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.5);
        border-radius: 30px;
        padding: 12px 24px;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s, border-color .2s;
        white-space: nowrap;
    }
    .pf-btn-ghost:hover {
        background: rgba(255,255,255,.25);
        border-color: rgba(255,255,255,.8);
        color: #fff;
    }

    /* ─── FOOTER ──────────────────────────────────────────── */
    .footer-section {
        background: #0f172a;
        color: #94a3b8;
        padding: 72px 0 0;
        position: relative;
        font-family: 'Poppins', sans-serif;
    }

    /* 3-px gradient accent at very top */
    .footer-top-line {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #e91e63, #ff4f81, #e91e63);
    }

    /* ── brand ── */
    .footer-logo {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 1.55rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        margin-bottom: 18px;
    }
    .footer-logo i  { color: #e91e63; }
    .footer-logo span { font-weight: 500; color: #e2e8f0; }

    .footer-brand p {
        line-height: 1.85;
        color: #64748b;
        font-size: .88rem;
        margin-bottom: 0;
    }

    .footer-social {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }
    .footer-social a {
        width: 40px; height: 40px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8;
        font-size: .95rem;
        text-decoration: none;
        transition: background .22s, color .22s, transform .22s, border-color .22s;
    }
    .footer-social a:hover {
        background: #e91e63;
        border-color: #e91e63;
        color: #fff;
        transform: translateY(-4px);
    }

    /* ── section title ── */
    .footer-title {
        color: #f1f5f9;
        font-weight: 700;
        font-size: .95rem;
        margin-bottom: 28px;
        position: relative;
        padding-bottom: 12px;
    }
    .footer-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: 0;
        width: 36px; height: 2.5px;
        background: linear-gradient(90deg, #e91e63, #ff4f81);
        border-radius: 10px;
    }

    /* ── links ── */
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 11px; }
    .footer-links a {
        color: #64748b;
        text-decoration: none;
        font-size: .86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: color .2s, padding-left .2s;
    }
    .footer-links a:hover {
        color: #fff;
        padding-left: 4px;
    }
    .footer-links a i {
        color: #e91e63;
        font-size: .72rem;
        flex-shrink: 0;
    }

    /* ── contact boxes ── */
    .footer-contact { display: flex; flex-direction: column; gap: 18px; }

    .contact-box {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .fc-icon {
        width: 38px; height: 38px;
        background: rgba(233,30,99,.12);
        border: 1px solid rgba(233,30,99,.2);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #e91e63;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .fc-label {
        display: block;
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #475569;
        margin-bottom: 2px;
    }
    .fc-text p,
    .fc-text a {
        margin: 0;
        color: #64748b;
        font-size: .84rem;
        line-height: 1.6;
        text-decoration: none;
        transition: color .2s;
    }
    .fc-text a:hover { color: #fff; }

    /* ── bottom bar ── */
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.06);
        margin-top: 56px;
        padding: 22px 0 24px;
        font-size: .82rem;
        color: #475569;
    }
    .footer-bottom-brand {
        color: #e91e63;
        text-decoration: none;
        font-weight: 600;
    }
    .footer-bottom-brand:hover { color: #ff4f81; }

    /* ─── RESPONSIVE ────────────────────────────────────────── */
    @media (max-width: 767px) {
        .pre-footer-cta { padding: 32px 0; }
        .pf-inner { text-align: center; justify-content: center; }
        .pf-text h4 { font-size: 1.2rem; }

        .footer-section { padding: 56px 0 0; text-align: center; }

        .footer-title::after { left: 50%; transform: translateX(-50%); }

        .footer-logo { justify-content: center; }

        .footer-social { justify-content: center; }

        .footer-links a { justify-content: center; }

        .contact-box { flex-direction: column; align-items: center; text-align: center; }

        .footer-bottom { text-align: center; }
        .footer-bottom .col-md-6:last-child { margin-top: 6px; }
    }
</style>
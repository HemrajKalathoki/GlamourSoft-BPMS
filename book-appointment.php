<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsuid']==0)) {
    header('location:logout.php');
} else {

if (isset($_POST['submit'])) {
    $uid      = $_SESSION['bpmsuid'];
    $adate    = $_POST['adate'];
    $atime    = $_POST['atime'];
    $msg      = $_POST['message'];
    $aptnumber = mt_rand(100000000, 999999999);

    $query = mysqli_query($con, "INSERT INTO tblbook(UserID,AptNumber,AptDate,AptTime,Message)
                                  VALUES('$uid','$aptnumber','$adate','$atime','$msg')");
    if ($query) {
        $ret    = mysqli_query($con, "SELECT AptNumber FROM tblbook
                                      WHERE tblbook.UserID='$uid'
                                      ORDER BY ID DESC LIMIT 1");
        $result = mysqli_fetch_array($ret);
        $_SESSION['aptno'] = $result['AptNumber'];
        echo "<script>window.location.href='thank-you.php'</script>";
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
    <title>Book Appointment | GlamourSoft</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────── */
        :root {
            --pink:         #e91e63;
            --pink-mid:     #ff4f81;
            --pink-light:   #fce4ec;
            --pink-dark:    #c2185b;
            --gradient:     linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);
            --surface:      #fdf5f8;
            --card:         #ffffff;
            --text-dark:    #1a1a1a;
            --text-mid:     #555;
            --text-muted:   #999;
            --border-soft:  #f0eaee;
            --radius-xl:    24px;
            --radius-md:    14px;
            --radius-sm:    10px;
            --shadow-card:  0 8px 40px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ────────────────────────────────── */
        .apt-hero {
            background: var(--gradient);
            padding: 72px 0 52px;
            position: relative;
            overflow: hidden;
        }

        .apt-hero .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            pointer-events: none;
        }
        .b1 { width:340px; height:340px; top:-80px;   right:-60px; }
        .b2 { width:180px; height:180px; bottom:-60px; left:6%;    }
        .b3 { width: 70px; height: 70px; top:28px;    left:42%;    }

        .hero-icon-ring {
            width: 62px;
            height: 62px;
            background: rgba(255,255,255,.18);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }

        .apt-hero h1 {
            font-size: clamp(1.55rem, 3vw, 2.3rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        .apt-hero .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: .92rem;
            font-weight: 300;
            margin: 0;
        }

        /* breadcrumb */
        .apt-hero .breadcrumb { background: transparent; margin: 12px 0 0; padding: 0; }
        .apt-hero .breadcrumb-item a  { color: rgba(255,255,255,.72); text-decoration: none; font-size: .8rem; }
        .apt-hero .breadcrumb-item a:hover { color: #fff; }
        .apt-hero .breadcrumb-item.active { color: rgba(255,255,255,.95); font-size: .8rem; }
        .apt-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.45); }

        /* ─── MAIN SECTION ────────────────────────── */
        .apt-main { padding: 56px 0 88px; }

        /* ─── SIDEBAR CARDS ───────────────────────── */
        .s-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            padding: 26px 28px;
            box-shadow: var(--shadow-card);
            margin-bottom: 20px;
        }

        .s-card-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: var(--pink);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* contact rows */
        .c-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }
        .c-row:last-child { margin-bottom: 0; }

        .c-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--pink-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .c-icon .bi { color: var(--pink); font-size: 1rem; }

        .c-meta-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--text-muted);
            margin-bottom: 2px;
        }
        .c-meta-val {
            font-size: .875rem;
            color: var(--text-dark);
            font-weight: 500;
            text-decoration: none;
            word-break: break-word;
            display: block;
        }
        .c-meta-val:hover { color: var(--pink); }

        /* perks */
        .perk {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: var(--radius-sm);
            background: var(--pink-light);
            margin-bottom: 8px;
            transition: .2s;
        }
        .perk:last-child { margin-bottom: 0; }
        .perk:hover { background: #f8bbd0; }
        .perk .bi { color: var(--pink); font-size: 1rem; flex-shrink: 0; }
        .perk span { font-size: .82rem; font-weight: 500; color: #444; }

        /* ─── FORM CARD ───────────────────────────── */
        .f-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            padding: 42px 46px;
            box-shadow: var(--shadow-card);
        }

        .f-card-heading {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }
        .f-card-sub {
            font-size: .85rem;
            color: var(--text-muted);
            margin-bottom: 28px;
        }

        /* notice box */
        .notice-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fff8f0;
            border: 1.5px solid #ffe0b2;
            border-radius: var(--radius-sm);
            padding: 12px 15px;
            margin-bottom: 32px;
        }
        .notice-box .bi { color: #e65100; font-size: .9rem; margin-top: 1px; flex-shrink: 0; }
        .notice-box p { font-size: .79rem; color: #bf360c; margin: 0; line-height: 1.55; }

        /* step markers */
        .step-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            font-size: .73rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(233,30,99,.25);
        }
        .step-lbl {
            font-size: .87rem;
            font-weight: 600;
            color: #333;
        }

        /* form controls */
        .f-label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 7px;
        }

        .f-wrap { position: relative; margin-bottom: 26px; }

        .f-wrap .f-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--pink);
            font-size: .95rem;
            pointer-events: none;
            z-index: 2;
        }
        .f-wrap.ta .f-icon {
            top: 15px;
            transform: none;
        }

        .f-wrap input,
        .f-wrap textarea {
            width: 100%;
            border: 2px solid var(--border-soft);
            border-radius: 12px;
            padding: 13px 16px 13px 42px;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem;
            color: var(--text-dark);
            background: var(--surface);
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
            -webkit-appearance: none;
            appearance: none;
        }
        .f-wrap textarea {
            resize: vertical;
            min-height: 124px;
        }
        .f-wrap input:focus,
        .f-wrap textarea:focus {
            border-color: var(--pink);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(233,30,99,.07);
        }
        .f-wrap input::placeholder,
        .f-wrap textarea::placeholder { color: #c5b5bc; }

        /* dashed step divider */
        .s-divider {
            border: none;
            border-top: 2px dashed #f0e0e8;
            margin: 24px 0;
        }

        /* submit button */
        .btn-book {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            background: var(--gradient);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            padding: 15px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(233,30,99,.28);
            transition: transform .2s, box-shadow .2s;
            letter-spacing: .3px;
        }
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(233,30,99,.38);
        }
        .btn-book:active { transform: translateY(0); }

        /* back-to-top */
        #movetop {
            display: none;
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(233,30,99,.35);
            font-size: 1.05rem;
            transition: transform .2s;
            z-index: 999;
        }
        #movetop:hover { transform: translateY(-3px); }

        /* ─── RESPONSIVE ──────────────────────────── */
        @media (max-width: 767px) {
            .f-card { padding: 28px 20px; }
            .apt-hero { padding: 56px 0 40px; }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- ══ HERO ══════════════════════════════════════════════ -->
<section class="apt-hero">
    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>

    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="hero-icon-ring">
                <i class="bi bi-calendar-heart fs-3 text-white"></i>
            </div>
            <div>
                <h1>Book an Appointment</h1>
                <p class="hero-sub">Reserve your slot — we'll take care of the rest.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Book Appointment</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ══ MAIN ══════════════════════════════════════════════ -->
<section class="apt-main">
    <div class="container">
        <div class="row g-4 align-items-start">

            <!-- ── SIDEBAR ─────────────────────── -->
            <div class="col-lg-4">

                <!-- Contact Info -->
                <div class="s-card">
                    <div class="s-card-label">
                        <i class="bi bi-geo-alt-fill"></i> Our Details
                    </div>

                    <?php
                    $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
                    while ($row = mysqli_fetch_array($ret)) {
                    ?>

                    <div class="c-row">
                        <div class="c-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <p class="c-meta-label">Phone</p>
                            <a class="c-meta-val" href="tel:+<?php echo $row['MobileNumber']; ?>">
                                +<?php echo $row['MobileNumber']; ?>
                            </a>
                        </div>
                    </div>

                    <div class="c-row">
                        <div class="c-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <p class="c-meta-label">Email</p>
                            <a class="c-meta-val" href="mailto:<?php echo $row['Email']; ?>">
                                <?php echo $row['Email']; ?>
                            </a>
                        </div>
                    </div>

                    <div class="c-row">
                        <div class="c-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <p class="c-meta-label">Address</p>
                            <span class="c-meta-val"><?php echo $row['PageDescription']; ?></span>
                        </div>
                    </div>

                    <div class="c-row">
                        <div class="c-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <p class="c-meta-label">Working Hours</p>
                            <span class="c-meta-val"><?php echo $row['Timing']; ?></span>
                        </div>
                    </div>

                    <?php } ?>
                </div>

                <!-- Why Book Here -->
                <div class="s-card">
                    <div class="s-card-label">
                        <i class="bi bi-stars"></i> Why Book Here
                    </div>

                    <div class="perk">
                        <i class="bi bi-shield-check"></i>
                        <span>Instant Booking Confirmation</span>
                    </div>
                    <div class="perk">
                        <i class="bi bi-person-hearts"></i>
                        <span>Expert Beauty Professionals</span>
                    </div>
                    <div class="perk">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Easy Rescheduling Policy</span>
                    </div>
                    <div class="perk">
                        <i class="bi bi-chat-heart"></i>
                        <span>Personalized Service</span>
                    </div>
                    <div class="perk">
                        <i class="bi bi-lock-fill"></i>
                        <span>Secure &amp; Private Booking</span>
                    </div>
                </div>

            </div>

            <!-- ── FORM ────────────────────────── -->
            <div class="col-lg-8">
                <div class="f-card">

                    <h5 class="f-card-heading">Schedule Your Visit</h5>
                    <p class="f-card-sub">Complete the 3 steps below and we'll confirm your slot shortly.</p>

                    <!-- Notice -->
                    <div class="notice-box">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <p>Please book at least <strong>24 hours in advance</strong>. Our team will review and confirm your appointment as soon as possible.</p>
                    </div>

                    <form method="post">

                        <!-- ① Date -->
                        <div class="step-row">
                            <div class="step-dot">1</div>
                            <span class="step-lbl">Choose Your Date</span>
                        </div>

                        <div class="f-wrap">
                            <label class="f-label" for="adate">Appointment Date</label>
                            <i class="bi bi-calendar3 f-icon" style="top:calc(50% + 11px);"></i>
                            <input type="date" name="adate" id="adate" required>
                        </div>

                        <hr class="s-divider">

                        <!-- ② Time -->
                        <div class="step-row">
                            <div class="step-dot">2</div>
                            <span class="step-lbl">Pick a Time Slot</span>
                        </div>

                        <div class="f-wrap">
                            <label class="f-label" for="atime">Appointment Time</label>
                            <i class="bi bi-clock f-icon" style="top:calc(50% + 11px);"></i>
                            <input type="time" name="atime" id="atime" required>
                        </div>

                        <hr class="s-divider">

                        <!-- ③ Message -->
                        <div class="step-row">
                            <div class="step-dot">3</div>
                            <span class="step-lbl">Tell Us What You Need</span>
                        </div>

                        <div class="f-wrap ta">
                            <label class="f-label" for="message">Service Details / Message</label>
                            <i class="bi bi-chat-text f-icon"></i>
                            <textarea name="message" id="message"
                                placeholder="Describe the service(s) you'd like, your preferences, or any special requests…"
                                required></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-book" name="submit">
                            <i class="bi bi-calendar-check"></i>
                            Confirm Appointment
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<!-- Back to top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    /* scroll-to-top button */
    window.onscroll = function () {
        document.getElementById('movetop').style.display =
            (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
                ? 'block' : 'none';
    };
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    /* lock date picker to today-or-later */
    (function () {
        const d   = new Date();
        const y   = d.getFullYear();
        const m   = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        document.getElementById('adate').setAttribute('min', `${y}-${m}-${day}`);
    })();
</script>

</body>
</html>
<?php } ?>
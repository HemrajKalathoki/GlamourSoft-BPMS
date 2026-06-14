<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {

    $alertMsg  = '';
    $alertType = '';   /* 'success' | 'error' */

    if (isset($_POST['change'])) {
        $userid      = $_SESSION['bpmsuid'];
        $cpassword   = md5($_POST['currentpassword']);
        $newpassword = md5($_POST['newpassword']);

        $query1 = mysqli_query($con,
            "SELECT ID FROM tbluser WHERE ID='$userid' AND Password='$cpassword'"
        );
        $row = mysqli_fetch_array($query1);

        if ($row > 0) {
            mysqli_query($con,
                "UPDATE tbluser SET Password='$newpassword' WHERE ID='$userid'"
            );
            $alertMsg  = 'Your password has been changed successfully.';
            $alertType = 'success';
        } else {
            $alertMsg  = 'Your current password is incorrect. Please try again.';
            $alertType = 'error';
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | GlamourSoft</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────── */
        :root {
            --pink:        #e91e63;
            --pink-mid:    #ff4f81;
            --pink-light:  #fce4ec;
            --pink-dark:   #c2185b;
            --gradient:    linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);

            --green:       #10b981;
            --green-light: #d1fae5;
            --amber:       #f59e0b;
            --amber-light: #fef3c7;
            --red:         #ef4444;
            --red-light:   #fee2e2;

            --surface:     #fdf5f8;
            --card:        #ffffff;
            --text-dark:   #1a1a1a;
            --text-mid:    #555;
            --text-muted:  #999;
            --border:      #f0eaee;

            --radius-xl:   24px;
            --radius-md:   14px;
            --radius-sm:   10px;
            --shadow-card: 0 8px 40px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ─────────────────────────────────── */
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
        .b1 { width:340px; height:340px; top:-80px;    right:-60px; }
        .b2 { width:180px; height:180px; bottom:-60px; left:6%;     }
        .b3 { width: 70px; height: 70px; top:28px;     left:42%;    }

        .hero-icon-ring {
            width: 62px; height: 62px;
            background: rgba(255,255,255,.18);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }
        .apt-hero h1 {
            font-size: clamp(1.55rem, 3vw, 2.3rem);
            font-weight: 700; color: #fff;
            margin-bottom: 3px; line-height: 1.2;
        }
        .apt-hero .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: .92rem; font-weight: 300; margin: 0;
        }
        .apt-hero .breadcrumb      { background: transparent; margin: 12px 0 0; padding: 0; }
        .apt-hero .breadcrumb-item a          { color: rgba(255,255,255,.72); text-decoration: none; font-size: .8rem; }
        .apt-hero .breadcrumb-item a:hover    { color: #fff; }
        .apt-hero .breadcrumb-item.active     { color: rgba(255,255,255,.95); font-size: .8rem; }
        .apt-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.45); }

        /* ─── MAIN ──────────────────────────────────── */
        .main-section { padding: 52px 0 80px; }

        /* ─── SIDEBAR CARDS ─────────────────────────── */
        .s-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            padding: 26px 28px;
            box-shadow: var(--shadow-card);
            margin-bottom: 20px;
        }
        .s-card-label {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.3px;
            color: var(--pink); margin-bottom: 20px;
            display: flex; align-items: center; gap: 6px;
        }

        .c-row {
            display: flex; align-items: flex-start; gap: 14px;
            margin-bottom: 16px;
        }
        .c-row:last-child { margin-bottom: 0; }
        .c-icon {
            width: 42px; height: 42px;
            border-radius: 12px; background: var(--pink-light);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .c-icon .bi { color: var(--pink); font-size: 1rem; }
        .c-meta-label {
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px;
            color: var(--text-muted); margin-bottom: 2px;
        }
        .c-meta-val {
            font-size: .875rem; color: var(--text-dark);
            font-weight: 500; text-decoration: none; display: block;
            word-break: break-word;
        }
        .c-meta-val:hover { color: var(--pink); }

        /* security tips */
        .tip-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 10px 13px; border-radius: var(--radius-sm);
            background: var(--pink-light); margin-bottom: 8px;
        }
        .tip-item:last-child { margin-bottom: 0; }
        .tip-item .bi { color: var(--pink); font-size: .9rem; flex-shrink: 0; margin-top: 1px; }
        .tip-item span { font-size: .8rem; font-weight: 500; color: #444; line-height: 1.45; }

        /* ─── FORM CARD ─────────────────────────────── */
        .f-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            padding: 40px 44px;
            box-shadow: var(--shadow-card);
        }
        .f-card-heading {
            font-size: 1.2rem; font-weight: 700;
            color: var(--text-dark); margin-bottom: 4px;
        }
        .f-card-sub {
            font-size: .85rem; color: var(--text-muted); margin-bottom: 28px;
        }

        /* ── inline alert ── */
        .inline-alert {
            display: flex; align-items: flex-start; gap: 10px;
            border-radius: var(--radius-sm);
            padding: 13px 16px; margin-bottom: 26px;
            font-size: .83rem; font-weight: 500;
        }
        .inline-alert.success {
            background: var(--green-light);
            border: 1.5px solid #6ee7b7;
            color: #065f46;
        }
        .inline-alert.error {
            background: var(--red-light);
            border: 1.5px solid #fca5a5;
            color: #991b1b;
        }
        .inline-alert .bi { font-size: .95rem; flex-shrink: 0; margin-top: 1px; }

        /* ── form groups ── */
        .f-group { margin-bottom: 24px; }

        .f-label {
            display: block; font-size: .78rem; font-weight: 600;
            color: var(--text-mid); margin-bottom: 7px;
        }

        .f-input-wrap {
            position: relative;
        }
        .f-input-wrap .f-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--pink); font-size: .95rem;
            pointer-events: none; z-index: 2;
        }
        .f-input-wrap input {
            width: 100%;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 13px 44px 13px 42px;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem; color: var(--text-dark);
            background: var(--surface); outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
            -webkit-appearance: none; appearance: none;
        }
        .f-input-wrap input:focus {
            border-color: var(--pink); background: #fff;
            box-shadow: 0 0 0 4px rgba(233,30,99,.07);
        }
        .f-input-wrap input.is-error  { border-color: var(--red); }
        .f-input-wrap input.is-ok     { border-color: var(--green); }
        .f-input-wrap input::placeholder { color: #c5b5bc; }

        /* eye toggle */
        .eye-btn {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: .95rem; padding: 3px;
            transition: color .2s; z-index: 2;
        }
        .eye-btn:hover { color: var(--pink); }

        /* field hint */
        .f-hint {
            font-size: .73rem; margin-top: 5px;
            display: flex; align-items: center; gap: 5px;
        }
        .f-hint.match-ok  { color: var(--green); }
        .f-hint.match-err { color: var(--red);   }
        .f-hint.neutral   { color: var(--text-muted); }

        /* ── password strength ── */
        .strength-wrap { margin-top: 8px; }
        .strength-bars {
            display: flex; gap: 5px; margin-bottom: 4px;
        }
        .s-bar {
            flex: 1; height: 4px; border-radius: 4px;
            background: var(--border); transition: background .3s;
        }
        .strength-label {
            font-size: .72rem; font-weight: 600;
            color: var(--text-muted); transition: color .3s;
        }

        /* ── divider ── */
        .s-divider {
            border: none; border-top: 2px dashed #f0e0e8; margin: 24px 0;
        }

        /* ── submit button ── */
        .btn-save {
            display: flex; align-items: center; justify-content: center;
            gap: 9px; width: 100%;
            background: var(--gradient); color: #fff;
            border: none; border-radius: var(--radius-md);
            padding: 15px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: .95rem; font-weight: 600; cursor: pointer;
            box-shadow: 0 8px 24px rgba(233,30,99,.28);
            transition: transform .2s, box-shadow .2s;
            letter-spacing: .3px;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(233,30,99,.38); }
        .btn-save:active { transform: translateY(0); }

        /* ─── BACK TO TOP ───────────────────────────── */
        #movetop {
            display: none; position: fixed;
            bottom: 28px; right: 28px;
            width: 46px; height: 46px; border-radius: 50%;
            background: var(--gradient); color: #fff;
            border: none; cursor: pointer;
            box-shadow: 0 6px 20px rgba(233,30,99,.35);
            font-size: 1.05rem; transition: transform .2s; z-index: 999;
        }
        #movetop:hover { transform: translateY(-3px); }

        /* ─── RESPONSIVE ────────────────────────────── */
        @media (max-width: 767px) {
            .apt-hero { padding: 56px 0 38px; }
            .f-card   { padding: 28px 20px; }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- ══ HERO ════════════════════════════════════════════════ -->
<section class="apt-hero">
    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>

    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="hero-icon-ring">
                <i class="bi bi-shield-lock fs-3 text-white"></i>
            </div>
            <div>
                <h1>Change Password</h1>
                <p class="hero-sub">Keep your account secure with a strong password.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Change Password</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ══ MAIN ════════════════════════════════════════════════ -->
<section class="main-section">
    <div class="container">
        <div class="row g-4 align-items-start">

            <!-- ── LEFT SIDEBAR ──────────────────────── -->
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

                <!-- Security Tips -->
                <div class="s-card">
                    <div class="s-card-label">
                        <i class="bi bi-shield-check"></i> Password Tips
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Use at least 8 characters including uppercase, lowercase, numbers, and symbols.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Avoid using your name, birthday, or common words.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Don't reuse passwords from other accounts.</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Never share your password with anyone, including our staff.</span>
                    </div>
                </div>

            </div>

            <!-- ── FORM CARD ──────────────────────────── -->
            <div class="col-lg-8">
                <div class="f-card">

                    <h5 class="f-card-heading">Update Your Password</h5>
                    <p class="f-card-sub">Fill in your current password, then choose a new secure one.</p>

                    <!-- Inline alert (PHP result) -->
                    <?php if ($alertMsg): ?>
                    <div class="inline-alert <?php echo $alertType; ?>">
                        <i class="bi <?php echo $alertType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'; ?>"></i>
                        <?php echo htmlspecialchars($alertMsg); ?>
                    </div>
                    <?php endif; ?>

                    <form method="post" name="changepassword" id="cpForm" onsubmit="return validateForm();">

                        <!-- Current Password -->
                        <div class="f-group">
                            <label class="f-label" for="currentpassword">Current Password</label>
                            <div class="f-input-wrap">
                                <i class="bi bi-lock f-icon"></i>
                                <input type="password" id="currentpassword" name="currentpassword"
                                       placeholder="Enter your current password" required>
                                <button type="button" class="eye-btn" onclick="toggleEye('currentpassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <hr class="s-divider">

                        <!-- New Password -->
                        <div class="f-group">
                            <label class="f-label" for="newpassword">New Password</label>
                            <div class="f-input-wrap">
                                <i class="bi bi-key f-icon"></i>
                                <input type="password" id="newpassword" name="newpassword"
                                       placeholder="Enter a strong new password"
                                       oninput="checkStrength(this.value); liveMatch();" required>
                                <button type="button" class="eye-btn" onclick="toggleEye('newpassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <!-- Strength meter -->
                            <div class="strength-wrap" id="strengthWrap" style="display:none;">
                                <div class="strength-bars">
                                    <div class="s-bar" id="bar1"></div>
                                    <div class="s-bar" id="bar2"></div>
                                    <div class="s-bar" id="bar3"></div>
                                    <div class="s-bar" id="bar4"></div>
                                </div>
                                <div class="strength-label" id="strengthLabel">Weak</div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="f-group">
                            <label class="f-label" for="confirmpassword">Confirm New Password</label>
                            <div class="f-input-wrap">
                                <i class="bi bi-shield-lock f-icon"></i>
                                <input type="password" id="confirmpassword" name="confirmpassword"
                                       placeholder="Re-enter your new password"
                                       oninput="liveMatch();" required>
                                <button type="button" class="eye-btn" onclick="toggleEye('confirmpassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="f-hint neutral" id="matchHint" style="display:none;">
                                <i class="bi bi-info-circle"></i>
                                <span id="matchText"></span>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-save" name="change">
                            <i class="bi bi-shield-check"></i>
                            Save New Password
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<button onclick="topFunction()" id="movetop" title="Go to top">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    /* ── back to top ── */
    window.onscroll = function () {
        document.getElementById('movetop').style.display =
            (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
                ? 'block' : 'none';
    };
    function topFunction() {
        document.body.scrollTop = 0; document.documentElement.scrollTop = 0;
    }

    /* ── eye toggle ── */
    function toggleEye(fieldId, btn) {
        var inp  = document.getElementById(fieldId);
        var icon = btn.querySelector('i');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    /* ── password strength ── */
    function checkStrength(val) {
        var wrap  = document.getElementById('strengthWrap');
        var label = document.getElementById('strengthLabel');
        var bars  = [
            document.getElementById('bar1'),
            document.getElementById('bar2'),
            document.getElementById('bar3'),
            document.getElementById('bar4')
        ];

        if (val.length === 0) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';

        var score = 0;
        if (val.length >= 6)                    score++;
        if (val.length >= 10)                   score++;
        if (/[0-9]/.test(val) && /[a-zA-Z]/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val))          score++;

        var colors  = ['#ef4444','#f59e0b','#10b981','#10b981'];
        var labels  = ['Weak','Fair','Good','Strong'];
        var barFill = ['#ef4444','#f59e0b','#10b981','#10b981'];

        bars.forEach(function(b, i) {
            b.style.background = (i < score) ? barFill[score - 1] : 'var(--border)';
        });
        label.textContent = labels[score - 1] || 'Weak';
        label.style.color = colors[score - 1] || '#ef4444';
    }

    /* ── live match validation ── */
    function liveMatch() {
        var np   = document.getElementById('newpassword').value;
        var cp   = document.getElementById('confirmpassword').value;
        var cf   = document.getElementById('confirmpassword');
        var hint = document.getElementById('matchHint');
        var txt  = document.getElementById('matchText');

        if (cp.length === 0) {
            hint.style.display = 'none';
            cf.classList.remove('is-ok', 'is-error');
            return;
        }

        hint.style.display = 'flex';

        if (np === cp) {
            hint.className = 'f-hint match-ok';
            txt.textContent = 'Passwords match.';
            cf.classList.add('is-ok'); cf.classList.remove('is-error');
        } else {
            hint.className = 'f-hint match-err';
            txt.textContent = 'Passwords do not match yet.';
            cf.classList.add('is-error'); cf.classList.remove('is-ok');
        }
    }

    /* ── form submit guard ── */
    function validateForm() {
        var np = document.getElementById('newpassword').value;
        var cp = document.getElementById('confirmpassword').value;

        if (np !== cp) {
            var hint = document.getElementById('matchHint');
            var txt  = document.getElementById('matchText');
            hint.style.display = 'flex';
            hint.className = 'f-hint match-err';
            txt.textContent = 'Passwords do not match — please fix before saving.';
            document.getElementById('confirmpassword').focus();
            return false;
        }
        return true;
    }
</script>

</body>
</html>
<?php } ?>
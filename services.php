<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

/* ── fetch services ── */
$ret = mysqli_query($con, "SELECT * FROM tblservices ORDER BY ID ASC");

$services      = [];
while ($row = mysqli_fetch_assoc($ret)) {
    $services[] = $row;
}
$totalServices = count($services);

/* ── keyword → Bootstrap Icon helper ── */
function serviceIcon(string $name): string {
    $n = strtolower($name);
    if (str_contains($n, 'hair'))    return 'bi-scissors';
    if (str_contains($n, 'nail'))    return 'bi-gem';
    if (str_contains($n, 'facial') || str_contains($n, 'skin')) return 'bi-stars';
    if (str_contains($n, 'makeup') || str_contains($n, 'make up')) return 'bi-palette';
    if (str_contains($n, 'massage') || str_contains($n, 'spa'))    return 'bi-heart-pulse';
    if (str_contains($n, 'wax'))     return 'bi-droplet';
    if (str_contains($n, 'brow') || str_contains($n, 'lash'))      return 'bi-eye';
    return 'bi-stars';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | GlamourSoft</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────────────── */
        :root {
            --pink:        #e91e63;
            --pink-mid:    #ff4f81;
            --pink-dark:   #c2185b;
            --pink-light:  #fce4ec;
            --gradient:    linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);
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
            --shadow-hover:0 20px 56px rgba(233,30,99,.16);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ────────────────────────────────────────── */
        .services-hero {
            position: relative;
            min-height: 580px;
            display: flex;
            align-items: center;
            background:
                linear-gradient(
                    to bottom,
                    rgba(0,0,0,.52) 0%,
                    rgba(233,30,99,.28) 100%
                ),
                url('https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?q=80&w=1600&auto=format&fit=crop')
                center/cover no-repeat;
            overflow: hidden;
        }

        /* decorative circles */
        .hero-bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(233,30,99,.15);
            pointer-events: none;
        }
        .hb1 { width:420px; height:420px; top:-120px; right:-80px;  }
        .hb2 { width:220px; height:220px; bottom:-60px; left:4%;    }
        .hb3 { width: 80px; height: 80px; top:60px; left:38%;       }

        .hero-inner {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 100px 0 80px;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.25);
            color: #fff;
            border-radius: 30px;
            padding: 7px 18px;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .hero-pill .bi { color: #ffb3c8; }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3.4rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -.5px;
        }
        .hero-title em {
            font-style: normal;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: 1rem;
            font-weight: 300;
            max-width: 580px;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center; gap: 8px;
            background: var(--gradient);
            color: #fff; border: none;
            border-radius: 30px;
            padding: 13px 28px;
            font-family: 'Poppins', sans-serif;
            font-size: .9rem; font-weight: 600;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(233,30,99,.4);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(233,30,99,.5);
            color: #fff;
        }

        .btn-hero-ghost {
            display: inline-flex;
            align-items: center; gap: 8px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(8px);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 30px;
            padding: 13px 28px;
            font-family: 'Poppins', sans-serif;
            font-size: .9rem; font-weight: 600;
            text-decoration: none;
            transition: background .2s, border-color .2s;
        }
        .btn-hero-ghost:hover {
            background: rgba(255,255,255,.22);
            border-color: rgba(255,255,255,.7);
            color: #fff;
        }

        /* hero stat strip */
        .hero-stats {
            display: flex;
            gap: 0;
            margin-top: 48px;
            background: rgba(255,255,255,.10);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            width: fit-content;
        }
        .hero-stat {
            padding: 16px 28px;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,.15);
        }
        .hero-stat:last-child { border-right: none; }
        .hs-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            margin-bottom: 2px;
        }
        .hs-lbl {
            font-size: .72rem;
            color: rgba(255,255,255,.7);
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 500;
        }

        /* ─── SECTION HEADER ──────────────────────────────── */
        .section-wrap { padding: 72px 0 80px; }

        .sec-eyebrow {
            display: inline-flex;
            align-items: center; gap: 8px;
            background: var(--pink-light);
            color: var(--pink);
            border-radius: 30px;
            padding: 7px 18px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .sec-title {
            font-size: clamp(1.7rem, 3vw, 2.5rem);
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -.3px;
        }
        .sec-sub {
            font-size: .95rem;
            color: var(--text-muted);
            max-width: 620px;
            margin: 0 auto;
            line-height: 1.7;
        }



        /* ─── SERVICE CARD ────────────────────────────────── */
        .service-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease, box-shadow .3s ease;
            position: relative;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        /* image wrapper */
        .card-img-wrap {
            position: relative;
            overflow: hidden;
            height: 248px;
            flex-shrink: 0;
        }
        .card-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }
        .service-card:hover .card-img-wrap img {
            transform: scale(1.07);
        }

        /* gradient overlay on image */
        .card-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(30,10,20,.75) 0%,
                rgba(30,10,20,.08) 55%,
                transparent 100%
            );
        }



        /* price badge on image bottom */
        .card-price-badge {
            position: absolute;
            bottom: 14px; left: 14px;
            background: var(--gradient);
            color: #fff;
            border-radius: 10px;
            padding: 5px 14px;
            font-size: .9rem;
            font-weight: 700;
            z-index: 2;
            box-shadow: 0 4px 14px rgba(233,30,99,.4);
        }

        /* hover "book" overlay on image */
        .card-hover-overlay {
            position: absolute;
            inset: 0;
            background: rgba(233,30,99,.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s ease;
            z-index: 3;
        }
        .service-card:hover .card-hover-overlay { opacity: 1; }
        .hover-cta {
            display: inline-flex;
            align-items: center; gap: 8px;
            background: #fff;
            color: var(--pink);
            border-radius: 30px;
            padding: 11px 26px;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            transition: transform .2s;
        }
        .hover-cta:hover { transform: scale(1.05); color: var(--pink-dark); }

        /* card body */
        .card-body-custom {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-icon-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .card-icon {
            width: 40px; height: 40px;
            border-radius: 11px;
            background: var(--pink-light);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .card-icon .bi { color: var(--pink); font-size: 1rem; }

        .card-service-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.3;
        }

        .card-desc {
            font-size: .83rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin: 12px 0 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .card-footer-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }
        .card-price-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--pink);
        }
        .card-price-text small {
            font-size: .7rem;
            font-weight: 400;
            color: var(--text-muted);
            display: block;
            line-height: 1;
        }

        .btn-book-card {
            display: inline-flex;
            align-items: center; gap: 6px;
            background: var(--pink-light);
            color: var(--pink);
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-family: 'Poppins', sans-serif;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, color .2s, transform .2s;
        }
        .btn-book-card:hover {
            background: var(--pink);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ─── EMPTY STATE ──────────────────────────────────── */
        .empty-state {
            background: var(--card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            text-align: center;
            padding: 72px 32px;
        }
        .empty-icon-ring {
            width: 90px; height: 90px;
            background: var(--pink-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 22px;
        }
        .empty-icon-ring .bi { font-size: 2.2rem; color: var(--pink); }
        .empty-state h5 { font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; }
        .empty-state p  { font-size: .88rem; color: var(--text-muted); }

        /* ─── BOTTOM CTA BANNER ────────────────────────────── */
        .cta-banner {
            background: var(--gradient);
            border-radius: var(--radius-xl);
            padding: 52px 44px;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }
        .cta-banner .cb1 {
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,.07);
            top: -80px; right: -60px;
            pointer-events: none;
        }
        .cta-banner .cb2 {
            position: absolute;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            bottom: -50px; left: 8%;
            pointer-events: none;
        }
        .cta-banner h3 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
        }
        .cta-banner p {
            color: rgba(255,255,255,.82);
            font-size: .95rem;
            margin-bottom: 0;
        }
        .btn-cta-white {
            display: inline-flex;
            align-items: center; gap: 8px;
            background: #fff;
            color: var(--pink);
            border: none;
            border-radius: 30px;
            padding: 14px 32px;
            font-family: 'Poppins', sans-serif;
            font-size: .92rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(0,0,0,.15);
            transition: transform .2s, box-shadow .2s;
            white-space: nowrap;
        }
        .btn-cta-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(0,0,0,.2);
            color: var(--pink-dark);
        }

        /* ─── BACK TO TOP ──────────────────────────────────── */
        #movetop {
            display: none; position: fixed;
            bottom: 28px; right: 28px;
            width: 46px; height: 46px;
            border-radius: 50%;
            background: var(--gradient); color: #fff;
            border: none; cursor: pointer;
            box-shadow: 0 6px 20px rgba(233,30,99,.35);
            font-size: 1.05rem; transition: transform .2s; z-index: 999;
        }
        #movetop:hover { transform: translateY(-3px); }

        /* ─── RESPONSIVE ────────────────────────────────────── */
        @media (max-width: 767px) {
            .services-hero { min-height: 480px; }
            .hero-stats    { width: 100%; }
            .hero-stat     { flex: 1; padding: 14px 12px; }
            .cta-banner    { padding: 36px 24px; }
        }
        @media (max-width: 575px) {
            .hero-stat.hide-xs { display: none; }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- ══ HERO ══════════════════════════════════════════════════ -->
<section class="services-hero" id="top">
    <div class="hero-bubble hb1"></div>
    <div class="hero-bubble hb2"></div>
    <div class="hero-bubble hb3"></div>

    <div class="hero-inner">
        <div class="container">

            <div class="hero-pill">
                <i class="bi bi-scissors"></i>
                GlamourSoft Beauty
            </div>

            <h1 class="hero-title">
                Discover Your<br>
                <em>Perfect Look</em>
            </h1>

            <p class="hero-sub">
                Premium salon and beauty treatments crafted for your style,
                comfort, and confidence — by expert professionals who care.
            </p>

            <div class="hero-actions">
                <a href="#services-grid" class="btn-hero-primary">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    Browse Services
                </a>
                <a href="book-appointment.php" class="btn-hero-ghost">
                    <i class="bi bi-calendar-check"></i>
                    Book Appointment
                </a>
            </div>

            <!-- stats -->
            <div class="hero-stats mt-4">
                <div class="hero-stat">
                    <div class="hs-num"><?php echo $totalServices; ?>+</div>
                    <div class="hs-lbl">Services</div>
                </div>
                <div class="hero-stat">
                    <div class="hs-num">500+</div>
                    <div class="hs-lbl">Happy Clients</div>
                </div>
                <div class="hero-stat hide-xs">
                    <div class="hs-num">5★</div>
                    <div class="hs-lbl">Rating</div>
                </div>
                <div class="hero-stat hide-xs">
                    <div class="hs-num">10+</div>
                    <div class="hs-lbl">Years Exp.</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ══ SERVICES SECTION ═══════════════════════════════════════ -->
<section class="section-wrap" id="services-grid">
    <div class="container">

        <!-- Section header -->
        <div class="text-center mb-5">
            <div class="sec-eyebrow">
                <i class="bi bi-stars"></i>
                Professional Services
            </div>
            <h2 class="sec-title">Beauty &amp; Wellness Menu</h2>
            <p class="sec-sub">
                Handcrafted treatments for every need — from quick touch-ups
                to full transformations. Your confidence is our craft.
            </p>
        </div>



        <!-- Cards grid -->
        <?php if (!empty($services)): ?>
        <div class="row g-4" id="servicesGrid">

            <?php foreach ($services as $svc):
                $icon   = serviceIcon($svc['ServiceName']);
                $imgSrc = !empty($svc['Image'])
                    ? 'admin/images/' . htmlspecialchars($svc['Image'])
                    : 'https://images.unsplash.com/photo-1562322140-8baeececf3df?w=600&auto=format&fit=crop';
                $desc   = htmlspecialchars($svc['ServiceDescription'] ?? '');
            ?>
            <div class="col-xl-4 col-md-6">

                <div class="service-card">

                    <!-- Image -->
                    <div class="card-img-wrap">
                        <img src="<?php echo $imgSrc; ?>"
                             alt="<?php echo htmlspecialchars($svc['ServiceName']); ?>"
                             loading="lazy">

                        <div class="card-img-overlay"></div>

                        <!-- Price badge -->
                        <div class="card-price-badge">
                            Rs.&nbsp;<?php echo number_format((float)$svc['Cost'], 0); ?>
                        </div>

                        <!-- Hover book overlay -->
                        <div class="card-hover-overlay">
                            <a href="book-appointment.php" class="hover-cta">
                                <i class="bi bi-calendar-check"></i>
                                Book This Service
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body-custom">

                        <div class="card-icon-row">
                            <div class="card-icon">
                                <i class="bi <?php echo $icon; ?>"></i>
                            </div>
                            <h5 class="card-service-title">
                                <?php echo htmlspecialchars($svc['ServiceName']); ?>
                            </h5>
                        </div>

                        <p class="card-desc"><?php echo $desc; ?></p>

                        <div class="card-footer-custom">
                            <div class="card-price-text">
                                <small>Starting from</small>
                                Rs.&nbsp;<?php echo number_format((float)$svc['Cost'], 0); ?>
                            </div>
                            <a href="book-appointment.php" class="btn-book-card">
                                Book Now <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
            <?php endforeach; ?>

        </div><!-- /#servicesGrid -->

        <?php else: ?>

        <!-- Empty state -->
        <div class="empty-state">
            <div class="empty-icon-ring">
                <i class="bi bi-scissors"></i>
            </div>
            <h5>No Services Yet</h5>
            <p>Our team is setting things up. Check back soon for our full beauty menu.</p>
        </div>

        <?php endif; ?>

        <!-- Bottom CTA banner -->
        <div class="cta-banner mt-5">
            <div class="cb1"></div>
            <div class="cb2"></div>
            <div class="row align-items-center g-4" style="position:relative;z-index:2;">
                <div class="col-lg-8">
                    <h3>Ready for Your Glow-Up?</h3>
                    <p>Book a session today and let our experts take care of the rest. Walk in, walk out confident.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="book-appointment.php" class="btn-cta-white">
                        <i class="bi bi-calendar-heart"></i>
                        Book Appointment
                    </a>
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

    /* ── smooth scroll for Browse Services button ── */
    document.querySelector('a[href="#services-grid"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('services-grid').scrollIntoView({ behavior: 'smooth' });
    });


</script>

</body>
</html>
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
      $email=$_POST['email'];
      $mobnumber=$_POST['mobnumber'];
      $timing=$_POST['timing'];
     
    $query=mysqli_query($con,"update tblpage set PageTitle='$pagetitle',Email='$email',MobileNumber='$mobnumber',Timing='$timing',PageDescription='$pagedes' where  PageType='contactus'");
    if ($query) {
    $msg="Contact Us has been updated successfully.";
  }
  else
    {
      $msg="Something Went Wrong. Please try again";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>BPMS | Contact Us</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>
  /* ── Matches your project variables exactly ─────────────── */
  :root {
    --primary-color:   #e91e63;
    --secondary-color: #ff4f81;
    --dark-color:      #1f1f1f;
    --primary-light:   #fce4ec;
    --primary-muted:   #fdf0f5;
    --primary-dark:    #c2185b;
    --border-soft:     #f3e0ea;
    --text-muted-soft: #9e9e9e;
    --page-bg:         #fdf6fb;
    --surface:         #ffffff;
    --radius-card:     16px;
    --radius-input:    10px;
    --shadow-card:     0 2px 20px rgba(233,30,99,0.07);
  }

  body {
    font-family: 'Poppins', sans-serif;
    background: var(--page-bg);
  }

  /* ── Dashboard wrapper: lines up with your sidebar (280px) + navbar (78px) ── */
  .dashboard-wrapper {
    margin-left: 280px;
    padding-top: 78px;
    transition: margin-left 0.3s;
    min-height: 100vh;
  }
  .dashboard-wrapper.full-width { margin-left: 0; }
  @media (max-width: 991px) { .dashboard-wrapper { margin-left: 0; } }

  /* ── Page content padding ───────────────────────────────── */
  .page-content { padding: 2rem 2rem 3rem; max-width: 980px; }
  @media (max-width: 768px) { .page-content { padding: 1.25rem 1rem 2rem; } }

  /* ── Breadcrumb ─────────────────────────────────────────── */
  .bpms-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--text-muted-soft);
    margin-bottom: 1.5rem;
  }
  .bpms-breadcrumb a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
  }
  .bpms-breadcrumb a:hover { text-decoration: underline; }
  .bpms-breadcrumb .current { color: var(--primary-color); font-weight: 600; }

  /* ── Page header ────────────────────────────────────────── */
  .page-header { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.75rem; }
  .page-header-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 5px 15px rgba(233,30,99,.3);
  }
  .page-header-icon i { font-size: 1.4rem; color: #fff; }
  .page-header-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 700;
    color: var(--dark-color); margin: 0 0 3px;
  }
  .page-header-text p { font-size: 13px; color: var(--text-muted-soft); margin: 0; }

  /* ── Alert ──────────────────────────────────────────────── */
  .bpms-alert {
    border-radius: 12px; padding: .8rem 1.1rem;
    font-size: 13.5px; font-weight: 500;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 1.5rem; border: none;
    animation: slideDown .3s ease;
  }
  .bpms-alert.success { background: #d1fae5; color: #065f46; }
  .bpms-alert.danger  { background: #fee2e2; color: #991b1b; }
  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── Card ───────────────────────────────────────────────── */
  .bpms-card {
    background: var(--surface);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-card);
    border: 1px solid var(--border-soft);
    overflow: hidden;
    margin-bottom: 1.4rem;
  }
  .bpms-card:last-child { margin-bottom: 0; }

  .bpms-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border-soft);
    display: flex; align-items: center; gap: 10px;
  }
  .card-header-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .card-header-icon i { font-size: .95rem; color: var(--primary-color); }
  .bpms-card-header h5 { font-size: 14px; font-weight: 600; color: var(--dark-color); margin: 0; flex: 1; }
  .card-badge {
    font-size: 11px; font-weight: 500;
    color: var(--primary-color); background: var(--primary-light);
    padding: 3px 10px; border-radius: 20px;
  }
  .bpms-card-body { padding: 20px; }

  /* ── Info banner ────────────────────────────────────────── */
  .info-banner {
    background: var(--primary-muted);
    border-left: 3px solid var(--primary-color);
    border-radius: 0 10px 10px 0;
    padding: 10px 14px;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 1.4rem;
  }
  .info-banner i { color: var(--primary-color); font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
  .info-banner p { font-size: 12.5px; color: #7b1e4a; margin: 0; line-height: 1.55; }

  /* ── Section divider ────────────────────────────────────── */
  .field-section-title {
    font-size: 10.5px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-muted-soft);
    margin: 1.3rem 0 1rem;
    display: flex; align-items: center; gap: 8px;
  }
  .field-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border-soft); }

  /* ── Form labels ────────────────────────────────────────── */
  .bpms-label { font-size: 12.5px; font-weight: 600; color: #333; margin-bottom: 6px; display: block; }
  .bpms-label .req { color: var(--primary-color); margin-left: 2px; }
  .field-hint { font-size: 11.5px; color: var(--text-muted-soft); margin-top: 4px; }
  .field-error { font-size: 11.5px; color: #dc3545; margin-top: 4px; display: none; }

  /* ── Inputs ─────────────────────────────────────────────── */
  .input-wrapper { position: relative; }
  .input-wrapper .input-icon {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--primary-color); font-size: .95rem; pointer-events: none;
  }
  .bpms-input {
    width: 100%;
    border: 1.5px solid #eedde8;
    border-radius: var(--radius-input);
    padding: 9px 12px 9px 36px;
    font-size: 13.5px; font-family: 'Poppins', sans-serif;
    color: var(--dark-color); background: var(--surface);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
  }
  .bpms-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(233,30,99,.10);
  }
  .bpms-input.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,.10);
  }
  .bpms-input::placeholder { color: #cdb5c7; }

  textarea.bpms-input {
    padding-left: 12px; resize: vertical; min-height: 110px; line-height: 1.6;
  }
  .char-counter { font-size: 11px; color: var(--text-muted-soft); text-align: right; margin-top: 3px; }

  /* ── Live preview panel ─────────────────────────────────── */
  .preview-panel {
    background: linear-gradient(145deg, var(--primary-muted) 0%, #fff5f9 100%);
    border-radius: 14px; border: 1px dashed #f0a8c8; padding: 18px;
  }
  .preview-label {
    font-size: 10px; font-weight: 700; letter-spacing: .09em;
    text-transform: uppercase; color: var(--primary-color);
    margin-bottom: 14px; display: flex; align-items: center; gap: 6px;
  }
  .preview-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700;
    color: #6b1050; margin-bottom: 8px; line-height: 1.3;
  }
  .preview-page-desc {
    font-size: 12px; color: #8a3060; line-height: 1.65;
    margin-bottom: 14px; padding-bottom: 14px;
    border-bottom: 1px dashed rgba(233,30,99,.2);
  }
  .preview-info-row {
    display: flex; align-items: flex-start; gap: 9px;
    font-size: 12.5px; color: #7b2155; margin-bottom: 7px;
  }
  .preview-info-row i { font-size: .9rem; color: var(--primary-color); flex-shrink: 0; margin-top: 1px; }

  /* ── Buttons ────────────────────────────────────────────── */
  .btn-bpms-save {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff; border: none; border-radius: 10px;
    padding: 10px 22px; font-size: 13.5px; font-weight: 600;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 5px 15px rgba(233,30,99,.3);
    transition: opacity .2s, transform .15s, box-shadow .2s;
  }
  .btn-bpms-save:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(233,30,99,.38); }
  .btn-bpms-save:active { transform: translateY(0); }
  .btn-bpms-save:disabled { opacity: .65; cursor: not-allowed; transform: none; }

  .btn-bpms-outline {
    background: transparent; color: var(--primary-color);
    border: 1.5px solid var(--primary-color); border-radius: 10px;
    padding: 9px 18px; font-size: 13.5px; font-weight: 600;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    display: inline-flex; align-items: center; gap: 7px;
    text-decoration: none; transition: background .2s;
  }
  .btn-bpms-outline:hover { background: var(--primary-light); color: var(--primary-color); }

  /* Spinner in button */
  .btn-spinner {
    display: none; width: 15px; height: 15px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff; border-radius: 50%;
    animation: spin .6s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .save-note { font-size: 11.5px; color: var(--text-muted-soft); margin-top: .9rem; display: flex; align-items: center; gap: 6px; }
  .save-note i { color: #10b981; font-size: .85rem; }

  /* Sticky preview on desktop */
  @media (min-width: 992px) { .preview-sticky { position: sticky; top: 96px; } }
</style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<!-- Main wrapper — aligns with sidebar (280px) + navbar (78px) -->
<div class="dashboard-wrapper" id="dashboard-wrapper">
  <div class="page-content">

    <!-- Breadcrumb -->
    <nav class="bpms-breadcrumb">
      <i class="bi bi-house-door" style="font-size:.8rem;"></i>
      <a href="dashboard.php">Dashboard</a>
      <i class="bi bi-chevron-right" style="font-size:.7rem;opacity:.4;"></i>
      <span>Settings</span>
      <i class="bi bi-chevron-right" style="font-size:.7rem;opacity:.4;"></i>
      <span class="current">Contact Us</span>
    </nav>

    <!-- Page header -->
    <div class="page-header">
      <div class="page-header-icon">
        <i class="bi bi-telephone-fill"></i>
      </div>
      <div class="page-header-text">
        <h1>Contact Us Settings</h1>
        <p>Manage the information shown on your public Contact page.</p>
      </div>
    </div>

    <!-- ── Alert (only shown after submit) ────────────────── -->
    <?php if(isset($msg) && $msg != '') { ?>
    <div class="bpms-alert <?php echo (strpos($msg, 'successfully') !== false) ? 'success' : 'danger'; ?>">
      <i class="bi bi-<?php echo (strpos($msg, 'successfully') !== false) ? 'check-circle-fill' : 'exclamation-circle-fill'; ?>"></i>
      <?php echo $msg; ?>
    </div>
    <?php } ?>

    <!-- ── Form ──────────────────────────────────────────── -->
    <form method="post" id="contactUsForm" novalidate>
      <div class="row g-4">

        <!-- LEFT: form fields -->
        <div class="col-lg-7">

          <!-- Card 1: Page Identity -->
          <div class="bpms-card">
            <div class="bpms-card-header">
              <div class="card-header-icon"><i class="bi bi-file-earmark-text"></i></div>
              <h5>Page Identity</h5>
              <span class="card-badge">Public facing</span>
            </div>
            <div class="bpms-card-body">

              <div class="info-banner">
                <i class="bi bi-info-circle-fill"></i>
                <p>This title and description appear on your website's public Contact Us page. Keep them short and welcoming.</p>
              </div>

              <?php
                $ret=mysqli_query($con,"select * from tblpage where PageType='contactus'");
                $cnt=1;
                while($row=mysqli_fetch_array($ret)) {
              ?>

              <!-- Page Title -->
              <div class="mb-3">
                <label class="bpms-label" for="pagetitle">Page Title <span class="req">*</span></label>
                <div class="input-wrapper">
                  <i class="bi bi-type-h1 input-icon"></i>
                  <input type="text" class="bpms-input" name="pagetitle" id="pagetitle"
                    placeholder="e.g. Get In Touch With Us"
                    value="<?php echo htmlspecialchars($row['PageTitle']); ?>"
                    maxlength="100" required>
                </div>
                <p class="field-error" id="err-pagetitle">Page title is required.</p>
              </div>

              <!-- Page Description -->
              <div>
                <label class="bpms-label" for="pagedes">Page Description <span class="req">*</span></label>
                <textarea class="bpms-input" name="pagedes" id="pagedes"
                  placeholder="Write a short, welcoming message for your contact page..."
                  maxlength="500" required><?php echo htmlspecialchars($row['PageDescription']); ?></textarea>
                <div class="char-counter"><span id="descCount">0</span> / 500</div>
                <p class="field-error" id="err-pagedes">Page description is required.</p>
              </div>

            </div>
          </div><!-- /Card 1 -->

          <!-- Card 2: Contact Details -->
          <div class="bpms-card">
            <div class="bpms-card-header">
              <div class="card-header-icon"><i class="bi bi-person-lines-fill"></i></div>
              <h5>Contact Details</h5>
              <span class="card-badge">Shown to visitors</span>
            </div>
            <div class="bpms-card-body">

              <div class="field-section-title">Communication</div>

              <!-- Email -->
              <div class="mb-3">
                <label class="bpms-label" for="email">Email Address <span class="req">*</span></label>
                <div class="input-wrapper">
                  <i class="bi bi-envelope-fill input-icon"></i>
                  <input type="email" class="bpms-input" name="email" id="email"
                    placeholder="contact@yoursalon.com"
                    value="<?php echo htmlspecialchars($row['Email']); ?>" required>
                </div>
                <p class="field-error" id="err-email">A valid email address is required.</p>
              </div>

              <!-- Mobile Number -->
              <div class="mb-3">
                <label class="bpms-label" for="mobnumber">Mobile Number <span class="req">*</span></label>
                <div class="input-wrapper">
                  <i class="bi bi-telephone-fill input-icon"></i>
                  <input type="tel" class="bpms-input" name="mobnumber" id="mobnumber"
                    placeholder="+977-98XXXXXXXX"
                    value="<?php echo htmlspecialchars($row['MobileNumber']); ?>" required>
                </div>
                <p class="field-error" id="err-mobnumber">Mobile number is required.</p>
              </div>

              <div class="field-section-title">Availability</div>

              <!-- Timing -->
              <div>
                <label class="bpms-label" for="timing">Business Hours <span class="req">*</span></label>
                <div class="input-wrapper">
                  <i class="bi bi-clock-fill input-icon"></i>
                  <input type="text" class="bpms-input" name="timing" id="timing"
                    placeholder="Mon–Sat: 9:00 AM – 7:00 PM"
                    value="<?php echo htmlspecialchars($row['Timing']); ?>" required>
                </div>
                <p class="field-hint">Let visitors know when they can reach you.</p>
                <p class="field-error" id="err-timing">Business hours are required.</p>
              </div>

              <?php } /* end while */ ?>

            </div>
          </div><!-- /Card 2 -->

        </div><!-- /col-lg-7 -->

        <!-- RIGHT: live preview + save -->
        <div class="col-lg-5">
          <div class="bpms-card preview-sticky">
            <div class="bpms-card-header">
              <div class="card-header-icon"><i class="bi bi-eye-fill"></i></div>
              <h5>Live Preview</h5>
              <span class="card-badge">Updates as you type</span>
            </div>
            <div class="bpms-card-body">

              <div class="preview-panel">
                <div class="preview-label">
                  <i class="bi bi-broadcast"></i> Public page preview
                </div>
                <div class="preview-page-title" id="prevTitle">Page Title</div>
                <div class="preview-page-desc" id="prevDesc">Page description will appear here...</div>
                <div class="preview-info-row">
                  <i class="bi bi-envelope-fill"></i>
                  <span id="prevEmail">email@salon.com</span>
                </div>
                <div class="preview-info-row">
                  <i class="bi bi-telephone-fill"></i>
                  <span id="prevPhone">+977-XXXXXXXXXX</span>
                </div>
                <div class="preview-info-row">
                  <i class="bi bi-clock-fill"></i>
                  <span id="prevTiming">Mon–Sat: 9 AM – 7 PM</span>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="d-flex align-items-center gap-2 flex-wrap mt-4">
                <button type="submit" name="submit" class="btn-bpms-save" id="saveBtn">
                  <span class="btn-spinner" id="saveSpinner"></span>
                  <i class="bi bi-floppy-fill" id="saveIcon"></i>
                  Save Changes
                </button>
                <a href="dashboard.php" class="btn-bpms-outline">
                  <i class="bi bi-arrow-left"></i> Cancel
                </a>
              </div>

              <p class="save-note">
                <i class="bi bi-shield-check"></i>
                Changes reflect immediately on your public site.
              </p>

            </div>
          </div>
        </div><!-- /col-lg-5 -->

      </div><!-- /row -->
    </form>

  </div><!-- /page-content -->
</div><!-- /dashboard-wrapper -->

<?php include_once('includes/footer.php'); ?>

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
/* ── Live preview sync ─────────────────────────────────── */
const bindings = [
  { from: 'pagetitle', to: 'prevTitle',  fb: 'Page Title' },
  { from: 'pagedes',   to: 'prevDesc',   fb: 'Page description will appear here...' },
  { from: 'email',     to: 'prevEmail',  fb: 'email@salon.com' },
  { from: 'mobnumber', to: 'prevPhone',  fb: '+977-XXXXXXXXXX' },
  { from: 'timing',    to: 'prevTiming', fb: 'Mon–Sat: 9 AM – 7 PM' },
];
bindings.forEach(({ from, to, fb }) => {
  const inp  = document.getElementById(from);
  const prev = document.getElementById(to);
  if (!inp || !prev) return;
  const sync = () => { prev.textContent = inp.value.trim() || fb; };
  inp.addEventListener('input', sync);
  sync();
});

/* ── Character counter ─────────────────────────────────── */
const ta      = document.getElementById('pagedes');
const counter = document.getElementById('descCount');
if (ta && counter) {
  const upd = () => {
    counter.textContent    = ta.value.length;
    counter.style.color    = ta.value.length > 450 ? '#dc3545' : '';
  };
  ta.addEventListener('input', upd);
  upd();
}

/* ── Client-side validation ────────────────────────────── */
const rules = [
  { id: 'pagetitle', err: 'err-pagetitle', test: v => v.length > 0 },
  { id: 'pagedes',   err: 'err-pagedes',   test: v => v.length > 0 },
  { id: 'email',     err: 'err-email',     test: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
  { id: 'mobnumber', err: 'err-mobnumber', test: v => v.length > 0 },
  { id: 'timing',    err: 'err-timing',    test: v => v.length > 0 },
];

function validate(rule) {
  const inp = document.getElementById(rule.id);
  const err = document.getElementById(rule.err);
  if (!inp || !err) return true;
  const ok = rule.test(inp.value.trim());
  inp.classList.toggle('is-invalid', !ok);
  err.style.display = ok ? 'none' : 'block';
  return ok;
}

rules.forEach(r => {
  const inp = document.getElementById(r.id);
  if (!inp) return;
  inp.addEventListener('blur',  () => validate(r));
  inp.addEventListener('input', () => { if (inp.classList.contains('is-invalid')) validate(r); });
});

document.getElementById('contactUsForm').addEventListener('submit', function(e) {
  let ok = true;
  rules.forEach(r => { if (!validate(r)) ok = false; });
  if (!ok) {
    e.preventDefault();
    const bad = this.querySelector('.is-invalid');
    if (bad) { bad.scrollIntoView({ behavior:'smooth', block:'center' }); bad.focus(); }
    return;
  }
  /* show spinner */
  const btn     = document.getElementById('saveBtn');
  const spinner = document.getElementById('saveSpinner');
  const icon    = document.getElementById('saveIcon');
  if (btn) { spinner.style.display='block'; icon.style.display='none'; btn.disabled=true; }
});
</script>

</body>
</html>
<?php } ?>
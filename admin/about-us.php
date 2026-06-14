<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $bpmsaid   = $_SESSION['bpmsaid'];
        $pagetitle = $_POST['pagetitle'];
        $pagedes   = $_POST['pagedes'];

        $query = mysqli_query($con,
            "update tblpage set PageTitle='$pagetitle', PageDescription='$pagedes'
             where PageType='aboutus'"
        );

        if ($query) {
            $msg     = "About Us page has been updated successfully.";
            $msgType = "success";
        } else {
            $msg     = "Something went wrong. Please try again.";
            $msgType = "error";
        }
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BPMS | About Us</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- NicEdit rich text editor -->
<script src="http://js.nicedit.com/nicEdit-latest.js"></script>
<script>
    bkLib.onDomLoaded(function () {
        new nicEditor({ fullPanel: true }).panelInstance('pagedes');
    });
</script>

<style>
:root {
    --primary-color:   #e91e63;
    --secondary-color: #ff4f81;
    --bg-page:         #f8f9fc;
    --text-dark:       #1f2937;
    --text-muted:      #6b7280;
    --border:          #e5e7eb;
    --sidebar-width:   280px;
    --navbar-height:   78px;
}

* { box-sizing: border-box; }

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-page);
    margin: 0;
}

/* ── Layout ─────────────────────────────────────────── */
.page-wrapper {
    margin-left: var(--sidebar-width);
    padding: calc(var(--navbar-height) + 32px) 32px 60px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}
.page-wrapper.full-width { margin-left: 0; }

@media (max-width: 991px) {
    .page-wrapper {
        margin-left: 0;
        padding: calc(var(--navbar-height) + 20px) 16px 40px;
    }
}

/* ── Breadcrumb ─────────────────────────────────────── */
.breadcrumb-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    font-size: 13px;
    color: var(--text-muted);
}
.breadcrumb-bar a {
    color: var(--text-muted);
    text-decoration: none;
    transition: color .2s;
}
.breadcrumb-bar a:hover { color: var(--primary-color); }
.breadcrumb-bar .bi-chevron-right { font-size: 11px; opacity: .5; }
.breadcrumb-bar .current { color: var(--primary-color); font-weight: 500; }

/* ── Page heading ───────────────────────────────────── */
.page-heading { margin-bottom: 26px; }
.page-heading h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 5px;
}
.page-heading p { color: var(--text-muted); font-size: 14px; margin: 0; }

/* ── Two-column grid ────────────────────────────────── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 22px;
    align-items: start;
}
@media (max-width: 960px) {
    .content-grid { grid-template-columns: 1fr; }
}

/* ── Main form card ─────────────────────────────────── */
.form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
}

.form-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 22px 26px;
    border-bottom: 1px solid var(--border);
}
.head-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
    flex-shrink: 0;
}
.head-title { font-size: 16px; font-weight: 600; color: var(--text-dark); margin: 0; }
.head-sub   { font-size: 12px; color: var(--text-muted); margin: 2px 0 0; }

.form-card-body { padding: 28px; }

/* ── Alert ──────────────────────────────────────────── */
.alert-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 22px;
}
.alert-box.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.alert-box.error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* ── Field groups ───────────────────────────────────── */
.field-group { margin-bottom: 22px; }

.field-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}
.field-label .bi { color: var(--primary-color); font-size: 14px; }

.field-hint {
    font-size: 11px;
    color: var(--text-muted);
    margin-left: auto;
    font-weight: 400;
}

.field-input {
    width: 100%;
    height: 50px;
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: var(--text-dark);
    background: #fff;
    transition: .25s ease;
}
.field-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
}

/* Character counter */
.char-counter {
    text-align: right;
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 5px;
}
.char-counter.over { color: #dc2626; }

/* ── NicEdit container ──────────────────────────────── */
.editor-wrap {
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    overflow: hidden;
    transition: border-color .25s;
}
.editor-wrap:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
}

/* nicEdit injects its toolbar + iframe — let them fill naturally */
.editor-wrap .nicEdit-main,
.editor-wrap .nicEdit-panelContain {
    border: none !important;
    border-radius: 0 !important;
}

/* Fallback textarea (shown before JS loads) */
#pagedes {
    width: 100%;
    border: none;
    padding: 14px 16px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: var(--text-dark);
    resize: vertical;
    min-height: 240px;
    outline: none;
}

/* ── Form actions ───────────────────────────────────── */
.form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 22px;
    border-top: 1px solid var(--border);
    margin-top: 8px;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 50px;
    padding: 0 32px;
    border: none;
    border-radius: 13px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .25s ease;
    box-shadow: 0 4px 14px rgba(233,30,99,.25);
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(233,30,99,.32);
}

.btn-preview {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 50px;
    padding: 0 22px;
    border-radius: 13px;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text-muted);
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}
.btn-preview:hover {
    background: #fce4ec;
    border-color: #f9a8c9;
    color: var(--primary-color);
    text-decoration: none;
}

.save-note {
    margin-left: auto;
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── Side info card ─────────────────────────────────── */
.info-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
}

.info-card-banner {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 26px 22px;
    text-align: center;
}

.info-card-banner .page-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(255,255,255,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 26px;
    color: #fff;
}

.info-card-banner h5 {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px;
}

.info-card-banner p {
    font-size: 12px;
    color: rgba(255,255,255,.8);
    margin: 0;
}

.info-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.info-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.5;
}
.info-row:last-child { border-bottom: none; }

.info-row .info-dot {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #fce7ef;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
    margin-top: 1px;
}

.info-row strong {
    display: block;
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 2px;
}

/* ── Preview modal ──────────────────────────────────── */
.preview-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.preview-modal-overlay.open { display: flex; }

.preview-modal {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 760px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0,0,0,.18);
    overflow: hidden;
}

.preview-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
}

.preview-modal-head h6 {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.preview-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: #f3f4f6;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: .2s;
}
.preview-close:hover { background: #fce7ef; color: var(--primary-color); }

.preview-modal-body {
    padding: 28px 30px;
    overflow-y: auto;
    flex: 1;
}

.preview-title-display {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 2px solid #fce7ef;
}

.preview-content-display {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.8;
}
</style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<div class="page-wrapper" id="page-wrapper">

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <a href="#">Pages</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">About Us</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>About Us</h1>
        <p>Edit the content displayed on the public-facing About Us page.</p>
    </div>

    <?php
        $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='aboutus'");
        $row = mysqli_fetch_array($ret);
    ?>

    <div class="content-grid">

        <!-- ── Main form card ────────────────────────── -->
        <div class="form-card">

            <div class="form-card-head">
                <div class="head-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <p class="head-title">Page Content</p>
                    <p class="head-sub">Changes will be reflected on the live site immediately</p>
                </div>
            </div>

            <div class="form-card-body">

                <?php if (!empty($msg)): ?>
                <div class="alert-box <?php echo $msgType; ?>">
                    <i class="bi bi-<?php echo $msgType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php endif; ?>

                <form method="post" id="aboutForm">

                    <!-- Page title -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-fonts"></i>
                            Page Title
                            <span class="field-hint">Displayed as the page heading</span>
                        </label>
                        <input type="text"
                               class="field-input"
                               name="pagetitle"
                               id="pagetitle"
                               placeholder="e.g. About GlamourSoft"
                               value="<?php echo htmlspecialchars($row['PageTitle']); ?>"
                               maxlength="120"
                               oninput="updateCharCount()"
                               required>
                        <div class="char-counter" id="charCounter">
                            <span id="charCount"><?php echo strlen($row['PageTitle']); ?></span> / 120 characters
                        </div>
                    </div>

                    <!-- Page description -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-text-paragraph"></i>
                            Page Description
                            <span class="field-hint">Supports rich text formatting</span>
                        </label>
                        <div class="editor-wrap">
                            <textarea name="pagedes"
                                      id="pagedes"
                                      rows="12"><?php echo $row['PageDescription']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn-save">
                            <i class="bi bi-cloud-arrow-up"></i>
                            Save & Publish
                        </button>
                        <button type="button" class="btn-preview" onclick="openPreview()">
                            <i class="bi bi-eye"></i>
                            Preview
                        </button>
                        <span class="save-note">
                            <i class="bi bi-info-circle"></i>
                            Changes go live instantly
                        </span>
                    </div>

                </form>

            </div>
        </div><!-- /.form-card -->

        <!-- ── Side info card ────────────────────────── -->
        <div class="info-card">

            <div class="info-card-banner">
                <div class="page-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <h5>About Us Page</h5>
                <p>Public-facing page tips</p>
            </div>

            <div class="info-card-body">

                <div class="info-row">
                    <span class="info-dot"><i class="bi bi-pencil"></i></span>
                    <div>
                        <strong>Page Title</strong>
                        Keep it short and recognisable — max 120 characters.
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-dot"><i class="bi bi-text-left"></i></span>
                    <div>
                        <strong>Description</strong>
                        Use the rich text editor to add headings, lists, bold text, and links.
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-dot"><i class="bi bi-lightning"></i></span>
                    <div>
                        <strong>Live Changes</strong>
                        Saving publishes content immediately — no extra step needed.
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-dot"><i class="bi bi-eye"></i></span>
                    <div>
                        <strong>Preview</strong>
                        Use the Preview button to see how the content will look before saving.
                    </div>
                </div>

                <div class="info-row">
                    <span class="info-dot"><i class="bi bi-link-45deg"></i></span>
                    <div>
                        <strong>Also edit</strong>
                        Visit <a href="contact-us.php" style="color:var(--primary-color);text-decoration:none;font-weight:600;">Contact Us</a> to manage that page's content.
                    </div>
                </div>

            </div>

        </div><!-- /.info-card -->

    </div><!-- /.content-grid -->

</div><!-- /.page-wrapper -->

<!-- ── Preview modal ────────────────────────────────── -->
<div class="preview-modal-overlay" id="previewOverlay" onclick="closePreviewOutside(event)">
    <div class="preview-modal">
        <div class="preview-modal-head">
            <h6><i class="bi bi-eye" style="color:var(--primary-color)"></i> Page Preview</h6>
            <button class="preview-close" onclick="closePreview()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="preview-modal-body">
            <div class="preview-title-display" id="previewTitle"></div>
            <div class="preview-content-display" id="previewContent"></div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    /* ── Sidebar toggle ─────────────────────────────── */
    const wrapperEl = document.getElementById('page-wrapper');
    const toggleBtn = document.getElementById('showLeftPush');
    if (toggleBtn && wrapperEl) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 992) wrapperEl.classList.toggle('full-width');
        });
    }

    /* ── Character counter ──────────────────────────── */
    function updateCharCount() {
        const input   = document.getElementById('pagetitle');
        const counter = document.getElementById('charCount');
        const wrap    = document.getElementById('charCounter');
        const len     = input.value.length;
        counter.textContent = len;
        wrap.classList.toggle('over', len >= 110);
    }
    updateCharCount();

    /* ── Preview modal ──────────────────────────────── */
    function openPreview() {
        const title   = document.getElementById('pagetitle').value;
        const overlay = document.getElementById('previewOverlay');

        document.getElementById('previewTitle').textContent = title || '(No title)';

        /* NicEdit stores content back into the textarea on save;
           try to read the live iframe content first, fall back to textarea */
        let content = '';
        try {
            const nicInst = nicEditors.findEditor('pagedes');
            content = nicInst ? nicInst.getContent() : document.getElementById('pagedes').value;
        } catch(e) {
            content = document.getElementById('pagedes').value;
        }

        document.getElementById('previewContent').innerHTML = content || '<em style="color:#9ca3af">No description entered yet.</em>';
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        document.getElementById('previewOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    function closePreviewOutside(e) {
        if (e.target === document.getElementById('previewOverlay')) closePreview();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePreview();
    });
</script>

<?php include_once('includes/footer.php'); ?>

</body>
</html>
<?php } ?>
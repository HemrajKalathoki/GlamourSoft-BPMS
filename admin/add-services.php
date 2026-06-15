<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {

        $sername = $_POST['sername'];
        $serdesc = $_POST['serdesc'];
        $cost    = $_POST['cost'];
        $image   = $_FILES["image"]["name"];

        $extension       = substr($image, strlen($image) - 4, strlen($image));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");

        if (!in_array($extension, $allowed_extensions)) {
            $msg     = "Invalid format. Only JPG / JPEG / PNG / GIF allowed.";
            $msgType = "error";
        } else {
            $newimage = md5($image) . time() . $extension;
            move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $newimage);

            $query = mysqli_query($con,
                "INSERT INTO tblservices (ServiceName, ServiceDescription, Cost, Image)
                 VALUES ('$sername','$serdesc','$cost','$newimage')"
            );

            if ($query) {
                $msg     = "Service has been added successfully.";
                $msgType = "success";
            } else {
                $msg     = "Something went wrong. Please try again.";
                $msgType = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BPMS | Add Service</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 20px; font-size: 13px; color: var(--text-muted);
}
.breadcrumb-bar a { color: var(--text-muted); text-decoration: none; transition: color .2s; }
.breadcrumb-bar a:hover { color: var(--primary-color); }
.breadcrumb-bar .bi-chevron-right { font-size: 11px; opacity: .5; }
.breadcrumb-bar .current { color: var(--primary-color); font-weight: 500; }

/* ── Page heading ───────────────────────────────────── */
.page-heading { margin-bottom: 26px; }
.page-heading h1 { font-size: 26px; font-weight: 700; color: var(--text-dark); margin: 0 0 5px; }
.page-heading p  { color: var(--text-muted); font-size: 14px; margin: 0; }

/* ── Two-column grid ────────────────────────────────── */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 22px;
    align-items: start;
}
@media (max-width: 960px) { .content-grid { grid-template-columns: 1fr; } }

/* ── Cards ──────────────────────────────────────────── */
.form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
}

.form-card-head {
    display: flex; align-items: center; gap: 12px;
    padding: 20px 26px; border-bottom: 1px solid var(--border);
}
.head-icon {
    width: 38px; height: 38px; border-radius: 11px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 17px; flex-shrink: 0;
}
.head-title { font-size: 16px; font-weight: 600; color: var(--text-dark); margin: 0; }
.head-sub   { font-size: 12px; color: var(--text-muted); margin: 2px 0 0; }

.form-card-body { padding: 28px; }

/* ── Alert ──────────────────────────────────────────── */
.alert-box {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 16px; border-radius: 12px;
    font-size: 13px; font-weight: 500; margin-bottom: 22px;
}
.alert-box.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-box.error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

/* ── Field groups ───────────────────────────────────── */
.field-group { margin-bottom: 22px; }
.field-group:last-child { margin-bottom: 0; }

.field-label {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px;
}
.field-label .bi { color: var(--primary-color); font-size: 14px; }
.field-label .req { color: var(--primary-color); }

.field-hint { font-size: 11px; color: var(--text-muted); margin-top: 5px; }

.input-wrap { position: relative; }
.input-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color); font-size: 15px; pointer-events: none;
}

.field-input {
    width: 100%; height: 50px;
    border: 1px solid #dbe2ea; border-radius: 12px;
    padding: 10px 16px 10px 42px;
    font-size: 14px; font-family: 'Poppins', sans-serif;
    color: var(--text-dark); background: #fff;
    transition: .25s ease;
}
.field-input:focus {
    outline: none; border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
}

textarea.field-input {
    height: auto; min-height: 120px;
    padding-top: 14px; resize: vertical; line-height: 1.6;
}

/* Cost input with currency prefix */
.cost-wrap { position: relative; }
.cost-prefix {
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 52px; border-radius: 12px 0 0 12px;
    background: #fce7ef; border: 1px solid #dbe2ea; border-right: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: var(--primary-color);
}
.cost-input {
    width: 100%; height: 50px;
    border: 1px solid #dbe2ea; border-radius: 0 12px 12px 0;
    padding: 10px 16px 10px 14px;
    font-size: 14px; font-family: 'Poppins', sans-serif;
    color: var(--text-dark); background: #fff; transition: .25s ease;
}
.cost-input:focus {
    outline: none; border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
    position: relative; z-index: 1;
}

/* ── File upload zone ───────────────────────────────── */
.upload-zone {
    border: 2px dashed #dbe2ea;
    border-radius: 14px;
    padding: 32px 20px;
    text-align: center;
    cursor: pointer;
    transition: .25s ease;
    background: #fafafa;
    position: relative;
}
.upload-zone:hover, .upload-zone.drag-over {
    border-color: var(--primary-color);
    background: #fef2f6;
}
.upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0;
    cursor: pointer; width: 100%; height: 100%;
}
.upload-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: #fce7ef; color: var(--primary-color);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin: 0 auto 14px;
    transition: transform .2s ease;
}
.upload-zone:hover .upload-icon { transform: translateY(-3px); }
.upload-zone h6 {
    font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 5px;
}
.upload-zone p { font-size: 12px; color: var(--text-muted); margin: 0; }
.upload-zone .browse-link { color: var(--primary-color); font-weight: 600; }

/* Image preview */
.img-preview-wrap {
    display: none;
    border-radius: 14px;
    overflow: hidden;
    border: 2px solid #fce7ef;
    position: relative;
    margin-top: 12px;
}
.img-preview-wrap img {
    width: 100%; height: 180px;
    object-fit: cover; display: block;
}
.img-preview-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: .2s;
}
.img-preview-wrap:hover .img-preview-overlay { opacity: 1; }
.img-clear-btn {
    background: #fff; border: none;
    border-radius: 50%; width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: var(--primary-color);
    cursor: pointer; transition: .2s;
}
.img-clear-btn:hover { background: #fce7ef; }

.file-formats {
    display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px;
}
.fmt-badge {
    background: #f3f4f6; color: var(--text-muted);
    font-size: 10px; font-weight: 600; padding: 3px 9px;
    border-radius: 5px; text-transform: uppercase; letter-spacing: .4px;
}

/* ── Form actions ───────────────────────────────────── */
.form-actions {
    display: flex; align-items: center; gap: 12px;
    padding-top: 22px; border-top: 1px solid var(--border); margin-top: 24px;
}
.btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    height: 50px; padding: 0 32px; border: none; border-radius: 13px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff; font-family: 'Poppins', sans-serif;
    font-size: 14px; font-weight: 600; cursor: pointer;
    transition: .25s ease; box-shadow: 0 4px 14px rgba(233,30,99,.25);
}
.btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(233,30,99,.32); }
.btn-save:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.btn-reset {
    display: inline-flex; align-items: center; gap: 7px;
    height: 50px; padding: 0 22px; border-radius: 13px;
    border: 1px solid var(--border); background: #fff;
    color: var(--text-muted); font-family: 'Poppins', sans-serif;
    font-size: 13px; font-weight: 500; cursor: pointer; transition: .2s ease;
}
.btn-reset:hover { background: #fce4ec; border-color: #f9a8c9; color: var(--primary-color); }

/* ── Right info card ────────────────────────────────── */
.info-card {
    background: #fff; border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
    position: sticky;
    top: calc(var(--navbar-height) + 20px);
}
.info-card-banner {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 28px 22px; text-align: center;
}
.info-banner-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px; font-size: 26px; color: #fff;
}
.info-card-banner h5 { font-size: 15px; font-weight: 700; color: #fff; margin: 0 0 4px; }
.info-card-banner p  { font-size: 12px; color: rgba(255,255,255,.8); margin: 0; }

.info-tip-list { padding: 6px 0; }
.info-tip {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 20px; border-bottom: 1px solid #f3f4f6;
    font-size: 13px; color: var(--text-muted); line-height: 1.5;
}
.info-tip:last-child { border-bottom: none; }
.tip-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: #fce7ef; color: var(--primary-color);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0; margin-top: 1px;
}
.info-tip strong { display: block; color: var(--text-dark); font-weight: 600; margin-bottom: 2px; }

/* Manage link */
.manage-link {
    display: flex; align-items: center; justify-content: center; gap: 7px;
    padding: 16px 20px;
    background: #fafafa; border-top: 1px solid var(--border);
    font-size: 13px; font-weight: 600;
    color: var(--primary-color); text-decoration: none;
    transition: .2s;
}
.manage-link:hover { background: #fce7ef; color: var(--primary-color); }
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
        <a href="manage-services.php">Services</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Add Service</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Add Service</h1>
        <p>Create a new parlour service to display on the booking portal.</p>
    </div>

    <div class="content-grid">

        <!-- ── LEFT: form ────────────────────────────── -->
        <div class="form-card">

            <div class="form-card-head">
                <div class="head-icon"><i class="bi bi-plus-circle"></i></div>
                <div>
                    <p class="head-title">Service Details</p>
                    <p class="head-sub">Fill in the details below to create a new service</p>
                </div>
            </div>

            <div class="form-card-body">

                <?php if (!empty($msg)): ?>
                <div class="alert-box <?php echo $msgType; ?>">
                    <i class="bi bi-<?php echo $msgType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" id="serviceForm">

                    <!-- Service Name -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-scissors"></i>
                            Service Name <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-scissors input-icon"></i>
                            <input type="text"
                                   class="field-input"
                                   name="sername"
                                   id="sername"
                                   placeholder="e.g. Hair Colouring, Facial, Manicure"
                                   required>
                        </div>
                    </div>

                    <!-- Service Description -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-text-paragraph"></i>
                            Service Description <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <textarea class="field-input"
                                      name="serdesc"
                                      id="serdesc"
                                      rows="4"
                                      placeholder="Describe what this service includes..."
                                      required></textarea>
                        </div>
                    </div>

                    <!-- Cost -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-tag"></i>
                            Service Cost <span class="req">*</span>
                        </label>
                        <div class="cost-wrap">
                            <span class="cost-prefix">Rs.</span>
                            <input type="number"
                                   class="cost-input"
                                   name="cost"
                                   id="cost"
                                   placeholder="0.00"
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                        <p class="field-hint">Enter the price in Nepalese Rupees.</p>
                    </div>

                    <!-- Image upload -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-image"></i>
                            Service Image <span class="req">*</span>
                        </label>

                        <div class="upload-zone" id="uploadZone">
                            <input type="file"
                                   name="image"
                                   id="imageInput"
                                   accept=".jpg,.jpeg,.png,.gif"
                                   required>
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h6>Drop your image here</h6>
                            <p>or <span class="browse-link">browse files</span></p>
                        </div>

                        <!-- Preview -->
                        <div class="img-preview-wrap" id="imgPreviewWrap">
                            <img src="" id="imgPreview" alt="Preview">
                            <div class="img-preview-overlay">
                                <button type="button" class="img-clear-btn" id="clearImage" title="Remove">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="file-formats">
                            <span class="fmt-badge">JPG</span>
                            <span class="fmt-badge">JPEG</span>
                            <span class="fmt-badge">PNG</span>
                            <span class="fmt-badge">GIF</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn-save" id="saveBtn">
                            <i class="bi bi-plus-circle"></i>
                            Add Service
                        </button>
                        <button type="reset" class="btn-reset" id="resetBtn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                    </div>

                </form>

            </div>
        </div><!-- /.form-card -->

        <!-- ── RIGHT: info card ──────────────────────── -->
        <div class="info-card">

            <div class="info-card-banner">
                <div class="info-banner-icon">
                    <i class="bi bi-lightbulb"></i>
                </div>
                <h5>Service Tips</h5>
                <p>Best practices for adding services</p>
            </div>

            <div class="info-tip-list">
                <div class="info-tip">
                    <span class="tip-icon"><i class="bi bi-scissors"></i></span>
                    <div>
                        <strong>Service Name</strong>
                        Use a clear, specific name customers will recognise (e.g. "Deep Conditioning Treatment").
                    </div>
                </div>
                <div class="info-tip">
                    <span class="tip-icon"><i class="bi bi-text-left"></i></span>
                    <div>
                        <strong>Description</strong>
                        Mention what's included, duration, and any prep needed — helps customers choose confidently.
                    </div>
                </div>
                <div class="info-tip">
                    <span class="tip-icon"><i class="bi bi-tag"></i></span>
                    <div>
                        <strong>Pricing</strong>
                        Keep prices competitive. You can always update them from Manage Services.
                    </div>
                </div>
                <div class="info-tip">
                    <span class="tip-icon"><i class="bi bi-image"></i></span>
                    <div>
                        <strong>Image</strong>
                        Use a bright, high-quality square image (min 400×400 px) for best results on the booking page.
                    </div>
                </div>
            </div>

            <a href="manage-services.php" class="manage-link">
                <i class="bi bi-grid-3x3-gap"></i>
                View All Services
            </a>

        </div><!-- /.info-card -->

    </div><!-- /.content-grid -->

</div><!-- /.page-wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /* ── Sidebar toggle ─────────────────────────────── */
    const wrapperEl = document.getElementById('page-wrapper');
    const toggleBtn = document.getElementById('showLeftPush');
    if (toggleBtn && wrapperEl) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 992) wrapperEl.classList.toggle('full-width');
        });
    }

    /* ── Image preview ──────────────────────────────── */
    const imageInput    = document.getElementById('imageInput');
    const imgPreview    = document.getElementById('imgPreview');
    const imgPreviewWrap = document.getElementById('imgPreviewWrap');
    const uploadZone    = document.getElementById('uploadZone');
    const clearBtn      = document.getElementById('clearImage');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreviewWrap.style.display = 'block';
            uploadZone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    clearBtn.addEventListener('click', () => {
        imageInput.value = '';
        imgPreview.src = '';
        imgPreviewWrap.style.display = 'none';
        uploadZone.style.display = 'block';
    });

    /* ── Drag and drop ──────────────────────────────── */
    uploadZone.addEventListener('dragover', e => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', e => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            imageInput.dispatchEvent(new Event('change'));
        }
    });

    /* ── Reset button clears preview too ────────────── */
    document.getElementById('resetBtn').addEventListener('click', () => {
        imgPreview.src = '';
        imgPreviewWrap.style.display = 'none';
        uploadZone.style.display = 'block';
    });

    /* ── Submit spinner ─────────────────────────────── */
    document.getElementById('serviceForm').addEventListener('submit', function () {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Adding...';
    });
</script>

<?php include_once('includes/footer.php'); ?>

</body>
</html>
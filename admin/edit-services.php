<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── UPDATE SERVICE ─────────────────────────────── */
    if (isset($_POST['submit'])) {
        $sername = $_POST['sername'];
        $serdesc = $_POST['serdesc'];
        $cost    = $_POST['cost'];
        $eid     = $_GET['editid'];

        $query = mysqli_query($con,
            "UPDATE tblservices
             SET ServiceName='$sername', ServiceDescription='$serdesc', Cost='$cost'
             WHERE ID='$eid'"
        );

        if ($query) {
            echo "<script>alert('Service has been updated successfully.');</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Edit Service</title>

    <!-- Bootstrap 5 — same version as header.php -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons — same as header.php & sidebar.php -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Poppins — matches header.php body font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Variables — mirror header.php & sidebar.php ─── */
        :root {
            --primary-color:   #e91e63;
            --secondary-color: #ff4f81;
            --pk-light:        #fce4ec;
            --pk-soft:         #fff5f8;
            --pk-border:       #f8bbd0;
            --card-shadow:     0 4px 24px rgba(233,30,99,.07);
            --radius:          16px;
        }

        body { font-family: 'Poppins', sans-serif; background: #f9f4f7; }

        /* ── Layout — matches sidebar.php toggle logic ───── */
        .dashboard-wrapper {
            margin-left: 280px;       /* clears fixed sidebar       */
            margin-top: 78px;         /* clears fixed .admin-navbar */
            padding: 32px 28px 52px;
            transition: margin-left .3s ease;
            min-height: calc(100vh - 78px);
        }
        .dashboard-wrapper.full-width { margin-left: 0; }

        @media (max-width: 991px) {
            .dashboard-wrapper { margin-left: 0; padding: 24px 16px 40px; }
        }

        /* ── Page Header ─────────────────────────────────── */
        .page-header {
            display: flex; align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; gap: 14px;
            margin-bottom: 28px;
        }
        .page-header-left { display: flex; align-items: center; gap: 14px; }
        .ph-icon {
            width: 50px; height: 50px; background: var(--pk-light);
            border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 22px;
            color: var(--primary-color); flex-shrink: 0;
        }
        .page-header h4 { font-size: 1.2rem; font-weight: 700; color: #1a1a2e; margin: 0 0 2px; }
        .page-header p  { font-size: .77rem; color: #888; margin: 0; }
        .breadcrumb { font-size: .74rem; margin-bottom: 4px; padding: 0; background: none; }
        .breadcrumb-item a  { color: var(--primary-color); text-decoration: none; font-weight: 500; }
        .breadcrumb-item.active { color: #999; }

        /* Back button */
        .btn-back {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fff; color: #555;
            border: 1.5px solid #e8e8e8; padding: 10px 18px;
            border-radius: 12px; font-size: .84rem; font-weight: 600;
            font-family: 'Poppins', sans-serif; text-decoration: none;
            transition: border-color .2s, color .2s, transform .15s;
        }
        .btn-back:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* ── Form Card ───────────────────────────────────── */
        .form-card {
            background: #fff; border-radius: var(--radius);
            box-shadow: var(--card-shadow); border: 1px solid #f3e0ea;
            overflow: hidden; max-width: 780px;
        }

        .form-card-header {
            padding: 18px 28px; background: var(--pk-soft);
            border-bottom: 1px solid #f3e0ea;
            display: flex; align-items: center; gap: 10px;
        }
        .form-card-header-icon {
            width: 38px; height: 38px; background: var(--pk-light);
            border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-size: 1.1rem;
            color: var(--primary-color); flex-shrink: 0;
        }
        .form-card-header h6 {
            font-size: .93rem; font-weight: 700; color: #1a1a2e; margin: 0;
        }
        .form-card-header p {
            font-size: .74rem; color: #888; margin: 2px 0 0;
        }

        .form-body { padding: 28px; }

        /* ── Form Labels & Inputs ────────────────────────── */
        .form-label-custom {
            font-size: .78rem; font-weight: 600; color: #555;
            margin-bottom: 7px; display: flex; align-items: center; gap: 6px;
        }
        .form-label-custom i { color: var(--primary-color); font-size: .9rem; }

        .form-control-custom {
            height: 48px; border-radius: 12px;
            border: 1.5px solid #e8dded; background: var(--pk-soft);
            font-size: .88rem; color: #222; font-family: 'Poppins', sans-serif;
            padding: 0 16px;
            transition: border-color .2s, box-shadow .2s, background .2s;
            width: 100%;
        }
        .form-control-custom:focus {
            border-color: var(--primary-color); background: #fff;
            box-shadow: 0 0 0 4px rgba(233,30,99,.08); outline: none;
        }
        .form-control-custom::placeholder { color: #bbb; }

        /* Textarea variant */
        textarea.form-control-custom {
            height: 110px; padding: 14px 16px;
            resize: vertical; line-height: 1.6;
        }

        /* Cost input with Rs. prefix */
        .input-prefix-wrap { position: relative; }
        .input-prefix {
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 48px; display: flex; align-items: center;
            justify-content: center;
            font-size: .78rem; font-weight: 700; color: var(--primary-color);
            background: var(--pk-light);
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e8dded;
            border-right: none; pointer-events: none;
        }
        .input-with-prefix {
            padding-left: 58px;
        }

        /* Image preview card */
        .image-section {
            background: var(--pk-soft); border-radius: 12px;
            border: 1.5px dashed var(--pk-border); padding: 20px;
            display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
        }
        .image-preview {
            width: 90px; height: 90px; border-radius: 12px;
            object-fit: cover; border: 2px solid var(--pk-border);
            background: #fff;
        }
        .image-preview-placeholder {
            width: 90px; height: 90px; border-radius: 12px;
            background: var(--pk-light); display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 4px;
            border: 2px solid var(--pk-border); flex-shrink: 0;
        }
        .image-preview-placeholder i { font-size: 1.8rem; color: var(--primary-color); }
        .image-preview-placeholder span { font-size: .68rem; color: #aaa; }
        .image-meta { flex: 1; }
        .image-meta p { font-size: .78rem; color: #777; margin: 0 0 10px; line-height: 1.6; }
        .btn-update-img {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff; color: var(--primary-color);
            border: 1.5px solid var(--pk-border); padding: 8px 16px;
            border-radius: 10px; font-size: .78rem; font-weight: 600;
            font-family: 'Poppins', sans-serif; text-decoration: none;
            transition: background .2s, border-color .2s, transform .15s;
        }
        .btn-update-img:hover {
            background: var(--pk-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Divider */
        .form-divider {
            border: none; border-top: 1px solid #f3e0ea; margin: 22px 0;
        }

        /* ── Submit Row ──────────────────────────────────── */
        .submit-row {
            display: flex; align-items: center;
            justify-content: flex-end; gap: 12px;
            padding-top: 8px;
        }

        .btn-cancel {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fff; color: #666;
            border: 1.5px solid #ddd; padding: 12px 22px;
            border-radius: 12px; font-size: .88rem; font-weight: 600;
            font-family: 'Poppins', sans-serif; text-decoration: none;
            transition: border-color .2s, color .2s;
            cursor: pointer;
        }
        .btn-cancel:hover { border-color: #bbb; color: #333; }

        .btn-update {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff; border: none; padding: 12px 28px;
            border-radius: 12px; font-size: .88rem; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(233,30,99,.28); cursor: pointer;
        }
        .btn-update:hover {
            opacity: .92; transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(233,30,99,.35); color: #fff;
        }
        .btn-update:active { transform: translateY(0); }

        /* ── Validation feedback ─────────────────────────── */
        .field-error {
            font-size: .73rem; color: #c62828;
            margin-top: 5px; display: none;
            align-items: center; gap: 4px;
        }
        .field-error.show { display: flex; }
        .form-control-custom.invalid {
            border-color: #f44336;
            box-shadow: 0 0 0 4px rgba(244,67,54,.08);
        }
    </style>
</head>

<body>

    <!-- sidebar.php — renders #sidebar-wrapper, handles its own toggle JS -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- header.php — renders .admin-navbar fixed at top, loads Bootstrap 5 JS -->
    <?php include_once('includes/header.php'); ?>

    <!-- ── Main content wrapper ──────────────────────────────
         margin-left: 280px  → clears fixed sidebar
         margin-top:  78px   → clears fixed .admin-navbar
         sidebar.php JS toggles .full-width here on desktop collapse
    ─────────────────────────────────────────────────────────── -->
    <div class="dashboard-wrapper" id="dashboard-wrapper">

        <!-- ── Page Header ──────────────────────────────── -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="ph-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="manage-services.php">Manage Services</a></li>
                            <li class="breadcrumb-item active">Edit Service</li>
                        </ol>
                    </nav>
                    <h4>Edit Service</h4>
                    <p>Update service name, description, and pricing</p>
                </div>
            </div>
            <a href="manage-services.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back to Services
            </a>
        </div>

        <!-- ── Form Card ─────────────────────────────────── -->
        <div class="form-card">

            <!-- Card header -->
            <div class="form-card-header">
                <div class="form-card-header-icon">
                    <i class="bi bi-scissors"></i>
                </div>
                <div>
                    <h6>Service Details</h6>
                    <p>All fields are required unless marked optional</p>
                </div>
            </div>

            <div class="form-body">

                <?php
                /* ── Load current service data ─────────────── */
                $cid = $_GET['editid'];
                $ret = mysqli_query($con, "SELECT * FROM tblservices WHERE ID='$cid'");
                while ($row = mysqli_fetch_array($ret)) :
                ?>

                <form method="post" id="editServiceForm" novalidate>

                    <!-- Service Name -->
                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="bi bi-scissors"></i> Service Name
                        </label>
                        <input
                            type="text"
                            name="sername"
                            id="sername"
                            class="form-control-custom"
                            placeholder="e.g. Hair Cut & Styling"
                            value="<?php echo htmlspecialchars($row['ServiceName']); ?>"
                            required
                        >
                        <div class="field-error" id="err-sername">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            Service name is required.
                        </div>
                    </div>

                    <!-- Service Description -->
                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="bi bi-card-text"></i> Service Description
                        </label>
                        <textarea
                            name="serdesc"
                            id="serdesc"
                            class="form-control-custom"
                            placeholder="Describe what this service includes…"
                            required
                        ><?php echo htmlspecialchars($row['ServiceDescription']); ?></textarea>
                        <div class="field-error" id="err-serdesc">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            Service description is required.
                        </div>
                    </div>

                    <!-- Cost -->
                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="bi">Rs.</i> Service Cost (Rs.)
                        </label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rs.</span>
                            <input
                                type="number"
                                name="cost"
                                id="cost"
                                class="form-control-custom input-with-prefix"
                                placeholder="0"
                                min="0"
                                value="<?php echo htmlspecialchars($row['Cost']); ?>"
                                required
                            >
                        </div>
                        <div class="field-error" id="err-cost">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            Please enter a valid cost (numbers only).
                        </div>
                    </div>

                    <hr class="form-divider">

                    <!-- Service Image (optional / view + update link) -->
                    <div class="mb-4">
                        <label class="form-label-custom">
                            <i class="bi bi-image"></i> Service Image
                            <span style="font-weight:400;color:#aaa;font-size:.7rem;margin-left:4px">(optional)</span>
                        </label>
                        <div class="image-section">
                            <?php if (!empty($row['Image'])) : ?>
                                <img
                                    src="images/<?php echo htmlspecialchars($row['Image']); ?>"
                                    class="image-preview"
                                    alt="Service image"
                                    onerror="this.style.display='none'; document.getElementById('img-placeholder').style.display='flex';"
                                >
                                <div class="image-preview-placeholder" id="img-placeholder" style="display:none">
                                    <i class="bi bi-image-alt"></i>
                                    <span>No preview</span>
                                </div>
                            <?php else : ?>
                                <div class="image-preview-placeholder">
                                    <i class="bi bi-image-alt"></i>
                                    <span>No image</span>
                                </div>
                            <?php endif; ?>
                            <div class="image-meta">
                                <p>
                                    To replace the service image, use the dedicated image update page.
                                    Supported formats: JPG, PNG, WEBP.
                                </p>
                                <a href="update-image.php?lid=<?php echo $row['ID']; ?>" class="btn-update-img">
                                    <i class="bi bi-cloud-upload"></i> Update Image
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="form-divider">

                    <!-- Submit row -->
                    <div class="submit-row">
                        <a href="manage-services.php" class="btn-cancel">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                        <button type="submit" name="submit" class="btn-update">
                            <i class="bi bi-check2-circle"></i> Save Changes
                        </button>
                    </div>

                </form>

                <?php endwhile; ?>

            </div><!-- /form-body -->
        </div><!-- /form-card -->

    </div><!-- /dashboard-wrapper -->

    <!-- Bootstrap 5 JS — safety fallback, header.php already loads it -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        /* ── Client-side validation ─────────────────────── */
        document.getElementById('editServiceForm').addEventListener('submit', function (e) {
            let valid = true;

            /* Helper: show/hide error + toggle .invalid class */
            function validate(fieldId, errorId, condition) {
                const el  = document.getElementById(fieldId);
                const err = document.getElementById(errorId);
                if (condition) {
                    el.classList.add('invalid');
                    err.classList.add('show');
                    valid = false;
                } else {
                    el.classList.remove('invalid');
                    err.classList.remove('show');
                }
            }

            const name = document.getElementById('sername').value.trim();
            const desc = document.getElementById('serdesc').value.trim();
            const cost = document.getElementById('cost').value.trim();

            validate('sername', 'err-sername', name === '');
            validate('serdesc', 'err-serdesc', desc === '');
            validate('cost',    'err-cost',    cost === '' || isNaN(cost) || Number(cost) < 0);

            if (!valid) e.preventDefault();
        });

        /* ── Clear error on input ───────────────────────── */
        ['sername', 'serdesc', 'cost'].forEach(function (id) {
            document.getElementById(id).addEventListener('input', function () {
                this.classList.remove('invalid');
                const err = document.getElementById('err-' + id);
                if (err) err.classList.remove('show');
            });
        });
    </script>

</body>
</html>
<?php  ?>
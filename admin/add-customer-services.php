<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── CREATE INVOICE ──────────────────────────────── */
    if (isset($_POST['submit'])) {
        $uid       = intval($_GET['addid']);
        $invoiceid = mt_rand(100000000, 999999999);
        $sid       = $_POST['sids'];

        for ($i = 0; $i < count($sid); $i++) {
            $svid = $sid[$i];
            mysqli_query($con,
                "INSERT INTO tblinvoice(Userid, ServiceId, BillingId)
                 VALUES('$uid','$svid','$invoiceid')"
            );
        }

        echo '<script>alert("Invoice created successfully.\nInvoice Number: ' . $invoiceid . '")</script>';
        echo "<script>window.location.href='invoices.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Assign Services</title>

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

        /* ── Two-col layout ──────────────────────────────── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 22px;
            align-items: start;
        }
        @media (max-width: 1100px) {
            .content-grid { grid-template-columns: 1fr; }
        }

        /* ── Services Card ───────────────────────────────── */
        .services-card {
            background: #fff; border-radius: var(--radius);
            box-shadow: var(--card-shadow); border: 1px solid #f3e0ea;
            overflow: hidden;
        }
        .card-top {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
            padding: 18px 24px; background: var(--pk-soft);
            border-bottom: 1px solid #f3e0ea;
        }
        .card-top h6 {
            font-size: .93rem; font-weight: 700; color: #1a1a2e;
            margin: 0; display: flex; align-items: center; gap: 8px;
        }
        .selected-count {
            background: var(--pk-light); color: var(--primary-color);
            font-size: .71rem; font-weight: 700;
            padding: 4px 14px; border-radius: 20px;
            transition: background .2s;
        }
        .selected-count.has-items {
            background: var(--primary-color); color: #fff;
        }

        /* Filter bar inside card */
        .card-filter {
            padding: 14px 24px; border-bottom: 1px solid #f3e0ea;
        }
        .filter-group { position: relative; }
        .filter-group .f-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%); color: var(--primary-color);
            font-size: .9rem; pointer-events: none;
        }
        .filter-group input {
            padding-left: 36px; height: 40px; border-radius: 10px;
            border: 1.5px solid var(--pk-border); background: var(--pk-soft);
            font-size: .82rem; color: #222; font-family: 'Poppins', sans-serif;
            transition: border-color .2s, box-shadow .2s; width: 100%;
        }
        .filter-group input:focus {
            border-color: var(--primary-color); background: #fff;
            box-shadow: 0 0 0 3px rgba(233,30,99,.09); outline: none;
        }
        .filter-group input::placeholder { color: #bbb; }

        /* ── Service rows ────────────────────────────────── */
        .svc-list { padding: 8px 0; }

        .svc-row {
            display: flex; align-items: center; gap: 14px;
            padding: 13px 22px; cursor: pointer;
            border-bottom: 1px solid #fdf0f4;
            transition: background .15s;
            user-select: none;
        }
        .svc-row:last-child { border-bottom: none; }
        .svc-row:hover { background: var(--pk-soft); }
        .svc-row.selected { background: #fff5fb; }

        /* Hidden real checkbox */
        .svc-row input[type="checkbox"] { display: none; }

        /* Custom checkbox */
        .custom-check {
            width: 22px; height: 22px; border-radius: 7px;
            border: 2px solid #ddd; background: #fff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: border-color .2s, background .2s;
        }
        .custom-check .bi-check2 {
            color: #fff; font-size: .95rem; opacity: 0;
            transition: opacity .15s;
        }
        .svc-row.selected .custom-check {
            border-color: var(--primary-color);
            background: var(--primary-color);
        }
        .svc-row.selected .custom-check .bi-check2 { opacity: 1; }

        /* Service icon */
        .svc-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--pk-light); color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
            transition: background .15s;
        }
        .svc-row.selected .svc-icon {
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff;
        }

        /* Service info */
        .svc-info { flex: 1; min-width: 0; }
        .svc-name {
            font-size: .88rem; font-weight: 600; color: #1a1a2e;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .svc-id { font-size: .72rem; color: #aaa; margin-top: 1px; }

        /* Price tag */
        .svc-price {
            display: inline-flex; align-items: center; gap: 3px;
            background: #e8f5e9; color: #2e7d32;
            font-size: .78rem; font-weight: 700;
            padding: 4px 10px; border-radius: 20px; white-space: nowrap;
            flex-shrink: 0; transition: background .15s, color .15s;
        }
        .svc-row.selected .svc-price {
            background: #fce4ec; color: var(--primary-color);
        }

        /* ── Sticky Summary Card ─────────────────────────── */
        .summary-card {
            background: #fff; border-radius: var(--radius);
            box-shadow: var(--card-shadow); border: 1px solid #f3e0ea;
            overflow: hidden; position: sticky; top: 100px;
        }
        .summary-header {
            padding: 18px 22px; background: var(--pk-soft);
            border-bottom: 1px solid #f3e0ea;
        }
        .summary-header h6 {
            font-size: .93rem; font-weight: 700; color: #1a1a2e;
            margin: 0; display: flex; align-items: center; gap: 8px;
        }
        .summary-body { padding: 18px 22px; }

        /* Customer info row */
        .cust-info-row {
            display: flex; align-items: center; gap: 12px;
            background: var(--pk-soft); border-radius: 12px;
            padding: 13px 15px; margin-bottom: 18px;
            border: 1px solid var(--pk-border);
        }
        .cust-av {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff; font-size: .78rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .cust-label { font-size: .72rem; color: #aaa; }
        .cust-val   { font-size: .86rem; font-weight: 700; color: #1a1a2e; }

        /* Selected list */
        .selected-list { min-height: 60px; margin-bottom: 16px; }
        .selected-item {
            display: flex; align-items: center; justify-content: space-between;
            gap: 8px; padding: 8px 0;
            border-bottom: 1px dashed #f3e0ea; font-size: .82rem;
        }
        .selected-item:last-child { border-bottom: none; }
        .selected-item-name {
            display: flex; align-items: center; gap: 7px;
            color: #333; font-weight: 500; flex: 1; min-width: 0;
        }
        .selected-item-name span {
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .selected-item-name .bi { color: var(--primary-color); font-size: .85rem; flex-shrink: 0; }
        .selected-item-price {
            color: #2e7d32; font-weight: 700; font-size: .82rem; white-space: nowrap;
        }

        .empty-selection {
            text-align: center; padding: 20px 0; color: #bbb; font-size: .82rem;
        }
        .empty-selection i { font-size: 1.5rem; display: block; margin-bottom: 6px; }

        /* Total row */
        .total-row {
            display: flex; align-items: center; justify-content: space-between;
            background: var(--pk-soft); border-radius: 12px;
            padding: 13px 16px; border: 1px solid var(--pk-border);
            margin-bottom: 18px;
        }
        .total-label {
            font-size: .82rem; font-weight: 600; color: #555;
            display: flex; align-items: center; gap: 6px;
        }
        .total-label i { color: var(--primary-color); }
        .total-amount {
            font-size: 1.15rem; font-weight: 700; color: var(--primary-color);
        }

        /* Validation error */
        .val-error {
            background: #fce4ec; color: #c62828; border-radius: 10px;
            padding: 10px 14px; font-size: .78rem; font-weight: 500;
            margin-bottom: 14px; display: none;
            align-items: center; gap: 7px;
        }
        .val-error.show { display: flex; }

        /* Submit button */
        .btn-submit {
            width: 100%; height: 50px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            border: none; border-radius: 12px; color: #fff;
            font-size: .92rem; font-weight: 700; font-family: 'Poppins', sans-serif;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(233,30,99,.28); cursor: pointer;
        }
        .btn-submit:hover {
            opacity: .92; transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(233,30,99,.35);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled {
            opacity: .45; transform: none;
            box-shadow: none; cursor: not-allowed;
        }

        /* Select all toggle */
        .select-all-btn {
            font-size: .74rem; font-weight: 600; color: var(--primary-color);
            background: none; border: none; padding: 0; cursor: pointer;
            font-family: 'Poppins', sans-serif; text-decoration: underline;
            text-underline-offset: 2px;
        }

        /* No results state */
        .no-results {
            text-align: center; padding: 36px 24px; color: #bbb; display: none;
        }
        .no-results i { font-size: 2rem; display: block; margin-bottom: 8px; }
        .no-results span { font-size: .83rem; }
    </style>
</head>

<body>

    <!-- sidebar.php — renders #sidebar-wrapper, handles its own toggle JS -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- header.php — renders .admin-navbar fixed at top, loads Bootstrap 5 JS -->
    <?php include_once('includes/header.php'); ?>

    <div class="dashboard-wrapper" id="dashboard-wrapper">

        <!-- ── Page Header ──────────────────────────────── -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="ph-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="invoices.php">Billing</a></li>
                            <li class="breadcrumb-item active">Assign Services</li>
                        </ol>
                    </nav>
                    <h4>Assign Services</h4>
                    <p>Select services to include in this customer's invoice</p>
                </div>
            </div>
            <a href="invoices.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back to Invoices
            </a>
        </div>

        <!-- ── Two-column grid ──────────────────────────── -->
        <form method="post" id="assignForm" novalidate>
        <div class="content-grid">

            <!-- LEFT — Service list -->
            <div class="services-card">
                <div class="card-top">
                    <h6>
                        <i class="bi bi-scissors" style="color:var(--primary-color)"></i>
                        Available Services
                    </h6>
                    <div style="display:flex;align-items:center;gap:12px">
                        <button type="button" class="select-all-btn" id="selectAllBtn">Select all</button>
                        <span class="selected-count" id="selectedCount">0 selected</span>
                    </div>
                </div>

                <!-- Search / filter -->
                <div class="card-filter">
                    <div class="filter-group">
                        <i class="bi bi-funnel f-icon"></i>
                        <input type="text" id="filterInput"
                            placeholder="Filter services by name…" autocomplete="off">
                    </div>
                </div>

                <!-- Rows -->
                <div class="svc-list" id="svcList">
                    <?php
                    $ret = mysqli_query($con, "SELECT * FROM tblservices ORDER BY ServiceName ASC");
                    $cnt = 1;
                    while ($row = mysqli_fetch_array($ret)) :
                    ?>
                    <label class="svc-row" data-name="<?php echo strtolower(htmlspecialchars($row['ServiceName'])); ?>">
                        <input type="checkbox" name="sids[]"
                               value="<?php echo $row['ID']; ?>"
                               data-name="<?php echo htmlspecialchars($row['ServiceName']); ?>"
                               data-price="<?php echo $row['Cost']; ?>">
                        <span class="custom-check"><i class="bi bi-check2"></i></span>
                        <span class="svc-icon"><i class="bi bi-scissors"></i></span>
                        <span class="svc-info">
                            <span class="svc-name"><?php echo htmlspecialchars($row['ServiceName']); ?></span>
                            <span class="svc-id">Service #<?php echo $row['ID']; ?></span>
                        </span>
                        <span class="svc-price">
                            <i class="bi">Rs.</i>
                            <?php echo number_format($row['Cost'], 0); ?>
                        </span>
                    </label>
                    <?php $cnt++; endwhile; ?>

                    <div class="no-results" id="noResults">
                        <i class="bi bi-search"></i>
                        <span>No services match your filter</span>
                    </div>
                </div>
            </div><!-- /services-card -->

            <!-- RIGHT — Summary / checkout panel -->
            <div class="summary-card">
                <div class="summary-header">
                    <h6>
                        <i class="bi bi-bag-check" style="color:var(--primary-color)"></i>
                        Invoice Summary
                    </h6>
                </div>
                <div class="summary-body">

                    <!-- Customer chip -->
                    <?php
                    $uid = intval($_GET['addid']);
                    $ures = mysqli_query($con, "SELECT FirstName, LastName FROM tbluser WHERE ID='$uid'");
                    $urow = mysqli_fetch_array($ures);
                    $fname = $urow ? htmlspecialchars($urow['FirstName']) : 'Customer';
                    $lname = $urow ? htmlspecialchars($urow['LastName'])  : '';
                    $initials = strtoupper(substr($fname,0,1) . substr($lname,0,1));
                    ?>
                    <div class="cust-info-row">
                        <div class="cust-av"><?php echo $initials; ?></div>
                        <div>
                            <div class="cust-label">Billing for</div>
                            <div class="cust-val"><?php echo $fname . ' ' . $lname; ?></div>
                        </div>
                    </div>

                    <!-- Selected services list -->
                    <div class="section-label" style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--primary-color);margin-bottom:10px;display:flex;align-items:center;gap:6px">
                        <i class="bi bi-list-check"></i> Selected Services
                    </div>

                    <div class="selected-list" id="selectedList">
                        <div class="empty-selection" id="emptyMsg">
                            <i class="bi bi-clipboard-plus"></i>
                            Select services from the left
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="total-row">
                        <span class="total-label">
                            <i class="bi bi-calculator"></i> Total Amount
                        </span>
                        <span class="total-amount" id="totalAmount">Rs. 0</span>
                    </div>

                    <!-- Validation error -->
                    <div class="val-error" id="valError">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Please select at least one service.
                    </div>

                    <!-- Submit -->
                    <button type="submit" name="submit" class="btn-submit" id="submitBtn">
                        <i class="bi bi-receipt"></i> Generate Invoice
                    </button>

                </div>
            </div><!-- /summary-card -->

        </div><!-- /content-grid -->
        </form>

    </div><!-- /dashboard-wrapper -->

    <!-- Bootstrap 5 JS — safety fallback, header.php already loads it -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        /* ── DOM refs ───────────────────────────────────── */
        const rows         = document.querySelectorAll('.svc-row');
        const selectedList = document.getElementById('selectedList');
        const emptyMsg     = document.getElementById('emptyMsg');
        const totalEl      = document.getElementById('totalAmount');
        const countEl      = document.getElementById('selectedCount');
        const valError     = document.getElementById('valError');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const filterInput  = document.getElementById('filterInput');
        const noResults    = document.getElementById('noResults');

        /* ── Toggle a service row ───────────────────────── */
        rows.forEach(function (row) {
            row.addEventListener('click', function () {
                const cb = this.querySelector('input[type="checkbox"]');
                cb.checked = !cb.checked;
                this.classList.toggle('selected', cb.checked);
                updateSummary();
            });
        });

        /* ── Update summary panel ───────────────────────── */
        function updateSummary() {
            const checked = document.querySelectorAll('.svc-row.selected input[type="checkbox"]');
            let total = 0;

            /* Clear list */
            selectedList.innerHTML = '';

            if (checked.length === 0) {
                selectedList.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
            } else {
                checked.forEach(function (cb) {
                    const price = parseFloat(cb.dataset.price) || 0;
                    const name  = cb.dataset.name;
                    total += price;

                    const item = document.createElement('div');
                    item.className = 'selected-item';
                    item.innerHTML =
                        '<span class="selected-item-name">' +
                            '<i class="bi bi-check-circle-fill"></i>' +
                            '<span>' + name + '</span>' +
                        '</span>' +
                        '<span class="selected-item-price">Rs. ' + price.toLocaleString() + '</span>';
                    selectedList.appendChild(item);
                });
            }

            /* Update total */
            totalEl.textContent = 'Rs. ' + total.toLocaleString();

            /* Update badge */
            countEl.textContent = checked.length + ' selected';
            countEl.classList.toggle('has-items', checked.length > 0);

            /* Toggle select-all label */
            const allVisible = document.querySelectorAll('.svc-row:not([style*="display: none"]) input[type="checkbox"]');
            const allChecked = document.querySelectorAll('.svc-row:not([style*="display: none"]).selected input[type="checkbox"]');
            selectAllBtn.textContent = (allVisible.length > 0 && allVisible.length === allChecked.length)
                ? 'Deselect all' : 'Select all';

            /* Hide error once selection is made */
            if (checked.length > 0) valError.classList.remove('show');
        }

        /* ── Select / Deselect all (visible rows only) ─── */
        selectAllBtn.addEventListener('click', function () {
            const visibleRows = document.querySelectorAll('.svc-row:not([style*="display: none"])');
            const anyUnchecked = [...visibleRows].some(r => !r.classList.contains('selected'));

            visibleRows.forEach(function (row) {
                const cb = row.querySelector('input[type="checkbox"]');
                cb.checked = anyUnchecked;
                row.classList.toggle('selected', anyUnchecked);
            });
            updateSummary();
        });

        /* ── Live filter ────────────────────────────────── */
        filterInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach(function (row) {
                const match = row.dataset.name.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            noResults.style.display = visible === 0 ? 'block' : 'none';
        });

        /* ── Form validation ────────────────────────────── */
        document.getElementById('assignForm').addEventListener('submit', function (e) {
            const checked = document.querySelectorAll('input[name="sids[]"]:checked');
            if (checked.length === 0) {
                e.preventDefault();
                valError.classList.add('show');
                /* Scroll summary into view on mobile */
                valError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    </script>

</body>
</html>
<?php  ?>
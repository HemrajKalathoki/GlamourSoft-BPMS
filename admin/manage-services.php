<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    /* ── DELETE SERVICE ─────────────────────────────── */
    if (isset($_GET['delid'])) {
        $sid = $_GET['delid'];
        mysqli_query($con, "DELETE FROM tblservices WHERE ID='$sid'");
        echo "<script>alert('Service deleted successfully.');</script>";
        echo "<script>window.location.href='manage-services.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Manage Services</title>

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

        /* ── Layout — mirrors sidebar.php .page-container logic ─
           sidebar.php JS toggles .collapsed on #sidebar-wrapper
           and .full-width on .dashboard-wrapper                  */
        .dashboard-wrapper {
            margin-left: 280px;        /* clears fixed sidebar        */
            margin-top: 78px;          /* clears fixed .admin-navbar  */
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

        /* Add Service button */
        .btn-add {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff; border: none; padding: 11px 22px;
            border-radius: 12px; font-size: .88rem; font-weight: 600;
            font-family: 'Poppins', sans-serif; text-decoration: none;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(233,30,99,.28); white-space: nowrap;
        }
        .btn-add:hover {
            opacity: .92; transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(233,30,99,.35); color: #fff;
        }
        .btn-add:active { transform: translateY(0); }

        /* ── Stats strip ─────────────────────────────────── */
        .stats-strip {
            display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;
        }
        .stat-chip {
            display: flex; align-items: center; gap: 10px;
            background: #fff; border-radius: 14px; padding: 14px 20px;
            box-shadow: var(--card-shadow); border: 1px solid #f3e0ea;
            flex: 1; min-width: 140px;
        }
        .stat-chip-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--pk-light); color: var(--primary-color);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .stat-chip-val {
            font-size: 1.3rem; font-weight: 700; color: #1a1a2e; line-height: 1;
        }
        .stat-chip-label { font-size: .72rem; color: #888; margin-top: 2px; }

        /* ── Table Card ──────────────────────────────────── */
        .table-card {
            background: #fff; border-radius: var(--radius);
            box-shadow: var(--card-shadow); border: 1px solid #f3e0ea; overflow: hidden;
        }
        .table-card-header {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
            padding: 18px 24px; background: var(--pk-soft); border-bottom: 1px solid #f3e0ea;
        }
        .table-card-header h6 {
            font-size: .93rem; font-weight: 700; color: #1a1a2e; margin: 0;
            display: flex; align-items: center; gap: 8px;
        }
        .total-badge {
            background: var(--pk-light); color: var(--primary-color);
            font-size: .71rem; font-weight: 700; padding: 4px 14px; border-radius: 20px;
        }

        /* Search filter inside card */
        .card-filter {
            padding: 14px 24px; border-bottom: 1px solid #f3e0ea; background: #fff;
        }
        .filter-group { position: relative; max-width: 320px; }
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

        /* ── Table ───────────────────────────────────────── */
        .svc-table { margin: 0; }
        .svc-table thead th {
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff; font-size: .69rem; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            padding: 13px 18px; border: none; white-space: nowrap;
        }
        .svc-table tbody td {
            padding: 13px 18px; vertical-align: middle;
            font-size: .85rem; color: #333; border-color: #fdf0f4;
        }
        .svc-table tbody tr { transition: background .15s; }
        .svc-table tbody tr:hover { background: var(--pk-soft); }
        .svc-table tbody tr:last-child td { border-bottom: none; }

        /* Row number bubble */
        .row-num {
            width: 30px; height: 30px; background: var(--pk-light);
            color: var(--primary-color); border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .73rem; font-weight: 700;
        }

        /* Service name cell */
        .svc-cell { display: flex; align-items: center; gap: 10px; }
        .svc-icon-wrap {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff; font-size: .95rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .svc-name { font-weight: 600; font-size: .86rem; color: #1a1a2e; }

        /* Price badge */
        .price-badge {
            display: inline-flex; align-items: center; gap: 4px;
            background: #e8f5e9; color: #2e7d32;
            font-size: .78rem; font-weight: 700;
            padding: 4px 12px; border-radius: 20px;
        }

        /* Date cell */
        .dt-cell {
            display: flex; align-items: center; gap: 5px;
            font-size: .82rem; color: #666;
        }
        .dt-cell .bi { color: var(--primary-color); }

        /* Action buttons */
        .action-group { display: flex; align-items: center; gap: 8px; }

        .btn-edit {
            display: inline-flex; align-items: center; gap: 5px;
            background: #e3f2fd; color: #1565c0;
            border: none; padding: 7px 14px; border-radius: 10px;
            font-size: .77rem; font-weight: 600; font-family: 'Poppins', sans-serif;
            text-decoration: none; transition: background .2s, transform .15s;
        }
        .btn-edit:hover {
            background: #bbdefb; color: #0d47a1; transform: translateY(-1px);
        }

        .btn-delete {
            display: inline-flex; align-items: center; gap: 5px;
            background: #fce4ec; color: #c62828;
            border: none; padding: 7px 14px; border-radius: 10px;
            font-size: .77rem; font-weight: 600; font-family: 'Poppins', sans-serif;
            text-decoration: none; transition: background .2s, transform .15s;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #ffcdd2; color: #b71c1c; transform: translateY(-1px);
        }

        /* ── Empty State ─────────────────────────────────── */
        .empty-wrap { text-align: center; padding: 60px 24px; }
        .empty-ring {
            width: 76px; height: 76px; background: var(--pk-light); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.1rem; color: var(--primary-color); margin: 0 auto 18px;
        }
        .empty-wrap h5 { font-weight: 700; color: #1a1a2e; margin-bottom: 6px; font-size: 1rem; }
        .empty-wrap p  { font-size: .83rem; color: #888; max-width: 320px; margin: 0 auto; }
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
                    <i class="bi bi-scissors"></i>
                </div>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Services</li>
                        </ol>
                    </nav>
                    <h4>Manage Services</h4>
                    <p>View, edit and delete salon services</p>
                </div>
            </div>
            <!-- Quick-add button -->
            <a href="add-services.php" class="btn-add">
                <i class="bi bi-plus-circle-fill"></i> Add New Service
            </a>
        </div>

        <?php
        /* ── Fetch all services once for stats + table ── */
        $all = mysqli_query($con, "SELECT * FROM tblservices ORDER BY ID DESC");
        $total_services = mysqli_num_rows($all);

        /* Total revenue potential (sum of Cost) */
        $cost_res = mysqli_query($con, "SELECT SUM(Cost) AS total FROM tblservices");
        $cost_row = mysqli_fetch_assoc($cost_res);
        $total_cost = $cost_row['total'] ?? 0;

        /* Reset pointer */
        mysqli_data_seek($all, 0);
        ?>

        <!-- ── Stats Strip ───────────────────────────── -->
        <div class="stats-strip">
            <div class="stat-chip">
                <div class="stat-chip-icon"><i class="bi bi-scissors"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $total_services; ?></div>
                    <div class="stat-chip-label">Total Services</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon">Rs.</div>
                <div>
                    <div class="stat-chip-val">Rs. <?php echo number_format($total_cost, 0); ?></div>
                    <div class="stat-chip-label">Combined Price Value</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="stat-chip-val">
                        Rs. <?php echo $total_services > 0 ? number_format($total_cost / $total_services, 0) : 0; ?>
                    </div>
                    <div class="stat-chip-label">Average Service Price</div>
                </div>
            </div>
        </div>

        <!-- ── Table Card ────────────────────────────── -->
        <div class="table-card">

            <!-- Card header -->
            <div class="table-card-header">
                <h6>
                    <i class="bi bi-list-ul" style="color:var(--primary-color)"></i>
                    All Services
                </h6>
                <span class="total-badge"><?php echo $total_services; ?> service<?php echo $total_services != 1 ? 's' : ''; ?></span>
            </div>

            <!-- Live filter -->
            <div class="card-filter">
                <div class="filter-group">
                    <i class="bi bi-funnel f-icon"></i>
                    <input
                        type="text"
                        id="filterInput"
                        placeholder="Filter services by name…"
                        autocomplete="off"
                    >
                </div>
            </div>

            <?php if ($total_services > 0) : ?>
                <div class="table-responsive">
                    <table class="table svc-table" id="servicesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php $cnt = 1;
                            while ($row = mysqli_fetch_array($all)) : ?>
                            <tr>
                                <!-- Row number -->
                                <td>
                                    <span class="row-num"><?php echo $cnt; ?></span>
                                </td>

                                <!-- Service name with scissors icon -->
                                <td>
                                    <div class="svc-cell">
                                        <div class="svc-icon-wrap">
                                            <i class="bi bi-scissors"></i>
                                        </div>
                                        <span class="svc-name">
                                            <?php echo htmlspecialchars($row['ServiceName']); ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Price -->
                                <td>
                                    <span class="price-badge">
                                        Rs. 
                                        <?php echo htmlspecialchars($row['Cost']); ?>
                                    </span>
                                </td>

                                <!-- Created date -->
                                <td>
                                    <div class="dt-cell">
                                        <i class="bi bi-calendar3"></i>
                                        <?php echo htmlspecialchars($row['CreationDate']); ?>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="action-group">
                                        <a href="edit-services.php?editid=<?php echo $row['ID']; ?>"
                                           class="btn-edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <a href="manage-services.php?delid=<?php echo $row['ID']; ?>"
                                           class="btn-delete"
                                           onclick="return confirm('Delete \'<?php echo htmlspecialchars($row['ServiceName'], ENT_QUOTES); ?>\'? This cannot be undone.')">
                                            <i class="bi bi-trash3-fill"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $cnt++; endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else : ?>
                <div class="empty-wrap">
                    <div class="empty-ring">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h5>No Services Yet</h5>
                    <p>You haven't added any services. Click <strong>Add New Service</strong> to get started.</p>
                </div>
            <?php endif; ?>

        </div><!-- /table-card -->

    </div><!-- /dashboard-wrapper -->

    <!-- Bootstrap 5 JS — safety fallback, header.php already loads it -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        /* ── Live client-side filter ────────────────────── */
        document.getElementById('filterInput').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#tableBody tr').forEach(function (row) {
                /* match against service name cell (2nd td) */
                const name = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
                row.style.display = name.includes(q) ? '' : 'none';
            });
        });
    </script>

</body>
</html>
<?php  ?>
<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── DELETE handler — LOGIC UNCHANGED ── */
    if ($_GET['delid']) {
        $sid = $_GET['delid'];
        mysqli_query($con, "delete from tblinvoice where BillingId ='$sid'");
        echo "<script>alert('Invoice Deleted Successfully!');</script>";
        echo "<script>window.location.href='invoices.php'</script>";
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Invoices</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #fdf6f9;
            color: #3d2233;
        }

        .dashboard-wrapper {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
        }

        .page-content { padding: 32px 28px; }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #e91e63;
            margin-bottom: 2px;
        }

        .page-header-left p {
            font-size: 0.85rem;
            color: #9e6b7e;
            margin: 0;
        }

        .breadcrumb-nav { font-size: 0.8rem; color: #9e6b7e; }
        .breadcrumb-nav a { color: #e91e63; text-decoration: none; font-weight: 500; }
        .breadcrumb-nav a:hover { text-decoration: underline; }

        .stat-strip {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .stat-pill {
            background: #fff;
            border: 1px solid #f3e0ea;
            border-radius: 50px;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #3d2233;
            box-shadow: 0 2px 12px rgba(233,30,99,.06);
        }

        .stat-pill span.count { color: #e91e63; font-size: 1rem; }

        .card-bpms {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f3e0ea;
            box-shadow: 0 2px 20px rgba(233,30,99,.07);
            overflow: hidden;
        }

        .card-bpms-header {
            background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-bpms-header h5 {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-link {
            background: rgba(255,255,255,.18);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background .2s;
        }

        .header-link:hover { background: rgba(255,255,255,.30); color: #fff; }

        .filter-bar {
            padding: 16px 20px;
            background: #faf5ff;
            border-bottom: 1px solid #ede9fe;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #7c3aed;
            font-size: 0.9rem;
        }

        .search-box input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1.5px solid #ddd6fe;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            color: #3d2233;
            background: #fff;
            transition: border-color .2s;
            outline: none;
        }

        .search-box input:focus { border-color: #7c3aed; }

        .filter-bar select {
            padding: 9px 14px;
            border: 1.5px solid #ddd6fe;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            color: #3d2233;
            background: #fff;
            outline: none;
            cursor: pointer;
            transition: border-color .2s;
        }

        .filter-bar select:focus { border-color: #7c3aed; }

        table.bpms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        table.bpms-table thead tr { background: #f5f3ff; }

        table.bpms-table thead th {
            padding: 14px 16px;
            font-weight: 600;
            font-size: 0.78rem;
            color: #5b21b6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }

        table.bpms-table tbody tr {
            border-bottom: 1px solid #faf5ff;
            transition: background .15s;
        }

        table.bpms-table tbody tr:last-child { border-bottom: none; }
        table.bpms-table tbody tr:hover { background: #faf5ff; }

        table.bpms-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #3d2233;
            border: none;
        }

        .sno-cell { width: 40px; color: #9e6b7e; font-size: 0.8rem; font-weight: 600; }

        .invoice-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f5f3ff;
            color: #6d28d9;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            border: 1px solid #ddd6fe;
            letter-spacing: 0.3px;
        }

        .customer-cell { display: flex; align-items: center; gap: 10px; }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.76rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .customer-name { font-weight: 600; font-size: 0.88rem; color: #3d2233; }

        .date-cell {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fdf6f9;
            color: #5c3a4e;
            font-size: 0.83rem;
            padding: 5px 12px;
            border-radius: 8px;
            border: 1px solid #f3e0ea;
            white-space: nowrap;
        }

        .date-cell i { color: #e91e63; }

        .action-cell { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 16px;
            background: linear-gradient(135deg, #6d28d9, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .2s, transform .15s;
            cursor: pointer;
        }

        .btn-view:hover { opacity: .88; color: #fff; transform: translateY(-1px); }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 16px;
            background: #fff0f3;
            color: #e91e63;
            border: 1.5px solid #f3c6d9;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #e91e63;
            color: #fff;
            border-color: #e91e63;
            transform: translateY(-1px);
        }

        .empty-state { text-align: center; padding: 60px 20px; }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f5f3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #6d28d9;
        }

        .empty-state h5 { font-weight: 600; color: #3d2233; margin-bottom: 8px; }
        .empty-state p  { color: #9e6b7e; font-size: 0.88rem; margin: 0; }

        @media (max-width: 991px) { .dashboard-wrapper { margin-left: 0; } }
        @media (max-width: 576px)  { .page-content { padding: 20px 14px; } }
    </style>
</head>
<body>

    <?php include_once('./includes/sidebar.php'); ?>
    <?php include_once('./includes/header.php'); ?>

    <div class="dashboard-wrapper">
        <div class="page-content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h2><i class="bi bi-receipt me-2"></i>Invoice List</h2>
                    <p>All generated customer invoices and billing records</p>
                </div>
                <nav class="breadcrumb-nav">
                    <a href="dashboard.php"><i class="bi bi-house-door me-1"></i>Dashboard</a>
                    <span class="mx-2">/</span>
                    <span>Invoices</span>
                </nav>
            </div>

            <?php
            /* Safe count query — no extra columns needed */
            $count_result   = mysqli_query($con, "SELECT COUNT(DISTINCT BillingId) as total FROM tblinvoice");
            $count_row      = mysqli_fetch_assoc($count_result);
            $total_invoices = $count_row['total'];
            ?>

            <!-- Stat Strip -->
            <div class="stat-strip">
                <div class="stat-pill">
                    <i class="bi bi-file-earmark-text-fill" style="color:#6d28d9;"></i>
                    Total Invoices:
                    <span class="count"><?php echo $total_invoices; ?></span>
                </div>
                <div class="stat-pill">
                    <i class="bi bi-sort-down" style="color:#e91e63;"></i>
                    Sorted: Latest First
                </div>
            </div>

            <!-- Main Card -->
            <div class="card-bpms">

                <div class="card-bpms-header">
                    <h5>
                        <i class="bi bi-file-earmark-ruled-fill"></i>
                        All Invoices
                    </h5>
                    <a href="dashboard.php" class="header-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </div>

                <div class="filter-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by invoice ID, customer name, or date…">
                    </div>
                    <select id="sortSelect">
                        <option value="">Sort by Date</option>
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="bpms-table" id="invoiceTable">
                        <thead>
                            <tr>
                                <th class="sno-cell">#</th>
                                <th><i class="bi bi-file-earmark-text me-1"></i>Invoice ID</th>
                                <th><i class="bi bi-person me-1"></i>Customer Name</th>
                                <th><i class="bi bi-calendar3 me-1"></i>Invoice Date</th>
                                <th><i class="bi bi-gear me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        /* ── ORIGINAL QUERY — NOT CHANGED AT ALL ── */
                        $ret=mysqli_query($con,"select distinct tbluser.FirstName,tbluser.LastName,tblinvoice.BillingId,date(tblinvoice.PostingDate) as invoicedate from  tbluser   
                            join tblinvoice on tbluser.ID=tblinvoice.Userid  order by tblinvoice.ID desc");
                        $cnt=1;
                        $has_rows = false;

                        while ($row=mysqli_fetch_array($ret)) :
                            $has_rows = true;
                            $initials = strtoupper(substr($row['FirstName'],0,1).substr($row['LastName'],0,1));
                        ?>
                            <tr>
                                <td class="sno-cell"><?php echo $cnt; ?></td>

                                <td>
                                    <span class="invoice-badge">
                                        <i class="bi bi-receipt"></i>
                                        <?php echo $row['BillingId']; ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="customer-cell">
                                        <div class="avatar-circle"><?php echo $initials; ?></div>
                                        <div class="customer-name">
                                            <?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="date-cell">
                                        <i class="bi bi-calendar-event-fill"></i>
                                        <?php echo $row['invoicedate']; ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-cell">
                                        <a href="view-invoice.php?invoiceid=<?php echo $row['BillingId']; ?>" class="btn-view">
                                            <i class="bi bi-eye-fill"></i> View
                                        </a>
                                        <a href="invoices.php?delid=<?php echo $row['BillingId']; ?>" class="btn-delete"
                                           onclick="return confirm('Are you sure you want to delete?')">
                                            <i class="bi bi-trash3-fill"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $cnt++;
                        endwhile;

                        if (!$has_rows) :
                        ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-receipt-cutoff"></i>
                                        </div>
                                        <h5>No Invoices Found</h5>
                                        <p>No invoices have been generated yet. They will appear here once billing is done.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div><!-- /.card-bpms -->

        </div><!-- /.page-content -->

        <?php include_once('./includes/footer.php'); ?>
    </div><!-- /.dashboard-wrapper -->

    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
            });
        });

        document.getElementById('sortSelect').addEventListener('change', function () {
            const order = this.value;
            if (!order) return;
            const tbody = document.querySelector('#invoiceTable tbody');
            const rows  = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
                const dateA = new Date(a.cells[3]?.innerText?.trim() || '');
                const dateB = new Date(b.cells[3]?.innerText?.trim() || '');
                return order === 'asc' ? dateA - dateB : dateB - dateA;
            });
            rows.forEach(r => tbody.appendChild(r));
        });
    </script>

</body>
</html>
<?php } ?>
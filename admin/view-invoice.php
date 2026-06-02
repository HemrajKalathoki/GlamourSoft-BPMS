<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BPMS || View Invoice</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

  <style>
    /* ── CSS variables ── */
    :root {
      --primary:       #e91e8c;
      --primary-light: #fce4f3;
      --primary-dark:  #c0156f;
      --card-shadow:   0 4px 24px rgba(233,30,140,0.08);
      --radius:        14px;
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #faf5f9;
      color: #2d1a27;
      min-height: 100vh;
    }

    /* ── Screen page wrapper ── */
    .page-wrapper {
      max-width: 860px;
      margin: 0 auto;
      padding: 32px 20px 60px;
    }

    /* ── Back button ── */
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: var(--primary);
      font-weight: 600;
      font-size: 0.88rem;
      text-decoration: none;
      border: 1.5px solid var(--primary-light);
      border-radius: 8px;
      padding: 7px 16px;
      background: #fff;
      transition: background .18s, color .18s;
      margin-bottom: 24px;
    }
    .btn-back:hover { background: var(--primary); color: #fff; }

    /* ── Invoice card ── */
    .invoice-card {
      background: #fff;
      border-radius: var(--radius);
      box-shadow: var(--card-shadow);
      overflow: hidden;
    }

    /* Header stripe */
    .invoice-header {
      background: linear-gradient(135deg, #e91e8c 0%, #c0156f 100%);
      color: #fff;
      padding: 28px 36px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }
    .brand { display: flex; align-items: center; gap: 14px; }
    .brand-icon {
      width: 50px; height: 50px;
      background: rgba(255,255,255,0.18);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.55rem;
    }
    .brand-name  { font-size: 1.3rem; font-weight: 700; letter-spacing: -.3px; }
    .brand-sub   { font-size: 0.78rem; opacity: .75; margin-top: 2px; }
    .inv-meta    { text-align: right; }
    .inv-meta .lbl { font-size: 0.72rem; opacity: .75; text-transform: uppercase; letter-spacing: .06em; }
    .inv-meta .num { font-size: 1.6rem; font-weight: 700; letter-spacing: -.5px; margin-top: 2px; }

    /* Body */
    .invoice-body { padding: 28px 36px 32px; }

    /* Section title */
    .sec-title {
      font-size: 0.7rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .09em;
      color: var(--primary);
      display: flex; align-items: center; gap: 8px;
      margin-bottom: 14px;
    }
    .sec-title::after { content:''; flex:1; height:1px; background: var(--primary-light); }

    /* Customer grid */
    .detail-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 14px 20px;
      background: #fdf2fa;
      border-radius: 10px;
      padding: 20px 22px;
      margin-bottom: 28px;
    }
    .di-label {
      font-size: 0.7rem; font-weight: 600;
      color: #a87299; text-transform: uppercase;
      letter-spacing: .05em; margin-bottom: 3px;
    }
    .di-value { font-size: 0.92rem; font-weight: 600; color: #2d1a27; }

    /* Services table */
    .svc-table { width: 100%; border-collapse: collapse; }
    .svc-table thead tr  { background: #fce4f3; }
    .svc-table thead th  {
      padding: 12px 16px;
      font-size: 0.72rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .06em;
      color: #c0156f; border: none;
    }
    .svc-table thead th:last-child { text-align: right; }
    .svc-table tbody tr  { border-bottom: 1px solid #f5e8f2; }
    .svc-table tbody td  { padding: 13px 16px; font-size: 0.91rem; border: none; }
    .svc-table tbody td:first-child { font-size: 0.78rem; color: #a87299; font-weight: 700; width: 48px; }
    .svc-table tbody td:last-child  { text-align: right; font-weight: 600; }

    /* Total row */
    .total-row { background: #fce4f3; }
    .total-row td { padding: 15px 16px !important; font-weight: 700 !important; border: none !important; }
    .total-lbl  { font-size: 0.82rem; text-transform: uppercase; letter-spacing: .06em; color: #c0156f; }
    .total-amt  { color: #e91e8c !important; font-size: 1.15rem !important; text-align: right; }

    /* Thank-you footer strip */
    .inv-footer {
      background: #fdf2fa;
      border-top: 1px solid #f5e8f2;
      padding: 18px 36px;
      display: flex; justify-content: space-between; align-items: center;
      font-size: 0.78rem; color: #a87299;
      flex-wrap: wrap; gap: 8px;
    }
    .inv-footer strong { color: #e91e8c; }

    /* Actions bar (screen only) */
    .invoice-actions {
      padding: 20px 36px 28px;
      border-top: 1px solid #f5e8f2;
      display: flex; justify-content: center;
      gap: 12px; flex-wrap: wrap;
    }
    .btn-print {
      display: inline-flex; align-items: center; gap: 9px;
      background: #e91e8c; color: #fff;
      font-weight: 600; font-size: 0.92rem;
      border: none; border-radius: 10px; padding: 11px 28px;
      cursor: pointer;
      transition: background .18s, transform .12s, box-shadow .18s;
      box-shadow: 0 4px 16px rgba(233,30,140,.28);
    }
    .btn-print:hover { background: #c0156f; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(233,30,140,.34); }
    .btn-print:active { transform: translateY(0); }

    /* ── SCREEN-ONLY hide print ── */
    @media screen { .print-only { display: none !important; } }
  </style>
</head>
<body>

  <?php include_once('includes/sidebar.php'); ?>
  <?php include_once('includes/header.php'); ?>

  <div id="page-wrapper">
    <div class="page-wrapper">

      <!-- Back link (screen only) -->
      <a href="invoices.php" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Invoices
      </a>

      <!-- ══ INVOICE CARD ══ -->
      <div class="invoice-card" id="invoice-print-area">

        <!-- Header stripe -->
        <div class="invoice-header">
          <div class="brand">
            <div class="brand-icon"><i class="bi bi-scissors"></i></div>
            <div>
              <div class="brand-name">Beauty Parlor</div>
              <div class="brand-sub">Management System</div>
            </div>
          </div>
          <div class="inv-meta">
            <div class="lbl">Invoice No.</div>
            <div class="num">#<?php echo intval($_GET['invoiceid']); ?></div>
          </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">

          <?php
          /* ── Customer details query (logic unchanged) ── */
          $invid = intval($_GET['invoiceid']);
          $ret = mysqli_query($con,
            "SELECT DISTINCT date(tblinvoice.PostingDate) AS invoicedate,
                    tbluser.FirstName, tbluser.LastName, tbluser.Email,
                    tbluser.MobileNumber, tbluser.RegDate
             FROM   tblinvoice
             JOIN   tbluser ON tbluser.ID = tblinvoice.Userid
             WHERE  tblinvoice.BillingId='$invid'"
          );
          while ($row = mysqli_fetch_array($ret)) :
          ?>

          <!-- Customer section -->
          <div class="sec-title"><i class="bi bi-person-vcard"></i> Customer Details</div>
          <div class="detail-grid">
            <div>
              <div class="di-label">Full Name</div>
              <div class="di-value"><?php echo htmlspecialchars($row['FirstName'].' '.$row['LastName']); ?></div>
            </div>
            <div>
              <div class="di-label">Contact No.</div>
              <div class="di-value"><?php echo htmlspecialchars($row['MobileNumber']); ?></div>
            </div>
            <div>
              <div class="di-label">Email</div>
              <div class="di-value"><?php echo htmlspecialchars($row['Email']); ?></div>
            </div>
            <div>
              <div class="di-label">Registration Date</div>
              <div class="di-value"><?php echo htmlspecialchars($row['RegDate']); ?></div>
            </div>
            <div>
              <div class="di-label">Invoice Date</div>
              <div class="di-value"><?php echo htmlspecialchars($row['invoicedate']); ?></div>
            </div>
          </div>

          <?php endwhile; ?>

          <!-- Services section -->
          <div class="sec-title"><i class="bi bi-stars"></i> Services</div>

          <table class="svc-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Service Name</th>
                <th style="text-align:right">Cost</th>
              </tr>
            </thead>
            <tbody>
              <?php
              /* ── Services query (logic unchanged) ── */
              $ret2 = mysqli_query($con,
                "SELECT tblservices.ServiceName, tblservices.Cost
                 FROM   tblinvoice
                 JOIN   tblservices ON tblservices.ID = tblinvoice.ServiceId
                 WHERE  tblinvoice.BillingId='$invid'"
              );
              $cnt = 1; $gtotal = 0;
              while ($row2 = mysqli_fetch_array($ret2)) :
                $subtotal = $row2['Cost'];
                $gtotal  += $subtotal;
              ?>
              <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php echo htmlspecialchars($row2['ServiceName']); ?></td>
                <td style="text-align:right">Rs. <?php echo number_format($subtotal, 2); ?></td>
              </tr>
              <?php $cnt++; endwhile; ?>
            </tbody>
            <tfoot>
              <tr class="total-row">
                <td colspan="2" class="total-lbl" style="text-align:center">Grand Total</td>
                <td class="total-amt">Rs. <?php echo number_format($gtotal, 2); ?></td>
              </tr>
            </tfoot>
          </table>

        </div><!-- /.invoice-body -->

        <!-- Thank-you footer strip -->
        <div class="inv-footer">
          <span>Thank you for choosing <strong>Beauty Parlor</strong>!</span>
          <span>This is a computer-generated invoice.</span>
        </div>

        <!-- Actions (screen only) -->
        <div class="invoice-actions">
          <button class="btn-print" onclick="CallPrint()">
            <i class="bi bi-file-earmark-pdf-fill"></i> Download / Print PDF
          </button>
        </div>

      </div><!-- /.invoice-card -->
    </div>
  </div>

  <?php include_once('includes/footer.php'); ?>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

  <script>
  /**
   * CallPrint()
   * Opens a self-contained print/PDF window with ALL styles inlined.
   * The browser's "Save as PDF" option will produce a fully-styled PDF.
   */
  function CallPrint() {

    /* ── Grab only the invoice card, not sidebar/header ── */
    var content = document.getElementById('invoice-print-area').innerHTML;
    var invoiceNo = <?php echo intval($_GET['invoiceid']); ?>;

    /* ── Self-contained CSS injected into the print window ── */
    var css = `
      @page {
        size: A4;
        margin: 15mm 14mm 15mm 14mm;
      }
      * { box-sizing: border-box; margin: 0; padding: 0; }

      body {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #2d1a27;
        background: #fff;
        font-size: 13px;
      }

      /* ── Invoice wrapper ── */
      .invoice-card {
        max-width: 720px;
        margin: 0 auto;
        border: 1px solid #f0d6ea;
        border-radius: 10px;
        overflow: hidden;
      }

      /* ── Header stripe ── */
      .invoice-header {
        background: #e91e8c !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color: #fff;
        padding: 22px 30px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .brand { display: flex; align-items: center; gap: 12px; }
      .brand-icon {
        width: 44px; height: 44px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; font-weight: 700; color: #fff;
      }
      .brand-name { font-size: 1.2rem; font-weight: 700; }
      .brand-sub  { font-size: 0.75rem; opacity: .8; margin-top: 1px; }
      .inv-meta   { text-align: right; }
      .inv-meta .lbl { font-size: 0.7rem; opacity: .75; text-transform: uppercase; letter-spacing: .06em; }
      .inv-meta .num { font-size: 1.5rem; font-weight: 700; margin-top: 2px; }

      /* ── Body ── */
      .invoice-body { padding: 22px 30px 26px; }

      /* ── Section title ── */
      .sec-title {
        font-size: 0.65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .09em;
        color: #e91e8c;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 12px;
      }
      .sec-title::after { content:''; flex:1; height:1px; background: #fce4f3; }

      /* ── Customer grid ── */
      .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px 18px;
        background: #fdf2fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border-radius: 8px;
        padding: 16px 18px;
        margin-bottom: 24px;
      }
      .di-label {
        font-size: 0.65rem; font-weight: 600;
        color: #a87299; text-transform: uppercase;
        letter-spacing: .05em; margin-bottom: 2px;
      }
      .di-value { font-size: 0.88rem; font-weight: 600; color: #2d1a27; }

      /* ── Services table ── */
      .svc-table { width: 100%; border-collapse: collapse; }
      .svc-table thead tr {
        background: #fce4f3 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .svc-table thead th {
        padding: 10px 14px;
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em;
        color: #c0156f; border: none;
      }
      .svc-table tbody tr { border-bottom: 1px solid #f5e8f2; }
      .svc-table tbody td { padding: 11px 14px; font-size: 0.88rem; border: none; }
      .svc-table tbody td:first-child { font-size: 0.75rem; color: #a87299; font-weight: 700; width: 40px; }
      .svc-table tbody td:last-child  { text-align: right; font-weight: 600; }

      /* ── Total row ── */
      .total-row {
        background: #fce4f3 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .total-row td { padding: 13px 14px !important; font-weight: 700 !important; border: none !important; }
      .total-lbl { font-size: 0.78rem; text-transform: uppercase; letter-spacing: .06em; color: #c0156f; text-align: center; }
      .total-amt { color: #e91e8c !important; font-size: 1.1rem !important; text-align: right; }

      /* ── Thank-you strip ── */
      .inv-footer {
        background: #fdf2fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border-top: 1px solid #f5e8f2;
        padding: 14px 30px;
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.75rem; color: #a87299;
      }
      .inv-footer strong { color: #e91e8c; }

      /* ── Hide action buttons in PDF ── */
      .invoice-actions, .btn-back, .btn-print { display: none !important; }
    `;

    /* ── Build the print window HTML ── */
    var html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Invoice #${invoiceNo}</title>
  <style>${css}</style>
</head>
<body>
  <div class="invoice-card">${content}</div>
</body>
</html>`;

    /* ── Open, write, and trigger print/save ── */
    var w = window.open('', '_blank', 'width=900,height=700');
    w.document.open();
    w.document.write(html);
    w.document.close();
    /* Small delay lets fonts/layout settle before the print dialog fires */
    w.onload = function() {
      setTimeout(function() {
        w.focus();
        w.print();
      }, 400);
    };
  }
  </script>

</body>
</html>
<?php } ?>
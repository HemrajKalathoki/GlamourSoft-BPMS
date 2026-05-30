<div class="sidebar" id="sidebar-wrapper">

    <style>
        :root {
            --primary-color: #e91e63;
            --secondary-color: #ff4f81;
            --dark-color: #1f1f1f;
            --light-color: #ffffff;
        }

        /* SIDEBAR */
        .sidebar {
          width: 280px;
          height: calc(100vh - 78px);
          position: fixed;
          top: 78px;
          left: 0;
          background: #fff;
          box-shadow: 4px 0 20px rgba(0,0,0,0.05);
          overflow-y: auto;
          transition: all 0.3s ease;
          z-index: 1040;
       }
       .sidebar.collapsed {
          left: -280px;
      }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;
        }

        /* MENU */
        .sidebar-menu {
            padding: 20px 15px;
        }

        .menu-title {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin: 20px 15px 10px;
            letter-spacing: 1px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 14px;
            color: #444;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .sidebar-link:hover {
            background: #fce4ec;
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: white;
            box-shadow: 0 5px 15px rgba(233,30,99,0.25);
        }

        .sidebar-link i {
            font-size: 18px;
            margin-right: 12px;
        }

        .sidebar-link .menu-left {
            display: flex;
            align-items: center;
        }

        /* SUBMENU */
        .submenu {
            padding-left: 15px;
            display: none;
        }

        .submenu a {
            display: block;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 10px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .submenu a:hover {
            background: #fce4ec;
            color: var(--primary-color);
        }

        .submenu.show {
            display: block;
        }

        /* CONTENT FIX */
        .page-container {
            margin-left: 280px;
            transition: 0.3s;
        }

        /* COLLAPSED */
        .sidebar.collapsed {
            left: -280px;
        }

        .page-container.full {
            margin-left: 0;
        }

        /* MOBILE */
        @media (max-width: 991px) {

            .sidebar {
                left: -280px;
            }

            .sidebar.show {
                left: 0;
            }

            .page-container {
                margin-left: 0;
            }
        }
    </style>

    <div class="sidebar-menu">

        <!-- DASHBOARD -->
        <a href="dashboard.php" class="sidebar-link active">
            <div class="menu-left">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </div>
        </a>

        <!-- SERVICES -->
        <div class="menu-title">Services</div>

        <a href="javascript:void(0)" class="sidebar-link toggle-menu">
            <div class="menu-left">
                <i class="bi bi-scissors"></i>
                Services
            </div>

            <i class="bi bi-chevron-down"></i>
        </a>

        <div class="submenu">
            <a href="add-services.php">
                Add Services
            </a>

            <a href="manage-services.php">
                Manage Services
            </a>
        </div>

        <!-- PAGES -->
        <div class="menu-title">Pages</div>

        <a href="javascript:void(0)" class="sidebar-link toggle-menu">
            <div class="menu-left">
                <i class="bi bi-file-earmark-text"></i>
                Pages
            </div>

            <i class="bi bi-chevron-down"></i>
        </a>

        <div class="submenu">
            <a href="about-us.php">About Us</a>
            <a href="contact-us.php">Contact Us</a>
        </div>

        <!-- APPOINTMENTS -->
        <div class="menu-title">Appointments</div>

        <a href="javascript:void(0)" class="sidebar-link toggle-menu">
            <div class="menu-left">
                <i class="bi bi-calendar-check"></i>
                Appointments
            </div>

            <i class="bi bi-chevron-down"></i>
        </a>

        <div class="submenu">
            <a href="all-appointment.php">All Appointment</a>
            <a href="new-appointment.php">New Appointment</a>
            <a href="accepted-appointment.php">Accepted Appointment</a>
            <a href="rejected-appointment.php">Rejected Appointment</a>
        </div>

        <!-- ENQUIRY -->
        <div class="menu-title">Enquiry</div>

        <a href="javascript:void(0)" class="sidebar-link toggle-menu">
            <div class="menu-left">
                <i class="bi bi-chat-left-text"></i>
                Enquiry
            </div>

            <i class="bi bi-chevron-down"></i>
        </a>

        <div class="submenu">
            <a href="readenq.php">Read Enquiry</a>
            <a href="unreadenq.php">Unread Enquiry</a>
        </div>

        <!-- CUSTOMERS -->
        <div class="menu-title">Customers</div>

        <a href="customer-list.php" class="sidebar-link">
            <div class="menu-left">
                <i class="bi bi-people"></i>
                Customer List
            </div>
        </a>

        <!-- REPORTS -->
        <div class="menu-title">Reports</div>

        <a href="javascript:void(0)" class="sidebar-link toggle-menu">
            <div class="menu-left">
                <i class="bi bi-bar-chart"></i>
                Reports
            </div>

            <i class="bi bi-chevron-down"></i>
        </a>

        <div class="submenu">
            <a href="bwdates-reports-ds.php">
                Between Dates
            </a>

            <a href="sales-reports.php">
                Sales Reports
            </a>
        </div>

        <!-- INVOICES -->
        <div class="menu-title">Billing</div>

        <a href="invoices.php" class="sidebar-link">
            <div class="menu-left">
                <i class="bi bi-receipt"></i>
                Invoices
            </div>
        </a>

        <!-- SEARCH -->
        <div class="menu-title">Search</div>

        <a href="search-appointment.php" class="sidebar-link">
            <div class="menu-left">
                <i class="bi bi-search"></i>
                Search Appointment
            </div>
        </a>

        <a href="search-invoices.php" class="sidebar-link">
            <div class="menu-left">
                <i class="bi bi-search-heart"></i>
                Search Invoice
            </div>
        </a>

    </div>
</div>

<script>

    /* =========================
       SUBMENU TOGGLE
    ========================== */

    document.querySelectorAll(".toggle-menu").forEach(menu => {

        menu.addEventListener("click", function () {

            let submenu = this.nextElementSibling;

            submenu.classList.toggle("show");

        });

    });


    /* =========================
       SIDEBAR TOGGLE
    ========================== */

    const toggleBtn = document.getElementById("showLeftPush");

    const sidebar = document.getElementById("sidebar-wrapper");

    const dashboardWrapper =
        document.querySelector(".dashboard-wrapper");


    if (toggleBtn) {

        toggleBtn.addEventListener("click", () => {

            /* MOBILE */
            if (window.innerWidth < 992) {

                sidebar.classList.toggle("show");

            }

            /* DESKTOP */
            else {

                sidebar.classList.toggle("collapsed");

                if (dashboardWrapper) {

                    dashboardWrapper.classList.toggle("full-width");

                }

            }

        });

    }

</script>
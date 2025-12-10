<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../database/connect.php';

// nanti ambil data booking di sini...
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings — Supplier Portal</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sidebarAdmin.css">
    <link rel="stylesheet" href="../css/navbarAdmin.css">
    <link rel="stylesheet" href="../css/footer.css">

    <!-- PAGE CSS -->
    <link rel="stylesheet" href="../css/bookings.css">
</head>

<body class="hm">

    <!-- SIDEBAR -->
    <?php include '../components/sidebarAdmin.html'; ?>

    <!-- NAVBAR -->
    <?php include '../components/navbarAdmin.html'; ?>

    <div id="content-wrapper">
        <main class="hm-main">

            <section class="bk-hero">
                <h1 class="bk-title">Bookings</h1>

                <div class="bk-layout">
                    <!-- LEFT FILTER PANEL -->
                    <aside class="bk-filters">

                    <div class="bk-dropdown">
                        <button type="button" class="bk-select" onclick="toggleProductDropdown()">
                            All products
                        </button>

                        <div class="bk-checkbox-panel" id="productDropdown">

                            <!-- SEARCH BOX -->
                            <div class="bk-search-wrapper">
                                <input type="text" id="productSearch" class="bk-search-input" 
                                    placeholder="Search products" onkeyup="filterProducts()">
                                <img src="../images/icons/search.svg" class="bk-search-icon">
                            </div>

                            <!-- PRODUCT LIST -->
                            <div id="productList">
                                <label class="bk-product-item">
                                    <input type="checkbox"> Bali: ATV, Coffee Plantation, Temple & Monkey Forest Tour
                                </label>

                                <label class="bk-product-item">
                                    <input type="checkbox"> Bali: Lempuyang Gate of Heaven
                                </label>

                                <label class="bk-product-item">
                                    <input type="checkbox"> Bali: Lempuyang Temple, Lahangan & Tirta Gangga Private Tour
                                </label>

                                <label class="bk-product-item">
                                    <input type="checkbox"> Bali: Nature’s Bloom, Lake Reflections and Strawberry Bliss
                                </label>

                                <label class="bk-product-item">
                                    <input type="checkbox"> Bali: Private Bedugul Day Tour UNESCO Rice Terraces & Temple
                                </label>
                            </div>
                        </div>
                    </div>

                        <div class="bk-filter-block bk-date-block">
                            <label class="bk-label">Purchase Date</label>
                            <input type="date" class="bk-input start-date">
                            <input type="date" class="bk-input end-date">
                            <a class="bk-clear" style="display:none;">Clear</a>
                        </div>

                        <div class="bk-filter-block bk-date-block">
                            <label class="bk-label">Activity Date</label>
                            <input type="date" class="bk-input start-date">
                            <input type="date" class="bk-input end-date">
                            <a class="bk-clear" style="display:none;">Clear</a>
                        </div>


                    </aside>

                    <!-- BOOKINGS LIST -->
                    <section class="bk-list">

                        <!-- One booking item -->
                        <article class="bk-card">

                            <img class="bk-thumb" src="../images/no-photo.svg">

                            <div class="bk-info">
                                <h3 class="bk-item-title">Bali: Heaven Gate & TirtaGangga Tour</h3>
                                <p class="bk-item-sub">Option: Lempuyang • Private Tour</p>

                                <div class="bk-meta-row">
                                    <span>📅 Friday, Dec 5, 2025</span>
                                    <span>🧾 GYGX7NV2XVW</span>
                                    <span>👥 2 people — IDR 1,798,000</span>
                                </div>

                                <div class="bk-actions">
                                    <button class="bk-detail-btn">Show details</button>
                                    <button class="bk-cancel-btn">Cancel</button>
                                </div>

                                <!-- DETAIL DROPDOWN -->
                                <div class="bk-detail-box">
                                    <div class="bk-detail-row">Customer Name: <b>John Doe</b></div>
                                    <div class="bk-detail-row">Total People: <b>2</b></div>
                                    <div class="bk-detail-row">Amount Paid: <b>IDR 1,798,000</b></div>
                                </div>

                                <!-- CANCEL PANEL -->
                                <div class="bk-cancel-box">
                                    <h4 class="bk-cancel-title">Cancel Booking</h4>

                                    <label class="bk-label">Booking ID</label>
                                    <input class="bk-input" value="GYGX7NV2XVW" readonly>

                                    <label class="bk-label">Admin Name</label>
                                    <select class="bk-input">
                                        <option>Select admin</option>
                                        <option>Koming</option>
                                        <option>Made</option>
                                    </select>

                                    <label class="bk-label">Reason (min 15 chars)</label>
                                    <textarea class="bk-input" minlength="15"></textarea>

                                    <button class="bk-send-cancel">Send & Cancel</button>
                                </div>
                            </div>
                        </article>
                    </section>
                </div>

            </section>

        </main>
    </div>

    <!-- FOOTER -->
    <?php include '../components/footer.html'; ?>

    <script src="../js/sidebarAdmin.js"></script>
    <script src="../js/navbarAdmin.js"></script>
    <script src="../js/bookings.js"></script>

</body>
</html>

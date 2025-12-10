<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../database/connect.php';

$products = [];
$res = $conn->query("SELECT id, title FROM products ORDER BY title ASC");
while ($row = $res->fetch_assoc()) {
    $products[] = $row;
}

$admins = [];
$resA = $conn->query("SELECT id, name FROM admin ORDER BY name ASC");
while ($a = $resA->fetch_assoc()) {
    $admins[] = $a;
}

$where = [];
$params = [];
$types = "";

if (!empty($_GET['products'])) {
    $ids = $_GET['products']; // array
    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $where[] = "product_id IN ($placeholders)";
    foreach ($ids as $id) {
        $params[] = $id;
        $types .= "i";
    }
}

if (!empty($_GET['purchase_start'])) {
    $where[] = "purchase_date >= ?";
    $params[] = $_GET['purchase_start'];
    $types .= "s";
}

if (!empty($_GET['purchase_end'])) {
    $where[] = "purchase_date <= ?";
    $params[] = $_GET['purchase_end'];
    $types .= "s";
}

if (!empty($_GET['activity_start'])) {
    $where[] = "activity_date >= ?";
    $params[] = $_GET['activity_start'];
    $types .= "s";
}

if (!empty($_GET['activity_end'])) {
    $where[] = "activity_date <= ?";
    $params[] = $_GET['activity_end'];
    $types .= "s";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = "
    SELECT 
        b.id,
        b.booking_code,
        b.product_id,
        b.option_name,
        b.customer_name,
        b.people_count,
        b.total_amount,
        b.purchase_date,
        b.activity_date,
        b.status,
        p.title AS product_title,
        (SELECT photo_path FROM product_photos WHERE product_id = b.product_id LIMIT 1) AS thumb
    FROM bookings b
    JOIN products p ON p.id = b.product_id
    $whereSQL
    ORDER BY b.id DESC
";

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$bookings = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings — Supplier Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sidebarAdmin.css">
    <link rel="stylesheet" href="../css/navbarAdmin.css">
    <link rel="stylesheet" href="../css/bookings.css">
</head>

<body class="hm">

    <?php include '../components/sidebarAdmin.html'; ?>
    <?php include '../components/navbarAdmin.html'; ?>

    <div id="content-wrapper">
        <main class="hm-main">

            <section class="bk-hero">
                <h1 class="bk-title">Bookings</h1>

                <div class="bk-layout">
                    
                <!-- FILTER PANEL -->
                    <aside class="bk-filters">

                        <form id="filterForm" method="GET" class="bk-filters">
                            <label class="bk-label" style="font-size: 20px;">Filters</label>

                            <!-- PRODUCT DROPDOWN FILTER -->
                            <div class="bk-dropdown">
                                <label class="bk-label">Products</label>

                                <button type="button" class="bk-select" onclick="toggleProductDropdown()">
                                    <?= isset($_GET['products']) ? "Filtered products" : "All products" ?>
                                </button>

                                <div class="bk-checkbox-panel" id="productDropdown">

                                    <!-- SEARCH BOX -->
                                    <div class="bk-search-wrapper">
                                        <input type="text" id="productSearch" class="bk-search-input"
                                            placeholder="Search products" onkeyup="filterProducts()">
                                        <img src="../images/icons/search.svg" class="bk-search-icon">
                                    </div>

                                    <!-- PRODUCT CHECKBOX LIST (DYNAMIC FROM DATABASE) -->
                                    <div id="productList">
                                        <?php
                                        $prodQ = $conn->query("SELECT id, title FROM products ORDER BY title ASC");
                                        while ($p = $prodQ->fetch_assoc()):
                                            ?>
                                            <label class="bk-product-item">
                                                <input type="checkbox" class="filter-trigger" name="products[]"
                                                    value="<?= $p['id'] ?>" <?= (isset($_GET['products']) && in_array($p['id'], haystack: $_GET['products'])) ? "checked" : "" ?>>
                                                <?= htmlspecialchars($p['title']) ?>
                                            </label>
                                        <?php endwhile; ?>
                                    </div>

                                </div>
                            </div>




                            <!-- PURCHASE DATE FILTER -->
                            <div class="bk-filter-block bk-date-block">

                                <label class="bk-label">Purchase Date</label>

                                <input type="date" class="bk-input start-date filter-trigger" name="purchase_start"
                                    value="<?= $_GET['purchase_start'] ?? '' ?>">

                                <input type="date" class="bk-input end-date filter-trigger" name="purchase_end"
                                    value="<?= $_GET['purchase_end'] ?? '' ?>">

                                <a class="bk-clear" onclick="clearDateFilter('purchase')"
                                    style="display: <?= (!empty($_GET['purchase_start']) || !empty($_GET['purchase_end'])) ? 'block' : 'none' ?>;">
                                    Clear
                                </a>

                            </div>

                            <!-- ACTIVITY DATE FILTER -->
                            <div class="bk-filter-block bk-date-block">

                                <label class="bk-label">Activity Date</label>

                                <input type="date" class="bk-input start-date filter-trigger" name="activity_start"
                                    value="<?= $_GET['activity_start'] ?? '' ?>">

                                <input type="date" class="bk-input end-date filter-trigger" name="activity_end"
                                    value="<?= $_GET['activity_end'] ?? '' ?>">

                                <a class="bk-clear" onclick="clearDateFilter('activity')"
                                    style="display: <?= (!empty($_GET['activity_start']) || !empty($_GET['activity_end'])) ? 'block' : 'none' ?>;">
                                    Clear
                                </a>

                            </div>

                        </form>

                    </aside>
                    <!-- BOOKINGS LIST -->
                    <section class="bk-list">

                        <?php while ($b = $bookings->fetch_assoc()): ?>

                            <article class="bk-card">

                                <img class="bk-thumb" src="<?= $b['thumb'] ?: '../images/no-photo.svg' ?>">

                                <div class="bk-info">

                                    <h3 class="bk-item-title"><?= htmlspecialchars($b['product_title']) ?></h3>
                                    <p class="bk-item-sub">Option: <?= htmlspecialchars($b['option_name']) ?></p>

                                    <div class="bk-meta-row">
                                        <span><?= $b['purchase_date'] ?></span>
                                        <span><?= $b['booking_code'] ?></span>
                                        <span><?= $b['people_count'] ?> people — IDR
                                            <?= number_format($b['total_amount']) ?></span>
                                    </div>

                                    <div class="bk-actions">
                                        <button class="bk-detail-btn">Show details</button>
                                        <button class="bk-cancel-btn">Cancel</button>
                                    </div>

                                    <!-- DETAIL DROPDOWN -->
                                    <div class="bk-detail-box">
                                        <div class="bk-detail-row">Customer Name:
                                            <b><?= htmlspecialchars($b['customer_name']) ?></b></div>
                                        <div class="bk-detail-row">Total People: <b><?= $b['people_count'] ?></b></div>
                                        <div class="bk-detail-row">Amount Paid: <b>IDR
                                                <?= number_format($b['total_amount']) ?></b></div>
                                    </div>

                                    <!-- CANCEL PANEL -->
                                    <div class="bk-cancel-box">
                                        <h4 class="bk-cancel-title">Cancel Booking</h4>

                                        <label class="bk-label">Booking ID</label>
                                        <input class="bk-input" value="<?= $b['booking_code'] ?>" readonly>

                                        <label class="bk-label">Admin Name</label>
                                        <select class="bk-input">
                                            <option value="">Select admin</option>
                                            <?php foreach ($admins as $ad): ?>
                                                <option value="<?= $ad['id'] ?>"><?= htmlspecialchars($ad['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                        <label class="bk-label">Reason (min 15 chars)</label>
                                        <textarea class="bk-input" minlength="15"></textarea>

                                        <button class="bk-send-cancel">Send & Cancel</button>
                                    </div>

                                </div>
                            </article>

                        <?php endwhile; ?>

                    </section>

                </div>
            </section>

        </main>
    </div>

    <script src="../js/sidebarAdmin.js"></script>
    <script src="../js/navbarAdmin.js"></script>
    <script src="../js/bookings.js"></script>

</body>

</html>
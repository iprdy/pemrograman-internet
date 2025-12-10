<?php
session_start();

// Jika user belum login, arahkan ke login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../database/connect.php';

// SEARCH FILTER
$search = "";
$param = "";

if (!empty($_GET['q'])) {
    $search = "WHERE p.title LIKE ?";
    $param = "%" . $_GET['q'] . "%";
}

// QUERY PRODUK (SEARCH)
$query = "
    SELECT p.id, p.title, p.reference_code,
        (SELECT photo_path FROM product_photos WHERE product_id = p.id LIMIT 1) AS thumb
    FROM products p
    $search
    ORDER BY p.id DESC
";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $stmt->bind_param("s", $param);
}

$stmt->execute();
$products = $stmt->get_result();

// DELETE PRODUCT
if (isset($_POST['delete_id'])) {

    $product_id = intval($_POST['delete_id']);

    // Delete photos from storage
    $photos = $conn->prepare("SELECT photo_path FROM product_photos WHERE product_id = ?");
    $photos->bind_param("i", $product_id);
    $photos->execute();
    $result = $photos->get_result();
    while ($p = $result->fetch_assoc()) {
        if (file_exists($p['photo_path'])) unlink($p['photo_path']);
    }
    $photos->close();

    // Delete itinerary file
    $it = $conn->prepare("SELECT itinerary_file FROM products WHERE id = ?");
    $it->bind_param("i", $product_id);
    $it->execute();
    $idata = $it->get_result()->fetch_assoc();
    $it->close();

    if (!empty($idata['itinerary_file']) && file_exists($idata['itinerary_file'])) {
        unlink($idata['itinerary_file']);
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM product_photos WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM product_prices WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    header("Location: home.php?deleted=1");
    exit;
}

$countQuery = "SELECT COUNT(*) AS total FROM products p $search";
$stmtCount = $conn->prepare($countQuery);

if (!empty($search)) {
    $stmtCount->bind_param("s", $param);
}

$stmtCount->execute();
$countResult = $stmtCount->get_result()->fetch_assoc();
$totalProducts = $countResult['total'];

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home — Supplier Portal</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap"
      rel="stylesheet"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/sidebarAdmin.css" />
    <link rel="stylesheet" href="../css/navbarAdmin.css" />
    <link rel="stylesheet" href="../css/home.css" />
  </head>

  <body class="hm">
    <!-- SIDEBAR -->
    <?php include '../components/sidebarAdmin.html'; ?>
    
    <!-- NAVBAR (replace original <header>) -->
    <?php include '../components/navbarAdmin.html'; ?>

    <div id="content-wrapper">
      <main class="hm-main">
        <!-- HERO AREA -->
        <section class="hm-hero">
  
          <div class="hm-toolbar">
            <div class="hm-filters">
  
              <!-- Filter by Product -->
              <label class="hm-filter">
                <span>Filter by Product</span>
                <div class="hm-input-icon">
                 <form method="GET" class="hm-search-form">
                      <input type="search" name="q" placeholder="Search" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"/>
                      <img src="../images/icons/search.svg" alt="">
                  </form>
                </div>
              </label>
  
              <div class="hm-result-hint">
                Showing <?= $totalProducts ?> products
            </div>
            </div>
  
            <!-- Create new product -->
            <a class="hm-create" href="create-product.php">
              <svg class="hm-create-icon" fill="#1677c5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M5,12H19M12,5V19" stroke="#1677c5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Create new product
            </a>
          </div>
  
          <!-- Product List -->
          <section class="hm-list" aria-labelledby="tblTitle">
            <h2 id="tblTitle" class="sr-only">Products</h2>
  
            <div class="hm-table">
              <div class="hm-thead">
                <div class="col-product">Product</div>
                <div class="col-ref">Reference code</span></div>
                <div class="col-action">Action</div>
              </div>

              <?php while ($row = $products->fetch_assoc()): ?>
                <article class="hm-row">

                  <!-- Product column -->
                  <div class="col-product">
                      <img class="thumb" 
                          src="<?= $row['thumb'] ? $row['thumb'] : '../images/no-photo.svg' ?>" 
                          alt="Thumbnail">

                      <div class="meta">
                          <a href="edit-product.php?id=<?= $row['id'] ?>" class="title">
                              <?= ($row['title']) ?>
                          </a>
                      </div>
                  </div>

                  <!-- Reference code -->
                  <div class="col-ref">
                      <?= ($row['reference_code']) ?>
                  </div>

                  <!-- Action buttons -->
                  <div class="col-action">
                      <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn--outline-primary">Edit</a>
                      <button type="button" class="btn btn--outline-danger" onclick="openDeleteModal(<?= $row['id'] ?>)">Delete</button>
                  </div>

                </article>
              <?php endwhile; ?>

            </div>
          </section>
        </section>
  
      </main>
    </div>

    <!-- MODAL DELETE -->
    <div id="modal-delete" class="md">
      <a href="#" class="md__overlay" onclick="closeDeleteModal()"></a>

      <section class="md__card" role="dialog" aria-modal="true">
        <a href="#" class="md__close">✕</a>

        <h2 class="md__title">Delete Product</h2>
        <p class="md__text">
          Are you sure you want to delete this product?<br>
          This action cannot be undone.
        </p>

        <form method="POST">
          <input type="hidden" id="delete_id" name="delete_id">

          <div class="md__actions">
            <a href="#" class="md-btn md-btn--ghost">Cancel</a>
            <button type="submit" class="md-btn md-btn--danger">Delete</button>
          </div>
        </form>

      </section>
    </div>

    <script src="../js/sidebarAdmin.js"></script>
    <script src="../js/navbarAdmin.js"></script>
    <script>
      function openDeleteModal(id) {
          document.getElementById('delete_id').value = id;
          document.getElementById('modal-delete').classList.add('show');
      }
      
      function closeDeleteModal() {
          document.getElementById('modal-delete').classList.remove('show');
      }
    </script>

  </body>
</html>

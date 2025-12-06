<?php
session_start();

// Jika user belum login, arahkan ke login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
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
                  <input type="search" placeholder="Search" />
                  <img src="assets/icons/search.svg" alt="" aria-hidden="true" />
                </div>
              </label>
  
              <!-- Filter by Status -->
              <label class="hm-filter">
                <span>Filter by Status</span>
                <div class="hm-select">
                  <select>
                    <option selected>Select Status</option>
                    <option>Bookable</option>
                    <option>Rejected</option>
                    <option>Pending</option>
                  </select>
                </div>
              </label>
  
              <div class="hm-result-hint">Showing 1 – 10 out of 12 and more</div>
            </div>
  
            <!-- Create new product -->
            <a class="hm-create" href="create-product.html">
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
                <div class="col-product">Product <span class="sort">↕</span></div>
                <div class="col-ref">Reference code <span class="sort">↕</span></div>
                <div class="col-status">Status <span class="sort">↕</span></div>
                <div class="col-action">Action</div>
              </div>
  
              <!-- ROW 1 -->
              <article class="hm-row">
                <div class="col-product">
                  <img class="thumb" src="images/feature-image-1.png" alt="" />
                  <div class="meta">
                    <a href="#" class="title">Bali: ATV, Coffee Plantation, Temple & Monkey Forest Tour</a>
                    <div class="rating">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                    </div>
                  </div>
                </div>
  
                <div class="col-ref">FR_Gianyar TripD</div>
  
                <div class="col-status">
                  <span class="badge badge--reject">Rejected</span>
                </div>
  
                <div class="col-action">
                  <a href="#modal-delete" class="btn btn--outline-danger">Delete</a>
                </div>
              </article>
  
              <!-- ROW 2 -->
              <article class="hm-row">
                <div class="col-product">
                  <img class="thumb" src="images/feature-image-2.png" alt="" />
                  <div class="meta">
                    <a href="#" class="title">Bali: ATV, Coffee Plantation, Temple & Monkey Forest Tour</a>
                    <div class="rating">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                    </div>
                  </div>
                </div>
  
                <div class="col-ref">FR_Gianyar TripA</div>
  
                <div class="col-status">
                  <span class="badge badge--ok">Bookable</span>
                </div>
  
                <div class="col-action">
                  <a href="#" class="btn btn--outline">Details</a>
                </div>
              </article>
  
              <!-- ROW 3 -->
              <article class="hm-row">
                <div class="col-product">
                  <img class="thumb" src="images/feature-image-3.png" alt="" />
                  <div class="meta">
                    <a href="#" class="title">Bali: ATV, Coffee Plantation, Temple & Monkey Forest Tour</a>
                    <div class="rating">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                      <img src="assets/icons/star-empty.svg" alt="">
                    </div>
                  </div>
                </div>
  
                <div class="col-ref">FR_Gianyar TripB</div>
  
                <div class="col-status">
                  <span class="badge badge--ok">Bookable</span>
                </div>
  
                <div class="col-action">
                  <a href="#" class="btn btn--outline">Details</a>
                </div>
              </article>
  
            </div>
          </section>
        </section>
  
        <!-- FOOTER (insert, not modify content) -->
        <?php include '../components/footer.html'; ?>
      </main>
    </div>

    <!-- SR-only -->
    <style>
      .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        white-space: nowrap;
        border: 0;
      }
    </style>

    <!-- MODAL DELETE -->
    <div id="modal-delete" class="md">
      <a href="#" class="md__overlay"></a>

      <section class="md__card" role="dialog" aria-modal="true">
        <a href="#" class="md__close">✕</a>

        <h2 class="md__title">Delete Product</h2>
        <p class="md__text">
          Are you sure you want to delete this product?<br>
          This action cannot be undone.
        </p>

        <div class="md__actions">
          <a href="#" class="md-btn md-btn--ghost">Cancel</a>
          <a href="delete-product.html" class="md-btn md-btn--danger">Delete</a>
        </div>
      </section>
    </div>

    <script src="../js/sidebarAdmin.js"></script>
    <script src="../js/navbarAdmin.js"></script>
  </body>
</html>

<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../database/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $language = $_POST['language'];
    $title = $_POST['title'];
    $full_desc = $_POST['full_description'];
    $include = $_POST['include'];
    $exclude = $_POST['exclude'];
    $duration_days = $_POST['duration_days'];
    $adult_private = $_POST['adult_private'];
    $child_private = $_POST['child_private'];
    $adult_group = $_POST['adult_group'];
    $child_group = $_POST['child_group'];   
    $reference_code = $_POST['reference_code'];
    
    // Upload itinerary file
    $itinerary_path = null;
    if (!empty($_FILES["itinerary"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["itinerary"]["name"]);
        $itinerary_path = "../database/uploads/itinerary/" . $fileName;

        move_uploaded_file($_FILES["itinerary"]["tmp_name"], $itinerary_path);
    }

    // Upload photos
    $photo_paths = [];
    if (!empty($_FILES["photos"]["name"][0])) {
        for ($i = 0; $i < count($_FILES["photos"]["name"]); $i++) {
            if ($_FILES["photos"]["error"][$i] === 0) {

                $fileName = time() . "_" . $_FILES["photos"]["name"][$i];
                $targetPath = "../database/uploads/photos/" . $fileName;

                move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $targetPath);

                $photo_paths[] = $targetPath;
            }
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO products 
        (language, title, full_description, include, exclude, duration_days,
        reference_code, itinerary_file)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssssiss",$language, $title, $full_desc, $include, $exclude, $duration_days, $reference_code, $itinerary_path);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();


    $stmt2 = $conn->prepare("
        INSERT INTO product_prices (product_id, category, adult_price, child_price)
        VALUES (?, 'private', ?, ?)
    ");

    $stmt2->bind_param("iii", $product_id, $adult_private, $child_private);
    $stmt2->execute();
    $stmt2->close();
    
    $stmt3 = $conn->prepare("
        INSERT INTO product_prices (product_id, category, adult_price, child_price)
        VALUES (?, 'group', ?, ?)
    ");

    $stmt3->bind_param("iii", $product_id, $adult_group, $child_group);
    $stmt3->execute();
    $stmt3->close();

    if (!empty($photo_paths)) {
        $stmt4 = $conn->prepare("INSERT INTO product_photos (product_id, photo_path) VALUES (?, ?)");

        foreach ($photo_paths as $path) {
            $stmt4->bind_param("is", $product_id, $path);
            $stmt4->execute();
        }

        $stmt4->close();
    }

    $conn->close();
    // redirect setelah sukses
    header("Location: home.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Create Product — uRoam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- GLOBAL LAYOUT -->
    <link rel="stylesheet" href="../css/sidebarAdmin.css">
    <link rel="stylesheet" href="../css/navbarAdmin.css">

    <!-- PAGE-ONLY CSS -->
    <link rel="stylesheet" href="../css/create-product.css">
</head>

<body class="hm">

    <!-- SIDEBAR -->
    <?php include '../components/sidebarAdmin.html'; ?>

    <!-- NAVBAR -->
    <?php include '../components/navbarAdmin.html'; ?>

    <div id="content-wrapper">
        <main class="cp-main">

            <h1 class="cp-title">Create New Product</h1>

            <form class="cp-grid" method="POST" action="create-product.php" enctype="multipart/form-data">
            
            
                <!-- LEFT COLUMN -->
                <div class="cp-card cp-left">
            
                    <div class="cp-field">
                        <label class="cp-label">Language</label>
                        <select class="cp-input" name="language">
                            <option value="id">Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>
            
                    <div class="cp-field">
                        <label class="cp-label">Title</label>
                        <input type="text" name="title" maxlength="60" class="cp-input">
                    </div>
            
                    <div class="cp-field">
                        <label class="cp-label">Full Description</label>
                        <textarea name="full_description" rows="7" class="cp-textarea"></textarea>
                    </div>
            
                    <div class="cp-field">
                        <label class="cp-label">Include</label>
                        <textarea name="include" rows="4" class="cp-textarea"></textarea>
                    </div>
            
                    <div class="cp-field">
                        <label class="cp-label">Exclude</label>
                        <textarea name="exclude" rows="4" class="cp-textarea"></textarea>
                    </div>
                </div> <!-- END LEFT CARD -->

                <!-- OPTION CARD (NEW) -->
                <div class="cp-card cp-option-card">

                    <h2 class="cp-opt-title">Option</h2>

                    <!-- Duration -->
                    <div class="cp-field">
                        <label class="cp-label">Duration Days</label>
                        <input type="number" name="duration_days" min="1" class="cp-input cp-duration">
                    </div>

                    <!-- PRIVATE CATEGORY -->
                    <div class="cp-option-block">
                        <h3 class="cp-opt-subtitle">Customer Category Private</h3>

                        <div class="cp-price-row">
                            <label class="cp-label-sm">Price</label>

                            <div class="cp-currency">IDR</div>
                            <input type="number" name="adult_private" class="cp-input cp-price">
                            <span class="cp-age-label">Adult 12+</span>
                        </div>

                        <div class="cp-price-row">
                            <label class="cp-label-sm cp-label-empty">Price</label>

                            <div class="cp-currency">IDR</div>
                            <input type="number" name="child_private" class="cp-input cp-price">
                            <span class="cp-age-label">Child &lt;= 12</span>
                        </div>
                    </div>

                    <!-- GROUP CATEGORY -->
                    <div class="cp-option-block">
                        <h3 class="cp-opt-subtitle">Customer Category Group</h3>

                        <div class="cp-price-row">
                            <label class="cp-label-sm">Price</label>

                            <div class="cp-currency">IDR</div>
                            <input type="number" name="adult_group" class="cp-input cp-price">
                            <span class="cp-age-label">Adult 12+</span>
                        </div>

                        <div class="cp-price-row">
                            <label class="cp-label-sm cp-label-empty">Price</label>

                            <div class="cp-currency">IDR</div>
                            <input type="number" name="child_group" class="cp-input cp-price">
                            <span class="cp-age-label">Child &lt;= 12</span>
                        </div>
                    </div>

                </div>
            
                <!-- RIGHT COLUMN -->
                <div class="cp-card cp-right">
            
                    <div class="cp-field">
                        <label class="cp-label">Itinerary File</label>
                        <label class="cp-upload">
                            <input type="file" id="itineraryInput" name="itinerary" accept="application/pdf">
                            <div class="cp-upload-box">
                                <img src="../images/icons/upload.svg" alt="">
                                <span>Upload Itinerary File (pdf)</span>
                            </div>
                        </label>
                        
                        <p id="itineraryStatus" class="cp-note"></p>

                        <label class="cp-label">Photos</label>
            
                        <label class="cp-upload">
                            <input type="file" id="photoInput" name="photos[]" accept="image/*" multiple>
                            <div class="cp-upload-box">
                                <img src="../images/icons/upload.svg" alt="">
                                <span>Upload Photos</span>
                            </div>
                        </label>
            
                        <p id="photoStatus" class="cp-note"></p>
                        <p class="cp-note">Atleast upload 1 photo.</p>
            
                        <div class="cp-photo-grid" id="photoGrid">
                            <div class="slot"></div>
                            <div class="slot"></div>
                            <div class="slot"></div>
                            <div class="slot"></div>
                        </div>
                    </div>
            
                    <div class="cp-field">
                        <label class="cp-label">Reference Code</label>
                        <input type="text" name="reference_code" class="cp-input">
                    </div>     

                    <!-- Actions -->
                    <div class="cp-actions">
                        <a href="home.php" class="cp-btn cp-btn-ghost">Cancel</a>
                        <button class="cp-btn cp-btn-primary">Save Product</button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <!-- JS -->
    <script src="../js/sidebarAdmin.js"></script>
    <script src="../js/navbarAdmin.js"></script>
    <script src="../js/create-product.js"></script>

</body>
</html>

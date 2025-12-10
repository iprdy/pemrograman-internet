<?php
session_start();

require '../database/connect.php';

// ====== CEK LOGIN ======
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID dari URL
$product_id = $_GET['id'] ?? 0;

// ====== AMBIL DATA PRODUCT ======
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found.");
}

// ====== AMBIL HARGA PRIVATE & GROUP ======
$prices = [
    "private" => ["adult_price" => 0, "child_price" => 0],
    "group"   => ["adult_price" => 0, "child_price" => 0]
];

$p = $conn->prepare("SELECT category, adult_price, child_price 
                     FROM product_prices WHERE product_id = ?");
$p->bind_param("i", $product_id);
$p->execute();
$res = $p->get_result();
while ($row = $res->fetch_assoc()) {
    $prices[$row['category']] = $row;
}
$p->close();

// ====== AMBIL FOTO ======
$photos = [];
$getPhotos = $conn->prepare("SELECT id, photo_path FROM product_photos WHERE product_id = ?");
$getPhotos->bind_param("i", $product_id);
$getPhotos->execute();
$photos = $getPhotos->get_result()->fetch_all(MYSQLI_ASSOC);
$getPhotos->close();


// ===================================================
// ================ HANDLE UPDATE (POST) =============
// ===================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $language = $_POST['language'];
    $title = $_POST['title'];
    $full_desc = $_POST['full_description'];
    $include = $_POST['include'];
    $exclude = $_POST['exclude'];
    $duration_days = $_POST['duration_days'];
    $reference_code = $_POST['reference_code'];

    // ====== HANDLE ITINERARY ======
    $update_itinerary_sql = "";
    $itinerary_path = $product['itinerary_file'];

    if (!empty($_FILES["itinerary"]["name"])) {

        // Hapus file lama
        if (!empty($product['itinerary_file']) && file_exists($product['itinerary_file'])) {
            unlink($product['itinerary_file']);
        }

        $fileName = time() . "_" . basename($_FILES["itinerary"]["name"]);
        $itinerary_path = "../database/uploads/itinerary/" . $fileName;

        move_uploaded_file($_FILES["itinerary"]["tmp_name"], $itinerary_path);

        $update_itinerary_sql = ", itinerary_file = '$itinerary_path'";
    }

    // ====== UPDATE PRODUCT ======
    $stmt = $conn->prepare("
        UPDATE products SET
            language = ?, 
            title = ?, 
            full_description = ?, 
            include = ?, 
            exclude = ?, 
            duration_days = ?, 
            reference_code = ?
            $update_itinerary_sql
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssssissi",
        $language, $title, $full_desc, $include, $exclude,
        $duration_days, $reference_code, $product_id
    );

    $stmt->execute();
    $stmt->close();


    // ====== UPDATE PRICES ======
    $conn->query("DELETE FROM product_prices WHERE product_id = $product_id");

    // PRIVATE
    $stmt2 = $conn->prepare("
        INSERT INTO product_prices (product_id, category, adult_price, child_price)
        VALUES (?, 'private', ?, ?)
    ");
    $stmt2->bind_param("iii", $product_id, $_POST['adult_private'], $_POST['child_private']);
    $stmt2->execute();
    $stmt2->close();

    // GROUP
    $stmt3 = $conn->prepare("
        INSERT INTO product_prices (product_id, category, adult_price, child_price)
        VALUES (?, 'group', ?, ?)
    ");
    $stmt3->bind_param("iii", $product_id, $_POST['adult_group'], $_POST['child_group']);
    $stmt3->execute();
    $stmt3->close();

    // ====== HANDLE PHOTO REPLACEMENT ======
    if (!empty($_FILES["photos"]["name"][0])) {

        // 1. DELETE OLD PHOTOS FROM STORAGE
        $oldPhotos = $conn->prepare("SELECT photo_path FROM product_photos WHERE product_id = ?");
        $oldPhotos->bind_param("i", $product_id);
        $oldPhotos->execute();
        $oldResult = $oldPhotos->get_result();

        while ($op = $oldResult->fetch_assoc()) {
            if (file_exists($op['photo_path'])) {
                unlink($op['photo_path']);
            }
        }
        $oldPhotos->close();

        // 2. DELETE OLD PHOTOS FROM DATABASE
        $conn->query("DELETE FROM product_photos WHERE product_id = $product_id");

        // 3. INSERT NEW PHOTOS
        foreach ($_FILES["photos"]["name"] as $i => $photoName) {
            if ($_FILES["photos"]["error"][$i] === 0) {

                $fileName = time() . "_" . $photoName;
                $targetPath = "../database/uploads/photos/" . $fileName;

                move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $targetPath);

                $stmt4 = $conn->prepare("
                    INSERT INTO product_photos (product_id, photo_path)
                    VALUES (?, ?)
                ");
                $stmt4->bind_param("is", $product_id, $targetPath);
                $stmt4->execute();
                $stmt4->close();
            }
        }
    }


    header("Location: edit-product.php?id=$product_id&updated=1");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Product — uRoam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <!-- GLOBAL LAYOUT -->
    <link rel="stylesheet" href="../css/sidebarAdmin.css">
    <link rel="stylesheet" href="../css/navbarAdmin.css">

    <!-- PAGE CSS -->
    <link rel="stylesheet" href="../css/create-product.css">
</head>

<body class="hm">

<?php include '../components/sidebarAdmin.html'; ?>
<?php include '../components/navbarAdmin.html'; ?>

<div id="content-wrapper">
<main class="cp-main">

    <h1 class="cp-title">Edit Product</h1>

    <form class="cp-grid" method="POST" enctype="multipart/form-data">

        <!-- LEFT SIDE -->
        <div class="cp-card cp-left">

            <div class="cp-field">
                <label class="cp-label">Language</label>
                <select class="cp-input" name="language">
                    <option value="id" <?= $product['language']=='id'?'selected':'' ?>>Indonesia</option>
                    <option value="en" <?= $product['language']=='en'?'selected':'' ?>>English</option>
                </select>
            </div>

            <div class="cp-field">
                <label class="cp-label">Title</label>
                <input type="text" name="title" maxlength="60" class="cp-input"
                       value="<?= htmlspecialchars($product['title']) ?>">
            </div>

            <div class="cp-field">
                <label class="cp-label">Full Description</label>
                <textarea name="full_description" rows="7" class="cp-textarea"><?= htmlspecialchars($product['full_description']) ?></textarea>
            </div>

            <div class="cp-field">
                <label class="cp-label">Include</label>
                <textarea name="include" rows="4" class="cp-textarea"><?= htmlspecialchars($product['include']) ?></textarea>
            </div>

            <div class="cp-field">
                <label class="cp-label">Exclude</label>
                <textarea name="exclude" rows="4" class="cp-textarea"><?= htmlspecialchars($product['exclude']) ?></textarea>
            </div>

        </div>

        <!-- OPTION CARD -->
        <div class="cp-card cp-option-card">

            <h2 class="cp-opt-title">Option</h2>

            <div class="cp-field">
                <label class="cp-label">Duration Days</label>
                <input type="number" name="duration_days" class="cp-input"
                       value="<?= $product['duration_days'] ?>">
            </div>

            <!-- PRIVATE -->
            <div class="cp-option-block">
                <h3 class="cp-opt-subtitle">Customer Category Private</h3>

                <div class="cp-price-row">
                    <div class="cp-currency">IDR</div>
                    <input type="number" name="adult_private" class="cp-input cp-price"
                           value="<?= $prices['private']['adult_price'] ?>">
                    <span class="cp-age-label">Adult 12+</span>
                </div>

                <div class="cp-price-row">
                    <div class="cp-currency">IDR</div>
                    <input type="number" name="child_private" class="cp-input cp-price"
                           value="<?= $prices['private']['child_price'] ?>">
                    <span class="cp-age-label">Child &lt;= 12</span>
                </div>
            </div>

            <!-- GROUP -->
            <div class="cp-option-block">
                <h3 class="cp-opt-subtitle">Customer Category Group</h3>

                <div class="cp-price-row">
                    <div class="cp-currency">IDR</div>
                    <input type="number" name="adult_group" class="cp-input cp-price"
                           value="<?= $prices['group']['adult_price'] ?>">
                    <span class="cp-age-label">Adult 12+</span>
                </div>

                <div class="cp-price-row">
                    <div class="cp-currency">IDR</div>
                    <input type="number" name="child_group" class="cp-input cp-price"
                           value="<?= $prices['group']['child_price'] ?>">
                    <span class="cp-age-label">Child &lt;= 12</span>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="cp-card cp-right">
            <div class="cp-field">
                <!-- CURRENT ITINERARY -->
                <label class="cp-label">Current Itinerary File</label>
                <?php if ($product['itinerary_file']): ?>
                    <p class="cp-note">
                        <a href="<?= $product['itinerary_file'] ?>" target="_blank">View Current PDF</a>
                    </p>
                <?php else: ?>
                    <p class="cp-note">No itinerary uploaded yet.</p>
                <?php endif; ?>

                <!-- UPLOAD NEW ITINERARY -->
                <label class="cp-label">Upload New Itinerary File</label>
                <label class="cp-upload">
                    <input type="file" name="itinerary" accept="application/pdf" id="itineraryInput">
                    <div class="cp-upload-box">
                        <img src="../images/icons/upload.svg">
                        <span>Choose PDF</span>
                    </div>
                </label>
                <p id="itineraryStatus" class="cp-note"></p>

                <!-- CURRENT PHOTOS -->
                <label class="cp-label" style="margin-top:20px;">Current Photos</label>

                <div class="cp-photo-grid">
                    <?php foreach ($photos as $p): ?>
                        <div class="slot">
                            <img src="<?= $p['photo_path'] ?>" class="photo-thumb">
                        </div>
                    <?php endforeach; ?>

                    <?php for ($i = count($photos); $i < 4; $i++): ?>
                        <div class="slot"></div>
                    <?php endfor; ?>
                </div>


                <!-- UPLOAD NEW PHOTOS -->
                <label class="cp-label" style="margin-top:20px;">Upload New Photos</label>

                <label class="cp-upload">
                    <input type="file" name="photos[]" accept="image/*" multiple id="photoInput">
                    <div class="cp-upload-box">
                        <img src="../images/icons/upload.svg">
                        <span>Choose Images</span>
                    </div>
                </label>

                <p id="photoStatus" class="cp-note"></p>

                <!-- PREVIEW NEW PHOTOS -->
                <label class="cp-label" style="margin-top:20px;">New Photos Preview</label>

                <div class="cp-photo-grid" id="newPhotoPreview">
                    <div class="slot"></div>
                    <div class="slot"></div>
                    <div class="slot"></div>
                    <div class="slot"></div>
                </div>
            </div>

            <!-- REFERENCE CODE -->
            <div class="cp-field" style="margin-top:20px;">
                <label class="cp-label">Reference Code</label>
                <input type="text" name="reference_code" class="cp-input"
                    value="<?= htmlspecialchars($product['reference_code']) ?>">
            </div>

            <!-- ACTION BUTTONS -->
            <div class="cp-actions">
                <a href="home.php" class="cp-btn cp-btn-ghost">Cancel</a>
                <button class="cp-btn cp-btn-primary">Save Changes</button>
            </div>
        </div>

    </form>

</main>
</div>

<script src="../js/sidebarAdmin.js"></script>
<script src="../js/navbarAdmin.js"></script>
<script>
    document.getElementById("photoInput").addEventListener("change", function () {
        const previewGrid = document.getElementById("newPhotoPreview");
        const files = this.files;

        // Reset grid
        previewGrid.innerHTML = `
            <div class="slot"></div>
            <div class="slot"></div>
            <div class="slot"></div>
            <div class="slot"></div>
        `;

        // Update status
        document.getElementById("photoStatus").textContent =
            files.length + " photo(s) selected";

        // Fill grid with previews
        [...files].slice(0,4).forEach((file, index) => {
            const imgURL = URL.createObjectURL(file);
            const slot = previewGrid.children[index];

            slot.innerHTML = `<img src="${imgURL}" class="photo-thumb">`;
        });
    });
</script>

<script>
    document.getElementById("itineraryInput").addEventListener("change", function() {
    if (this.files.length > 0) {
        document.getElementById("itineraryStatus").textContent =
            "Uploaded: " + this.files[0].name;
    } else {
        document.getElementById("itineraryStatus").textContent = "";
    }
});
</script>
</body>
</html>

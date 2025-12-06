<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Log in — Supplier Portal</title>

  <!-- GANTI DI SINI (FONT bila perlu) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Solway:wght@300;400;500;700;800&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/login.css" />
</head>

<body class="lg">
  <!-- HEADER -->
  <?php include '../components/navbar.html'; ?>

  <main class="lg-main">
    <!-- Area gradient (atur di CSS) -->
    <section class="lg-hero">
      <!-- Kartu form -->
      <section class="lg-card" aria-labelledby="loginTitle">
        <h1 id="loginTitle" class="lg-title">Log in to the Supplier Portal</h1>

        <form class="lg-form" action="#" method="post" novalidate>
          <!-- Email -->
          <div class="lg-field">
            <label for="email" class="lg-label">
              Email<span aria-hidden="true" class="lg-req">*</span>
            </label>
            <input id="email" name="email" type="email" autocomplete="username" required class="lg-input" />
          </div>

          <!-- Password -->
          <div class="lg-field">
            <label for="password" class="lg-label">
              Password<span aria-hidden="true" class="lg-req">*</span>
            </label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
              class="lg-input" />
          </div>

          <!-- Submit -->
          <div class="lg-actions">
            <button type="submit" class="lg-btn lg-btn--primary">
              Log in
            </button>
          </div>
        </form>
      </section>
    </section>

    <!-- FOOTER -->
    <?php include '../components/footer.html'; ?>
  </main>

  </body>

</html>
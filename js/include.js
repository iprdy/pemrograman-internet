document.addEventListener("DOMContentLoaded", function() {
  // Tentukan path berdasarkan lokasi file HTML
  const basePath = window.location.pathname.includes('/admin/') ? '../' : '';
  console.log("basePath:", basePath);
  console.log("location.pathname:", window.location.pathname);
  
  // Masukkan Navbar
  const navbarUrl = basePath + "components/navbar.html";
  console.log("Fetching navbar from:", navbarUrl);
  
  fetch(navbarUrl)
    .then(res => {
      console.log("Navbar response status:", res.status);
      return res.text();
    })
    .then(data => {
      console.log("Navbar data received, length:", data.length);
      const navbarPlaceholder = document.querySelector("#navbar-placeholder");
      if (navbarPlaceholder) {
        navbarPlaceholder.innerHTML = data;
        console.log("Navbar injected");
        
        // Perbaiki path image di navbar
        const navbarImages = navbarPlaceholder.querySelectorAll("img");
        console.log("Found navbar images:", navbarImages.length);
        navbarImages.forEach(img => {
          if (img.src && img.src.includes("images/")) {
            const oldSrc = img.src;
            img.src = basePath + "images/" + img.src.split("images/")[1];
            console.log("Fixed image path from", oldSrc, "to", img.src);
          }
        });
      }
    })
    .catch(err => console.error("Error loading navbar:", err));

  // Masukkan Footer
  const footerUrl = basePath + "components/footer.html";
  console.log("Fetching footer from:", footerUrl);
  
  fetch(footerUrl)
    .then(res => {
      console.log("Footer response status:", res.status);
      return res.text();
    })
    .then(data => {
      console.log("Footer data received, length:", data.length);
      const footerPlaceholder = document.querySelector("#footer-placeholder");
      if (footerPlaceholder) {
        footerPlaceholder.innerHTML = data;
        console.log("Footer injected");
        
        // Perbaiki path link di footer
        const footerLinks = footerPlaceholder.querySelectorAll("a");
        console.log("Found footer links:", footerLinks.length);
        footerLinks.forEach(link => {
          const href = link.getAttribute("href");
          if (href && !href.startsWith("#") && !href.startsWith("http")) {
            // Jika di admin folder, tambah ../
            if (basePath === "../") {
              if (!href.startsWith("../")) {
                const oldHref = link.href;
                link.href = basePath + href;
                console.log("Fixed link from", oldHref, "to", link.href);
              }
            }
          }
        });
      }
    })
    .catch(err => console.error("Error loading footer:", err));
});

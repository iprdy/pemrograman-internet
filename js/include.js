document.addEventListener("DOMContentLoaded", function() {
  // Masukkan Navbar
  fetch("components/navbar.html")
    .then(res => res.text())
    .then(data => {
      document.querySelector("#navbar-placeholder").innerHTML = data;
    });

  // Masukkan Footer
  fetch("components/footer.html")
    .then(res => res.text())
    .then(data => {
      document.querySelector("#footer-placeholder").innerHTML = data;
    });
});

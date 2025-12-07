const body = document.body;
const sidebarLogo = document.getElementById("sidebarLogo");
const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("sidebarToggle");
const wrapper = document.getElementById("content-wrapper");

// Default = expanded
wrapper.classList.add("content-expanded");
body.classList.add("sidebar-expanded");

toggleBtn.onclick = () => {
  const collapsed = sidebar.classList.toggle("collapsed");

  // Update wrapper
  wrapper.classList.remove("content-expanded", "content-collapsed");
  wrapper.classList.add(collapsed ? "content-collapsed" : "content-expanded");
  sidebarLogo.style.opacity = "1";

  // Update body class
  body.classList.remove("sidebar-expanded", "sidebar-collapsed");
  body.classList.add(collapsed ? "sidebar-collapsed" : "sidebar-expanded");
  sidebarLogo.style.opacity = collapsed ? "0" : "1";
  toggleBtn.classList.toggle("rotated", collapsed);
};

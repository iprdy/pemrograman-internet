const body = document.body;
const sidebarLogo = document.getElementById("sidebarLogo");

const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("sidebarToggle");
const wrapper = document.getElementById("content-wrapper");

// Default = expanded
wrapper.classList.add("push-expanded");
sidebarLogo.style.opacity = "1";

toggleBtn.onclick = () => {
  const collapsed = sidebar.classList.toggle("collapsed");

  wrapper.classList.remove("push-expanded", "push-collapsed");
  wrapper.classList.add(collapsed ? "push-collapsed" : "push-expanded");

  sidebarLogo.style.opacity = collapsed ? "0" : "1";
};

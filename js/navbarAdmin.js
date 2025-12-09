const profileToggle = document.getElementById("profileToggle");
const profileMenu = document.getElementById("profileMenu");

// Toggle profile dropdown
profileToggle.onclick = (e) => {
  e.stopPropagation();
  profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
  langMenu.style.display = "none";
};

// Close if clicked outside
document.addEventListener("click", () => {
  langMenu.style.display = "none";
  profileMenu.style.display = "none";
});

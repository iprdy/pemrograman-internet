const langToggle = document.getElementById("langToggle");
const langMenu = document.getElementById("langMenu");

const profileToggle = document.getElementById("profileToggle");
const profileMenu = document.getElementById("profileMenu");

// Toggle language dropdown
langToggle.onclick = (e) => {
  e.stopPropagation();
  langMenu.style.display = langMenu.style.display === "block" ? "none" : "block";
  profileMenu.style.display = "none";
};

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

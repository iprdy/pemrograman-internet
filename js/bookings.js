function toggleProductDropdown() {
    const dropdown = document.getElementById('productDropdown');
    const btn = document.querySelector('.bk-select');
    const rect = btn.getBoundingClientRect();

    dropdown.style.left = rect.left + "px";
    dropdown.style.top = (rect.bottom + 5) + "px";

    dropdown.style.display =
        (dropdown.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", function (e) {
    const panel = document.getElementById("productDropdown");
    const button = document.querySelector(".bk-select");
    if (!panel.contains(e.target) && !button.contains(e.target)) {
        panel.style.display = "none";
    }   
});

function filterProducts() {
    let input = document.getElementById("productSearch").value.toLowerCase();
    let items = document.querySelectorAll("#productList .bk-product-item");
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? "flex" : "none";
    });
}

document.querySelectorAll(".bk-detail-btn").forEach(btn => {
    btn.addEventListener("click", function () {

        const detailBox = this.parentElement.nextElementSibling; // .bk-detail-box
        const cancelBox = detailBox.nextElementSibling;          // .bk-cancel-box

        cancelBox.style.display = "none";

        if (detailBox.style.display === "block") {
            detailBox.style.display = "none";
            this.textContent = "Show details";
        } else {
            detailBox.style.display = "block";
            this.textContent = "Hide details";
        }
    });
});

document.querySelectorAll(".bk-cancel-btn").forEach(btn => {
    btn.addEventListener("click", function () {

        const detailBox = this.parentElement.nextElementSibling;     // .bk-detail-box
        const cancelBox = detailBox.nextElementSibling;              // .bk-cancel-box

        detailBox.style.display = "none";

        cancelBox.style.display = (cancelBox.style.display === "block")
            ? "none"
            : "block";
    });
});

// SHOW/HIDE CLEAR BUTTONS + CLEAR LOGIC
document.querySelectorAll(".bk-date-block").forEach(block => {

    const start = block.querySelector(".start-date");
    const end = block.querySelector(".end-date");
    const clearBtn = block.querySelector(".bk-clear");

    function updateClearVisibility() {
        if (start.value || end.value) {
            clearBtn.style.display = "block";
        } else {
            clearBtn.style.display = "none";
        }
    }

    // Saat user mengubah tanggal → cek lagi
    start.addEventListener("change", updateClearVisibility);
    end.addEventListener("change", updateClearVisibility);

    // Logika tombol CLEAR
    clearBtn.addEventListener("click", () => {
        start.value = "";
        end.value = "";
        updateClearVisibility();
    });
});
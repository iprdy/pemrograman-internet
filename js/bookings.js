function toggleProductDropdown() {
    const dropdown = document.getElementById("productDropdown");
    const btn = document.querySelector(".bk-select");
    const rect = btn.getBoundingClientRect();

    dropdown.style.left = rect.left + "px";
    dropdown.style.top = rect.bottom + 5 + "px";

    dropdown.style.display =
        dropdown.style.display === "block" ? "none" : "block";
}

// CLOSE DROPDOWN WHEN CLICK OUTSIDE
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

document.querySelectorAll("#productList input[type='checkbox']").forEach(cb => {
    cb.addEventListener("change", () => {
        document.getElementById("filterForm").submit();
    });
});

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

        const detailBox = this.parentElement.nextElementSibling; // .bk-detail-box
        const cancelBox = detailBox.nextElementSibling;          // .bk-cancel-box

        detailBox.style.display = "none";

        cancelBox.style.display =
            cancelBox.style.display === "block" ? "none" : "block";
    });
});

document.querySelectorAll(".bk-date-block").forEach(block => {

    const start = block.querySelector(".start-date");
    const end = block.querySelector(".end-date");
    const clearBtn = block.querySelector(".bk-clear");

    function updateClearVisibility() {
        clearBtn.style.display = (start.value || end.value) ? "block" : "none";
    }

    // submit filter on change
    function autoSubmit() {
        document.getElementById("filterForm").submit();
    }

    start.addEventListener("change", () => { updateClearVisibility(); autoSubmit(); });
    end.addEventListener("change", () => { updateClearVisibility(); autoSubmit(); });

    // clear button resets date & auto-submit
    clearBtn.addEventListener("click", () => {
        start.value = "";
        end.value = "";
        updateClearVisibility();
        autoSubmit();
    });
});

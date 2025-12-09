document.getElementById("itineraryInput").addEventListener("change", function() {
    if (this.files.length > 0) {
        document.getElementById("itineraryStatus").textContent =
            "Uploaded: " + this.files[0].name;
    } else {
        document.getElementById("itineraryStatus").textContent = "";
    }
});

document.getElementById("photoInput").addEventListener("change", function() {
    const status = document.getElementById("photoStatus");

    if (this.files.length > 0) {
        let names = [];

        for (let i = 0; i < this.files.length && i < 4; i++) {
            names.push(this.files[i].name);
        }
        
        status.textContent = "Uploaded: " + names.join(", ");

    } else {
        status.textContent = "";
    }
});


document.addEventListener("DOMContentLoaded", () => {

    const photoInput = document.getElementById("photoInput");
    const slots = document.querySelectorAll(".cp-photo-grid .slot");

    if (photoInput) {
        photoInput.addEventListener("change", function () {

            slots.forEach(slot => {
                slot.style.backgroundImage = "";
                slot.style.backgroundSize = "";
                slot.style.backgroundPosition = "";
                slot.style.backgroundColor = "#dfdfdfff";
            });

            const files = Array.from(this.files);

            files.slice(0, 4).forEach((file, index) => {

                const reader = new FileReader();
                reader.onload = function (e) {
                    slots[index].style.backgroundImage = `url(${e.target.result})`;
                    slots[index].style.backgroundSize = "cover";
                    slots[index].style.backgroundPosition = "center";
                    slots[index].style.backgroundColor = "transparent"; 
                }
                reader.readAsDataURL(file);
            });

        });
    }
});
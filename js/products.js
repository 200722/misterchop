function toggleMenu() {
    var productBox = document.getElementById("productBox");
    if (productBox.style.display === "block") {
        productBox.style.transform = 'translate(-50%, -50%) scale(0.5)'; // Scale down
        setTimeout(function () {
            productBox.style.display = "none";
        }, 300); // Delay to allow transition to finish
    } else {
        productBox.style.display = "block";
        productBox.style.transform = 'translate(-50%, -50%) scale(2)'; // Scale back to normal
    }
}

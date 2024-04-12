// Hide all service descriptions initially
document.querySelectorAll('.service-description').forEach(function (description) {
    description.style.display = 'none';
});

function toggleDescription(descriptionId) {
    var description = document.getElementById(descriptionId);
    var modal = document.getElementById('descriptionModal');
    var modalContent = document.getElementById('modalContent');

    if (description && description.textContent) {
        // Set the description content
        modalContent.textContent = description.textContent;

        // Display the modal
        modal.style.display = 'block';
    } else {
        // Hide the modal if no description or content is empty
        modal.style.display = 'none';
    }
}

// Close the modal when clicking the close button
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('descriptionModal').style.display = 'none';
});


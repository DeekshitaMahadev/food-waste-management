// General JavaScript functions for the Food Donation System

// Function to initialize tooltips
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

// Function to show/hide password
function togglePassword(fieldId, iconId) {
    var field = document.getElementById(fieldId);
    var icon = document.getElementById(iconId);
    
    if (field.type === "password") {
        field.type = "text";
        icon.className = "fas fa-eye-slash";
    } else {
        field.type = "password";
        icon.className = "fas fa-eye";
    }
}

// Function to preview image before upload
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('imagePreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Function to validate form
function validateForm(formId) {
    var form = document.getElementById(formId);
    var isValid = true;
    
    // Reset previous error messages
    var errorElements = form.querySelectorAll('.invalid-feedback');
    errorElements.forEach(function(element) {
        element.style.display = 'none';
    });
    
    // Check required fields
    var requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            var feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'block';
            }
            isValid = false;
        }
    });
    
    return isValid;
}

// Function to confirm deletion
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Function to copy text to clipboard
function copyToClipboard(text) {
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    
    // Show confirmation message
    alert('Copied to clipboard: ' + text);
}

// Function to format date
function formatDate(dateString) {
    var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Function to initialize Google Maps
function initMap() {
    // This function can be extended if we need dynamic map initialization
    console.log("Google Maps initialized");
}
// Live Expiry Countdown Timer for Food Donation System

// Function to update countdown timers
function updateCountdowns() {
    // Get all countdown elements
    var countdownElements = document.querySelectorAll('.countdown-timer[data-expiry]');
    
    countdownElements.forEach(function(element) {
        var expiryTime = element.getAttribute('data-expiry');
        var countdownText = calculateCountdown(expiryTime);
        element.textContent = countdownText;
        
        // Update class based on time remaining
        var timeRemaining = new Date(expiryTime) - new Date();
        if (timeRemaining < 0) {
            element.classList.add('text-danger');
            element.classList.remove('text-warning');
        } else if (timeRemaining < 24 * 60 * 60 * 1000) { // Less than 24 hours
            element.classList.add('text-warning');
            element.classList.remove('text-danger');
        }
    });
}

// Function to calculate countdown time
function calculateCountdown(expiryTime) {
    var now = new Date();
    var expiry = new Date(expiryTime);
    var diff = expiry - now;
    
    if (diff <= 0) {
        return "Expired";
    }
    
    var days = Math.floor(diff / (1000 * 60 * 60 * 24));
    var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    var countdownString = "";
    
    if (days > 0) {
        countdownString += days + " day" + (days > 1 ? "s " : " ");
    }
    
    if (hours > 0) {
        countdownString += hours + " hour" + (hours > 1 ? "s " : " ");
    }
    
    if (minutes > 0) {
        countdownString += minutes + " minute" + (minutes > 1 ? "s " : " ");
    }
    
    if (seconds > 0 && days === 0) { // Only show seconds if less than a day
        countdownString += seconds + " second" + (seconds > 1 ? "s" : "");
    }
    
    return "Expires in " + countdownString.trim();
}

// Initialize countdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCountdowns();
    // Update countdowns every minute
    setInterval(updateCountdowns, 60000);
});

// Function to initialize a single countdown (for dynamic content)
function initCountdown(elementId, expiryTime) {
    var element = document.getElementById(elementId);
    if (element) {
        element.setAttribute('data-expiry', expiryTime);
        var countdownText = calculateCountdown(expiryTime);
        element.textContent = countdownText;
    }
}
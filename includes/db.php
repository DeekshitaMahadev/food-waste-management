<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'food_donation_system');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Function to format date for display
function format_date($date) {
    return date('M j, Y g:i A', strtotime($date));
}

// Function to get status badge class
function get_status_badge_class($status) {
    switch($status) {
        case 'available':
            return 'status-available';
        case 'accepted':
            return 'status-accepted';
        case 'picked':
            return 'status-picked';
        case 'delivered':
            return 'status-delivered';
        default:
            return 'status-available';
    }
}

// Function to get status text
function get_status_text($status) {
    switch($status) {
        case 'available':
            return 'Available';
        case 'accepted':
            return 'Accepted';
        case 'picked':
            return 'Picked Up';
        case 'delivered':
            return 'Delivered';
        default:
            return ucfirst($status);
    }
}

// Simple function to get a map link with search query (alternative approach)
function generateMapLinkUrl($address) {
    $encodedAddress = urlencode($address);
    return "https://www.openstreetmap.org/search?query={$encodedAddress}";
}
?>
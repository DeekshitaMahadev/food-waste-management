<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if donor is logged in
function is_donor_logged_in() {
    return isset($_SESSION['donor_id']) && isset($_SESSION['donor_email']);
}

// Redirect to donor login if not logged in
function check_donor_login() {
    if (!is_donor_logged_in()) {
        header("Location: ../donor/donor_login.php");
        exit();
    }
}

// Get donor ID
function get_donor_id() {
    return isset($_SESSION['donor_id']) ? $_SESSION['donor_id'] : null;
}

// Get donor name
function get_donor_name() {
    return isset($_SESSION['donor_name']) ? $_SESSION['donor_name'] : null;
}

// Login donor
function login_donor($id, $email, $name) {
    $_SESSION['donor_id'] = $id;
    $_SESSION['donor_email'] = $email;
    $_SESSION['donor_name'] = $name;
}

// Logout donor
function logout_donor() {
    unset($_SESSION['donor_id']);
    unset($_SESSION['donor_email']);
    unset($_SESSION['donor_name']);
    session_destroy();
}
?>
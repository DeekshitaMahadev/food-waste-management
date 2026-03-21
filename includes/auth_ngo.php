<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if NGO is logged in
function is_ngo_logged_in() {
    return isset($_SESSION['ngo_id']) && isset($_SESSION['ngo_email']);
}

// Redirect to NGO login if not logged in
function check_ngo_login() {
    if (!is_ngo_logged_in()) {
        header("Location: ../ngo/ngo_login.php");
        exit();
    }
}

// Get NGO ID
function get_ngo_id() {
    return isset($_SESSION['ngo_id']) ? $_SESSION['ngo_id'] : null;
}

// Get NGO name
function get_ngo_name() {
    return isset($_SESSION['ngo_name']) ? $_SESSION['ngo_name'] : null;
}

// Login NGO
function login_ngo($id, $email, $name) {
    $_SESSION['ngo_id'] = $id;
    $_SESSION['ngo_email'] = $email;
    $_SESSION['ngo_name'] = $name;
}

// Logout NGO
function logout_ngo() {
    unset($_SESSION['ngo_id']);
    unset($_SESSION['ngo_email']);
    unset($_SESSION['ngo_name']);
    session_destroy();
}
?>
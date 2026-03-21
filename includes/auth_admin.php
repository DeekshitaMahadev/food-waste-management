<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Redirect to admin login if not logged in
function check_admin_login() {
    if (!is_admin_logged_in()) {
        header("Location: ../admin/admin_login.php");
        exit();
    }
}

// Get admin ID
function get_admin_id() {
    return isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
}

// Get admin username
function get_admin_username() {
    return isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : null;
}

// Login admin
function login_admin($id, $username) {
    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_username'] = $username;
}

// Logout admin
function logout_admin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    session_destroy();
}
?>
<?php
session_start();

// Check which user type is logged in and log them out accordingly
if (isset($_SESSION['donor_id'])) {
    // Donor logout
    unset($_SESSION['donor_id']);
    unset($_SESSION['donor_email']);
    unset($_SESSION['donor_name']);
    header("Location: ../donor/donor_login.php?logout=success");
} elseif (isset($_SESSION['ngo_id'])) {
    // NGO logout
    unset($_SESSION['ngo_id']);
    unset($_SESSION['ngo_email']);
    unset($_SESSION['ngo_name']);
    header("Location: ../ngo/ngo_login.php?logout=success");
} elseif (isset($_SESSION['admin_id'])) {
    // Admin logout
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    header("Location: ../admin/admin_login.php?logout=success");
} else {
    // No user logged in, redirect to home
    header("Location: ../index.php");
}

session_destroy();
exit();
?>
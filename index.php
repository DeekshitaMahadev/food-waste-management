<?php
session_start();
include 'includes/db.php';

// Check if any user is logged in and redirect accordingly
if (isset($_SESSION['donor_id'])) {
    header("Location: donor/donor_dashboard.php");
    exit();
} elseif (isset($_SESSION['ngo_id'])) {
    header("Location: ngo/ngo_dashboard.php");
    exit();
} elseif (isset($_SESSION['admin_id'])) {
    header("Location: admin/admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Food Donation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-utensils"></i> Smart Food Donation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown">
                            Login
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="donor/donor_login.php">Donor Login</a></li>
                            <li><a class="dropdown-item" href="ngo/ngo_login.php">NGO Login</a></li>
                            <li><a class="dropdown-item" href="admin/admin_login.php">Admin Login</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="registerDropdown" role="button" data-bs-toggle="dropdown">
                            Register
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="donor/donor_register.php">Donor Registration</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="jumbotron bg-green text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="display-4">Reduce Food Waste, Help Communities</h1>
                    <p class="lead">Join our platform to donate excess food and help those in need. Together, we can make a difference!</p>
                    <hr class="my-4">
                    <p>Sign up as a donor or login as an NGO to get started.</p>
                    <div class="mt-4">
                        <a class="btn btn-light btn-lg me-2" href="donor/donor_register.php" role="button">Donate Food</a>
                        <a class="btn btn-outline-light btn-lg" href="ngo/ngo_login.php" role="button">Receive Donations</a>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block">
                    <img src="https://images.unsplash.com/photo-1547540828-90dc9578e914?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=80" alt="Food Donation" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container my-5">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-3x text-green mb-3"></i>
                        <h5 class="card-title">Register</h5>
                        <p class="card-text">Create an account as a donor or NGO to join our community.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-utensils fa-3x text-green mb-3"></i>
                        <h5 class="card-title">Donate or Receive</h5>
                        <p class="card-text">Donors post food donations, NGOs browse and accept available donations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-hands-helping fa-3x text-green mb-3"></i>
                        <h5 class="card-title">Make a Difference</h5>
                        <p class="card-text">Reduce food waste while helping communities in need.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <?php
                // Get statistics from database
                $donors_count = $conn->query("SELECT COUNT(*) as count FROM donors")->fetch_assoc()['count'];
                $ngos_count = $conn->query("SELECT COUNT(*) as count FROM ngos")->fetch_assoc()['count'];
                $donations_count = $conn->query("SELECT COUNT(*) as count FROM donations")->fetch_assoc()['count'];
                $delivered_count = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status='delivered'")->fetch_assoc()['count'];
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-green"><?php echo $donors_count; ?></h3>
                            <p>Donors</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-green"><?php echo $ngos_count; ?></h3>
                            <p>NGOs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-green"><?php echo $donations_count; ?></h3>
                            <p>Donations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-green"><?php echo $delivered_count; ?></h3>
                            <p>Deliveries Made</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Smart Food Donation System</h5>
                    <p>Reducing food waste and fighting hunger through technology.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Home</a></li>
                        <li><a href="about.php" class="text-white">About</a></li>
                        <li><a href="contact.php" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Login</h5>
                    <ul class="list-unstyled">
                        <li><a href="donor/donor_login.php" class="text-white">Donor</a></li>
                        <li><a href="ngo/ngo_login.php" class="text-white">NGO</a></li>
                        <li><a href="admin/admin_login.php" class="text-white">Admin</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2025 Smart Food Donation System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/script.js"></script>
</body>
</html>
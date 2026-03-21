<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Smart Food Donation System</title>
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
                        <a class="nav-link active" href="about.php">About</a>
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

    <!-- Page Header -->
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>

    <!-- About Section -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-4">About Smart Food Donation System</h1>
                <p class="lead">Our mission is to bridge the gap between food surplus and food scarcity through innovative technology.</p>
                
                <p>The Smart Food Donation System is a comprehensive platform designed to reduce food waste while addressing hunger in communities. We connect food donors with charitable organizations to ensure that excess food reaches those who need it most.</p>
                
                <h3 class="mt-4">Our Vision</h3>
                <p>We envision a world where no edible food goes to waste and no person goes hungry. Through our platform, we aim to create a sustainable ecosystem that benefits donors, recipients, and the environment.</p>
                
                <h3 class="mt-4">How It Works</h3>
                <ol>
                    <li><strong>Donors</strong> register and post details of their food donations</li>
                    <li><strong>NGOs</strong> browse available donations and accept those that meet their needs</li>
                    <li><strong>Tracking</strong> enables both parties to monitor the donation status from acceptance to delivery</li>
                    <li><strong>Impact Measurement</strong> helps us quantify our collective efforts in reducing food waste</li>
                </ol>
                
                <h3 class="mt-4">Key Features</h3>
                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Real-time donation tracking</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Email notifications</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Google Maps integration</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Live expiry countdown</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Image upload for donations</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Multi-user dashboards</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Admin analytics</li>
                            <li><i class="fas fa-check-circle text-green me-2"></i> Secure authentication</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-green text-white">
                        <h5 class="mb-0">Our Impact</h5>
                    </div>
                    <div class="card-body">
                        <p>Since our launch, we've facilitated thousands of food donations that have:</p>
                        <ul>
                            <li>Prevented tons of food waste</li>
                            <li>Helped feed thousands of people</li>
                            <li>Connected hundreds of donors with NGOs</li>
                            <li>Reduced environmental impact</li>
                        </ul>
                        <div class="text-center mt-4">
                            <a href="donor/donor_register.php" class="btn btn-green">Join Us Today</a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Did You Know?</h5>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-info-circle text-green me-2"></i> Approximately one-third of all food produced globally is wasted each year.</p>
                        <p><i class="fas fa-info-circle text-green me-2"></i> At the same time, nearly 690 million people go to bed hungry each night.</p>
                        <p><i class="fas fa-info-circle text-green me-2"></i> Food waste contributes to about 8% of global greenhouse gas emissions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Team</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Team Member">
                        <div class="card-body">
                            <h5 class="card-title">John Anderson</h5>
                            <p class="card-text">Founder & CEO</p>
                            <p class="text-muted">Passionate about technology and social impact.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Team Member">
                        <div class="card-body">
                            <h5 class="card-title">Sarah Johnson</h5>
                            <p class="card-text">Operations Director</p>
                            <p class="text-muted">Expert in logistics and NGO partnerships.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg" class="card-img-top rounded-circle mx-auto mt-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Team Member">
                        <div class="card-body">
                            <h5 class="card-title">Michael Chen</h5>
                            <p class="card-text">Lead Developer</p>
                            <p class="text-muted">Creates seamless user experiences with cutting-edge tech.</p>
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
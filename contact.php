<?php
session_start();
// Process form submission
$message_sent = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real application, you would process the form data here
    // For this demo, we'll just set a flag to show the success message
    $message_sent = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Smart Food Donation System</title>
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
                        <a class="nav-link active" href="contact.php">Contact</a>
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
                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>

    <!-- Contact Section -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-4">Contact Us</h1>
                
                <?php if ($message_sent): ?>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Thank You!</h4>
                    <p>Your message has been sent successfully. We'll get back to you as soon as possible.</p>
                </div>
                <?php endif; ?>
                
                <p class="lead">Have questions or feedback? Reach out to us using the form below or through our contact information.</p>
                
                <form method="POST" action="contact.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-green">Send Message</button>
                </form>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-green text-white">
                        <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-map-marker-alt text-green me-2"></i>
                                <strong>Address</strong><br>
                                123 Food Drive, Suite 100<br>
                                San Francisco, CA 94103
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-phone text-green me-2"></i>
                                <strong>Phone</strong><br>
                                +1 (555) 123-4567
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-envelope text-green me-2"></i>
                                <strong>Email</strong><br>
                                info@smartfooddonation.org
                            </li>
                            <li>
                                <i class="fas fa-clock text-green me-2"></i>
                                <strong>Working Hours</strong><br>
                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Follow Us</h5>
                    </div>
                    <div class="card-body text-center">
                        <a href="#" class="btn btn-primary me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-info me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-danger me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-primary"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">FAQ</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>How do I register as a donor?</strong></p>
                        <p>You can register as a donor by clicking on "Register" and selecting "Donor Registration" from the dropdown menu.</p>
                        
                        <p><strong>How do NGOs receive donations?</strong></p>
                        <p>NGOs can login to their dashboard to view available donations and accept them.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="container my-5">
        <h2 class="mb-4">Find Us</h2>
        <div class="map-container">
            <!-- Google Maps Embed -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.681829803233!2d-122.41941548468244!3d37.77492927975935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c744d6a9f%3A0x952ebf55b31e0faf!2sSan%20Francisco%2C%20CA%2C%20USA!5e0!3m2!1sen!2s!4v1650000000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
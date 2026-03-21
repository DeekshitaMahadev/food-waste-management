<?php
session_start();
include '../includes/db.php';
include '../includes/auth_donor.php';

// Check if donor is logged in
check_donor_login();

$donor_id = get_donor_id();
$donor_name = get_donor_name();

// Get donor's donations
$stmt = $conn->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$donations_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - Smart Food Donation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="donor_dashboard.php">
                <i class="fas fa-utensils"></i> Smart Food Donation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="donor_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_donation.php">Add Donation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_status.php">My Donations</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($donor_name); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="view_status.php">My Donations</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../includes/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h1>Welcome, <?php echo htmlspecialchars($donor_name); ?>!</h1>
                <p class="lead">Thank you for helping reduce food waste in our community.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="add_donation.php" class="btn btn-green btn-lg">
                    <i class="fas fa-plus me-2"></i>Add New Donation
                </a>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Donor Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Section -->
    <div class="container my-4">
        <div class="row">
            <?php
            // Get statistics for this donor
            $total_donations = $conn->query("SELECT COUNT(*) as count FROM donations WHERE donor_id = $donor_id")->fetch_assoc()['count'];
            $pending_donations = $conn->query("SELECT COUNT(*) as count FROM donations WHERE donor_id = $donor_id AND status = 'available'")->fetch_assoc()['count'];
            $accepted_donations = $conn->query("SELECT COUNT(*) as count FROM donations WHERE donor_id = $donor_id AND status = 'accepted'")->fetch_assoc()['count'];
            $completed_donations = $conn->query("SELECT COUNT(*) as count FROM donations WHERE donor_id = $donor_id AND status = 'delivered'")->fetch_assoc()['count'];
            ?>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-green"><?php echo $total_donations; ?></h3>
                        <p>Total Donations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?php echo $pending_donations; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $accepted_donations; ?></h3>
                        <p>Accepted</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo $completed_donations; ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Donations</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($donations_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Quantity</th>
                                        <th>Expiry Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $donations_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                                        <td><?php echo format_date($donation['expiry_time']); ?></td>
                                        <td>
                                            <span class="badge <?php echo get_status_badge_class($donation['status']); ?>">
                                                <?php echo get_status_text($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_status.php?id=<?php echo $donation['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">You haven't made any donations yet. <a href="add_donation.php">Add your first donation</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">How It Works</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-utensils fa-3x text-green mb-3"></i>
                        <h5 class="card-title">1. Add Donation</h5>
                        <p class="card-text">Post details of your food donation including name, quantity, expiry time, and location.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-handshake fa-3x text-green mb-3"></i>
                        <h5 class="card-title">2. NGO Accepts</h5>
                        <p class="card-text">An NGO will review your donation and accept it if it meets their needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-truck fa-3x text-green mb-3"></i>
                        <h5 class="card-title">3. Delivery</h5>
                        <p class="card-text">The NGO will pick up the donation and deliver it to those in need.</p>
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
                        <li><a href="donor_dashboard.php" class="text-white">Dashboard</a></li>
                        <li><a href="add_donation.php" class="text-white">Add Donation</a></li>
                        <li><a href="view_status.php" class="text-white">My Donations</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>User</h5>
                    <ul class="list-unstyled">
                        <li><a href="../includes/logout.php" class="text-white">Logout</a></li>
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
    <script src="../js/script.js"></script>
    <!-- Countdown JS -->
    <script src="../js/countdown.js"></script>
</body>
</html>
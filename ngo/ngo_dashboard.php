<?php
session_start();
include '../includes/db.php';
include '../includes/auth_ngo.php';

// Check if NGO is logged in
check_ngo_login();

$ngo_id = get_ngo_id();
$ngo_name = get_ngo_name();

// Get statistics for this NGO
$total_accepted = $conn->query("SELECT COUNT(*) as count FROM donations WHERE ngo_id = $ngo_id")->fetch_assoc()['count'];
$total_picked = $conn->query("SELECT COUNT(*) as count FROM donations WHERE ngo_id = $ngo_id AND status = 'picked'")->fetch_assoc()['count'];
$total_delivered = $conn->query("SELECT COUNT(*) as count FROM donations WHERE ngo_id = $ngo_id AND status = 'delivered'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Dashboard - Smart Food Donation System</title>
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
            <a class="navbar-brand" href="ngo_dashboard.php">
                <i class="fas fa-utensils"></i> Smart Food Donation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="ngo_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_donations.php">View Donations</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($ngo_name); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="view_donations.php">My Accepted Donations</a></li>
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
            <div class="col-md-12">
                <h1>Welcome, <?php echo htmlspecialchars($ngo_name); ?>!</h1>
                <p class="lead">Thank you for your efforts in fighting hunger in our community.</p>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">NGO Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $total_accepted; ?></h3>
                        <p>Total Accepted</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?php echo $total_picked; ?></h3>
                        <p>Picked Up</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo $total_delivered; ?></h3>
                        <p>Delivered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Get recent donations for this NGO
                        $stmt = $conn->prepare("SELECT d.*, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.ngo_id = ? ORDER BY d.created_at DESC LIMIT 5");
                        $stmt->bind_param("i", $ngo_id);
                        $stmt->execute();
                        $recent_result = $stmt->get_result();
                        $stmt->close();
                        
                        if ($recent_result->num_rows > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Donor</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $recent_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td>
                                            <span class="badge <?php echo get_status_badge_class($donation['status']); ?>">
                                                <?php echo get_status_text($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_donations.php?id=<?php echo $donation['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">You haven't accepted any donations yet. <a href="view_donations.php">View available donations</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Donations Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Recently Available Donations</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Get recently available donations (limit 5)
                        $available_stmt = $conn->prepare("SELECT d.*, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.status = 'available' ORDER BY d.created_at DESC LIMIT 5");
                        $available_stmt->execute();
                        $available_result = $available_stmt->get_result();
                        $available_stmt->close();
                        
                        if ($available_result->num_rows > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Donor</th>
                                        <th>Quantity</th>
                                        <th>Expiry</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $available_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                                        <td><?php echo format_date($donation['expiry_time']); ?></td>
                                        <td>
                                            <a href="view_donations.php?id=<?php echo $donation['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="view_donations.php" class="btn btn-green">View All Available Donations</a>
                        </div>
                        <?php else: ?>
                        <p class="text-center">No donations are currently available.</p>
                        <?php endif; ?>
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
                        <li><a href="ngo_dashboard.php" class="text-white">Dashboard</a></li>
                        <li><a href="view_donations.php" class="text-white">View Donations</a></li>
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
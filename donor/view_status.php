<?php
session_start();
include '../includes/db.php';
include '../includes/auth_donor.php';

// Check if donor is logged in
check_donor_login();

$donor_id = get_donor_id();
$donor_name = get_donor_name();

// Get specific donation if ID is provided
$donation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($donation_id > 0) {
    // Get specific donation details
    $stmt = $conn->prepare("SELECT d.*, n.name as ngo_name FROM donations d LEFT JOIN ngos n ON d.ngo_id = n.id WHERE d.id = ? AND d.donor_id = ?");
    $stmt->bind_param("ii", $donation_id, $donor_id);
    $stmt->execute();
    $donation_result = $stmt->get_result();
    $donation = $donation_result->fetch_assoc();
    $stmt->close();
    
    if (!$donation) {
        header("Location: view_status.php");
        exit();
    }
} else {
    // Get all donations for this donor
    $stmt = $conn->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $donor_id);
    $stmt->execute();
    $donations_result = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $donation_id > 0 ? 'Donation Details' : 'My Donations'; ?> - Smart Food Donation System</title>
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
                        <a class="nav-link" href="donor_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_donation.php">Add Donation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_status.php">My Donations</a>
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
                <h1><?php echo $donation_id > 0 ? 'Donation Details' : 'My Donations'; ?></h1>
                <p class="lead"><?php echo $donation_id > 0 ? 'View details of your donation' : 'Track all your food donations'; ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if ($donation_id > 0): ?>
                <a href="view_status.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to All Donations
                </a>
                <?php else: ?>
                <a href="donor_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="donor_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $donation_id > 0 ? 'Donation Details' : 'My Donations'; ?></li>
            </ol>
        </nav>
    </div>

    <!-- Content Section -->
    <div class="container my-4">
        <?php if ($donation_id > 0): ?>
        <!-- Single Donation Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i><?php echo htmlspecialchars($donation['food_name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Donation Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Food Name:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Quantity:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Expiry Time:</strong></td>
                                        <td><?php echo format_date($donation['expiry_time']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <span class="badge <?php echo get_status_badge_class($donation['status']); ?>">
                                                <?php echo get_status_text($donation['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if ($donation['ngo_id']): ?>
                                    <tr>
                                        <td><strong>Accepted By:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['ngo_name']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><strong>Posted On:</strong></td>
                                        <td><?php echo format_date($donation['created_at']); ?></td>
                                    </tr>
                                </table>
                                
                                <h6>Live Countdown</h6>
                                <p class="countdown-timer" data-expiry="<?php echo $donation['expiry_time']; ?>">Loading...</p>
                                
                                <?php if ($donation['status'] == 'delivered'): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>This donation has been successfully delivered. Thank you for your contribution!
                                </div>
                                <?php elseif ($donation['status'] == 'picked'): ?>
                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-truck-loading me-2"></i>This donation has been picked up and is on its way to be delivered.
                                </div>
                                <?php elseif ($donation['status'] == 'accepted'): ?>
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-handshake me-2"></i>An NGO has accepted this donation. They will contact you soon to arrange pickup.
                                </div>
                                <?php else: ?>
                                <div class="alert alert-secondary" role="alert">
                                    <i class="fas fa-clock me-2"></i>This donation is available for NGOs to accept.
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?php if ($donation['image']): ?>
                                <h6>Food Image</h6>
                                <img src="../uploads/<?php echo htmlspecialchars($donation['image']); ?>" alt="Food Image" class="img-fluid donation-image mb-3">
                                <?php endif; ?>
                                
                                <h6>Pickup Location</h6>
                                <p><?php echo nl2br(htmlspecialchars($donation['location'])); ?></p>
                                
                                <!-- OpenStreetMap Link -->
                                <div class="map-container">
                                    <div class="alert alert-info">
                                        <p><strong>Pickup Location:</strong> <?php echo htmlspecialchars($donation['location']); ?></p>
                                        <p><a href="https://www.openstreetmap.org/search?query=<?php echo urlencode($donation['location']); ?>" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-map-marked-alt me-2"></i>View on Map
                                        </a></p>
                                        <small class="form-text">Click to view this location on OpenStreetMap (opens in new tab)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- All Donations List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>All My Donations</h5>
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
        <?php endif; ?>
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
<?php
session_start();
include '../includes/db.php';
include '../includes/auth_ngo.php';
include '../includes/mailer.php';

// Check if NGO is logged in
check_ngo_login();

$ngo_id = get_ngo_id();
$ngo_name = get_ngo_name();

// Handle accept donation action
if (isset($_GET['accept']) && is_numeric($_GET['accept'])) {
    $donation_id = intval($_GET['accept']);
    
    // Check if donation is available
    $check_stmt = $conn->prepare("SELECT d.*, dr.email as donor_email, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.id = ? AND d.status = 'available'");
    $check_stmt->bind_param("i", $donation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 1) {
        $donation = $check_result->fetch_assoc();
        
        // Update donation status to accepted and assign to this NGO
        $update_stmt = $conn->prepare("UPDATE donations SET status = 'accepted', ngo_id = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $ngo_id, $donation_id);
        
        if ($update_stmt->execute()) {
            // Send email notification to donor
            sendDonationAcceptanceNotification($donation['donor_email'], $donation['donor_name'], $ngo_name, $donation['food_name']);
            
            $success_message = "Donation accepted successfully!";
        } else {
            $error_message = "Error accepting donation. Please try again.";
        }
        
        $update_stmt->close();
    } else {
        $error_message = "Donation not found or already accepted.";
    }
    
    $check_stmt->close();
}

// Handle update status action
if (isset($_POST['update_status'])) {
    $donation_id = intval($_POST['donation_id']);
    $new_status = sanitize_input($_POST['status']);
    
    // Validate status
    if (in_array($new_status, ['picked', 'delivered'])) {
        // Check if donation belongs to this NGO
        $check_stmt = $conn->prepare("SELECT d.*, dr.email as donor_email, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.id = ? AND d.ngo_id = ?");
        $check_stmt->bind_param("ii", $donation_id, $ngo_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows == 1) {
            $donation = $check_result->fetch_assoc();
            
            // Update donation status
            $update_stmt = $conn->prepare("UPDATE donations SET status = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_status, $donation_id);
            
            if ($update_stmt->execute()) {
                // Send email notification to donor when status is delivered
                if ($new_status == 'delivered') {
                    sendDeliveryConfirmationNotification($donation['donor_email'], $donation['donor_name'], $ngo_name, $donation['food_name']);
                }
                
                $success_message = "Donation status updated successfully!";
            } else {
                $error_message = "Error updating donation status. Please try again.";
            }
            
            $update_stmt->close();
        } else {
            $error_message = "Donation not found or does not belong to your organization.";
        }
        
        $check_stmt->close();
    } else {
        $error_message = "Invalid status.";
    }
}

// Get specific donation if ID is provided
$donation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($donation_id > 0) {
    // Get specific donation details
    $stmt = $conn->prepare("SELECT d.*, dr.name as donor_name, dr.email as donor_email, dr.phone as donor_phone FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.id = ?");
    $stmt->bind_param("i", $donation_id);
    $stmt->execute();
    $donation_result = $stmt->get_result();
    $donation = $donation_result->fetch_assoc();
    $stmt->close();
    
    if (!$donation) {
        header("Location: view_donations.php");
        exit();
    }
    
    // Check if this donation belongs to this NGO or is available
    $can_view = ($donation['ngo_id'] == $ngo_id) || ($donation['status'] == 'available');
    if (!$can_view) {
        header("Location: view_donations.php");
        exit();
    }
} else {
    // Get available donations
    $available_stmt = $conn->prepare("SELECT d.*, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.status = 'available' ORDER BY d.created_at DESC");
    $available_stmt->execute();
    $available_result = $available_stmt->get_result();
    $available_stmt->close();
    
    // Get accepted donations for this NGO
    $accepted_stmt = $conn->prepare("SELECT d.*, dr.name as donor_name FROM donations d JOIN donors dr ON d.donor_id = dr.id WHERE d.ngo_id = ? AND d.status IN ('accepted', 'picked', 'delivered') ORDER BY d.created_at DESC");
    $accepted_stmt->bind_param("i", $ngo_id);
    $accepted_stmt->execute();
    $accepted_result = $accepted_stmt->get_result();
    $accepted_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $donation_id > 0 ? 'Donation Details' : 'View Donations'; ?> - Smart Food Donation System</title>
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
                        <a class="nav-link" href="ngo_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_donations.php">View Donations</a>
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
            <div class="col-md-6">
                <h1><?php echo $donation_id > 0 ? 'Donation Details' : 'View Donations'; ?></h1>
                <p class="lead"><?php echo $donation_id > 0 ? 'View details of this donation' : 'Browse available donations and manage your accepted donations'; ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if ($donation_id > 0): ?>
                <a href="view_donations.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to All Donations
                </a>
                <?php else: ?>
                <a href="ngo_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="ngo_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $donation_id > 0 ? 'Donation Details' : 'View Donations'; ?></li>
            </ol>
        </nav>
    </div>

    <!-- Messages -->
    <div class="container my-4">
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
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
                                    <tr>
                                        <td><strong>Donor:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Posted On:</strong></td>
                                        <td><?php echo format_date($donation['created_at']); ?></td>
                                    </tr>
                                </table>
                                
                                <h6>Live Countdown</h6>
                                <p class="countdown-timer" data-expiry="<?php echo $donation['expiry_time']; ?>">Loading...</p>
                                
                                <?php if ($donation['status'] == 'available' && $donation['ngo_id'] != $ngo_id): ?>
                                <a href="?accept=<?php echo $donation['id']; ?>" class="btn btn-green" onclick="return confirm('Are you sure you want to accept this donation?')">
                                    <i class="fas fa-check-circle me-2"></i>Accept Donation
                                </a>
                                <?php elseif ($donation['status'] == 'accepted' && $donation['ngo_id'] == $ngo_id): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="donation_id" value="<?php echo $donation['id']; ?>">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="picked">Picked Up</option>
                                            <option value="delivered">Delivered</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_status" class="btn btn-green">Update Status</button>
                                </form>
                                <?php elseif ($donation['status'] == 'picked' && $donation['ngo_id'] == $ngo_id): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="donation_id" value="<?php echo $donation['id']; ?>">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="delivered">Delivered</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_status" class="btn btn-green">Update Status</button>
                                </form>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <?php if ($donation['image']): ?>
                                <h6>Food Image</h6>
                                <img src="../uploads/<?php echo htmlspecialchars($donation['image']); ?>" alt="Food Image" class="img-fluid donation-image mb-3">
                                <?php endif; ?>
                                
                                <h6>Donor Contact Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['donor_email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td><?php echo htmlspecialchars($donation['donor_phone']); ?></td>
                                    </tr>
                                </table>
                                
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
        <!-- All Donations Lists -->
        <div class="row">
            <div class="col-md-12">
                <!-- Available Donations -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Available Donations</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($available_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Donor</th>
                                        <th>Quantity</th>
                                        <th>Expiry Time</th>
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
                        <?php else: ?>
                        <p class="text-center">No donations are currently available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- My Accepted Donations -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>My Accepted Donations</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($accepted_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Donor</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $accepted_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
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
                        <p class="text-center">You haven't accepted any donations yet.</p>
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
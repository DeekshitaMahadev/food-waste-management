<?php
session_start();
include '../includes/db.php';
include '../includes/auth_admin.php';

// Check if admin is logged in
check_admin_login();

$admin_id = get_admin_id();
$admin_username = get_admin_username();

// Handle delete donation action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $donation_id = intval($_GET['delete']);
    
    // Delete donation
    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->bind_param("i", $donation_id);
    
    if ($stmt->execute()) {
        $success_message = "Donation deleted successfully!";
    } else {
        $error_message = "Error deleting donation. Please try again.";
    }
    
    $stmt->close();
}

// Get all donations with donor and NGO information
$stmt = $conn->prepare("SELECT d.*, dr.name as donor_name, n.name as ngo_name FROM donations d JOIN donors dr ON d.donor_id = dr.id LEFT JOIN ngos n ON d.ngo_id = n.id ORDER BY d.created_at DESC");
$stmt->execute();
$donations_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations Report - Smart Food Donation System</title>
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
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-utensils"></i> Smart Food Donation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_donors.php">Manage Donors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_ngos.php">Manage NGOs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="donations_report.php">Donations Report</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($admin_username); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="donations_report.php">Reports</a></li>
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
                <h1>Donations Report</h1>
                <p class="lead">View and manage all donations in the system.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="admin_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Donations Report</li>
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

    <!-- Donations Table -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>All Donations</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($donations_result->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Food Item</th>
                                        <th>Donor</th>
                                        <th>NGO</th>
                                        <th>Quantity</th>
                                        <th>Expiry Time</th>
                                        <th>Status</th>
                                        <th>Posted On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $donations_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $donation['id']; ?></td>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td><?php echo $donation['ngo_name'] ? htmlspecialchars($donation['ngo_name']) : 'Not assigned'; ?></td>
                                        <td><?php echo htmlspecialchars($donation['quantity']); ?></td>
                                        <td><?php echo format_date($donation['expiry_time']); ?></td>
                                        <td>
                                            <span class="badge <?php echo get_status_badge_class($donation['status']); ?>">
                                                <?php echo get_status_text($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo format_date($donation['created_at']); ?></td>
                                        <td>
                                            <a href="?delete=<?php echo $donation['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this donation?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">No donations found in the system.</p>
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
                        <li><a href="admin_dashboard.php" class="text-white">Dashboard</a></li>
                        <li><a href="manage_donors.php" class="text-white">Manage Donors</a></li>
                        <li><a href="manage_ngos.php" class="text-white">Manage NGOs</a></li>
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
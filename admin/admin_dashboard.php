<?php
session_start();
include '../includes/db.php';
include '../includes/auth_admin.php';

// Check if admin is logged in
check_admin_login();

$admin_id = get_admin_id();
$admin_username = get_admin_username();

// Get statistics
$total_donors = $conn->query("SELECT COUNT(*) as count FROM donors")->fetch_assoc()['count'];
$total_ngos = $conn->query("SELECT COUNT(*) as count FROM ngos")->fetch_assoc()['count'];
$total_donations = $conn->query("SELECT COUNT(*) as count FROM donations")->fetch_assoc()['count'];
$total_delivered = $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'delivered'")->fetch_assoc()['count'];

// Get monthly donations data for chart
$monthly_donations = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $month_name = date('M Y', strtotime("-$i months"));
    
    $result = $conn->query("SELECT COUNT(*) as count FROM donations WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'")->fetch_assoc();
    $monthly_donations[] = $result['count'];
    $months[] = $month_name;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Food Donation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a class="nav-link active" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_donors.php">Manage Donors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_ngos.php">Manage NGOs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="donations_report.php">Donations Report</a>
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
            <div class="col-md-12">
                <h1>Admin Dashboard</h1>
                <p class="lead">Welcome to the administration panel. Manage donors, NGOs, and view system analytics.</p>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card text-center analytics-card">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-green mb-2"></i>
                        <h3 class="text-green"><?php echo $total_donors; ?></h3>
                        <p>Registered Donors</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center analytics-card">
                    <div class="card-body">
                        <i class="fas fa-hand-holding-heart fa-2x text-green mb-2"></i>
                        <h3 class="text-green"><?php echo $total_ngos; ?></h3>
                        <p>Registered NGOs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center analytics-card">
                    <div class="card-body">
                        <i class="fas fa-utensils fa-2x text-green mb-2"></i>
                        <h3 class="text-green"><?php echo $total_donations; ?></h3>
                        <p>Total Donations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center analytics-card">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-green mb-2"></i>
                        <h3 class="text-green"><?php echo $total_delivered; ?></h3>
                        <p>Deliveries Made</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Donations</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="donationsChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Donation Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="200"></canvas>
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
                        <?php
                        // Get recent donations
                        $stmt = $conn->prepare("SELECT d.*, dr.name as donor_name, n.name as ngo_name FROM donations d JOIN donors dr ON d.donor_id = dr.id LEFT JOIN ngos n ON d.ngo_id = n.id ORDER BY d.created_at DESC LIMIT 10");
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
                                        <th>NGO</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($donation = $recent_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($donation['food_name']); ?></td>
                                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                                        <td><?php echo $donation['ngo_name'] ? htmlspecialchars($donation['ngo_name']) : 'Not assigned'; ?></td>
                                        <td>
                                            <span class="badge <?php echo get_status_badge_class($donation['status']); ?>">
                                                <?php echo get_status_text($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo format_date($donation['created_at']); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-center">No donations found.</p>
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
    <!-- Chart Initialization -->
    <script>
        // Monthly Donations Chart
        const donationsCtx = document.getElementById('donationsChart').getContext('2d');
        const donationsChart = new Chart(donationsCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Donations',
                    data: <?php echo json_encode($monthly_donations); ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Donation Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Accepted', 'Picked Up', 'Delivered'],
                datasets: [{
                    data: [
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'available'")->fetch_assoc()['count']; ?>,
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'accepted'")->fetch_assoc()['count']; ?>,
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'picked'")->fetch_assoc()['count']; ?>,
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM donations WHERE status = 'delivered'")->fetch_assoc()['count']; ?>
                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#007bff',
                        '#28a745'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
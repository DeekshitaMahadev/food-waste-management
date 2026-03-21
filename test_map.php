<?php
include 'includes/db.php';

// Test the map generation function
$test_address = "New York, NY, USA";
$map_url = generateMapEmbedUrl($test_address);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Test - Smart Food Donation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Map Functionality Test</h1>
        <p>This page tests the dynamic map generation functionality.</p>
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>Note About Location Detection</h5>
            <p>In the actual application, users can click the "Use Current Location" button to automatically detect their location using the browser's Geolocation API. This test page uses a static example address.</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Test Map for: <?php echo htmlspecialchars($test_address); ?></h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($test_address); ?></p>
                    <p><a href="https://www.openstreetmap.org/search?query=<?php echo urlencode($test_address); ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt me-2"></i>View on OpenStreetMap
                    </a></p>
                    <small class="form-text">Click to view this location on OpenStreetMap (opens in new tab)</small>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
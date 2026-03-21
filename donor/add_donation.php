<?php
session_start();
include '../includes/db.php';
include '../includes/auth_donor.php';
include '../includes/mailer.php';

// Check if donor is logged in
check_donor_login();

$donor_id = get_donor_id();
$donor_name = get_donor_name();

$error = '';
$success = '';

// Get donor's address for default location
$donor_stmt = $conn->prepare("SELECT address FROM donors WHERE id = ?");
$donor_stmt->bind_param("i", $donor_id);
$donor_stmt->execute();
$donor_result = $donor_stmt->get_result();
$donor_data = $donor_result->fetch_assoc();
$default_location = $donor_data['address'];
$donor_stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_name = sanitize_input($_POST['food_name']);
    $quantity = sanitize_input($_POST['quantity']);
    $expiry_date = sanitize_input($_POST['expiry_date']);
    $expiry_time = sanitize_input($_POST['expiry_time']);
    $location = sanitize_input($_POST['location']);
    
    // Combine date and time
    $expiry_datetime = $expiry_date . ' ' . $expiry_time;
    
    // Handle image upload
    $image_filename = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $image_filename = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $error = "File is not an image.";
        } else {
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Move uploaded file
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }
    }

    // Validation
    if (empty($food_name) || empty($quantity) || empty($expiry_date) || empty($expiry_time) || empty($location)) {
        $error = "All fields except image are required.";
    } elseif (strtotime($expiry_datetime) <= time()) {
        $error = "Expiry date/time must be in the future.";
    } else {
        // Insert donation
        $stmt = $conn->prepare("INSERT INTO donations (donor_id, food_name, quantity, expiry_time, image, location) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $donor_id, $food_name, $quantity, $expiry_datetime, $image_filename, $location);
        
        if ($stmt->execute()) {
            $success = "Donation added successfully!";
            
            // Send notification to all NGOs (in a real app, you might want to filter by location)
            $ngo_stmt = $conn->prepare("SELECT email, name FROM ngos");
            $ngo_stmt->execute();
            $ngo_result = $ngo_stmt->get_result();
            
            while ($ngo = $ngo_result->fetch_assoc()) {
                sendNewDonationNotification($ngo['email'], $ngo['name'], $donor_name, $food_name);
            }
            
            $ngo_stmt->close();
        } else {
            $error = "Error adding donation. Please try again.";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Donation - Smart Food Donation System</title>
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
                        <a class="nav-link active" href="add_donation.php">Add Donation</a>
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
                <h1>Add New Donation</h1>
                <p class="lead">Fill in the details of your food donation below.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="donor_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="donor_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Donation</li>
            </ol>
        </nav>
    </div>

    <!-- Add Donation Form -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-green text-white">
                        <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Donation Details</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                            <a href="view_status.php" class="alert-link">View your donations</a>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="food_name" class="form-label">Food Name</label>
                                <input type="text" class="form-control" id="food_name" name="food_name" value="<?php echo isset($_POST['food_name']) ? htmlspecialchars($_POST['food_name']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="e.g., 5 kg, 10 pieces, 2 boxes" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_time" class="form-label">Expiry Time</label>
                                    <input type="time" class="form-control" id="expiry_time" name="expiry_time" value="<?php echo isset($_POST['expiry_time']) ? htmlspecialchars($_POST['expiry_time']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Food Image (Optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Upload an image of the food donation (JPG, PNG, GIF).</div>
                                <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                    <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid" style="max-height: 200px;">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Pickup Location</label>
                                <textarea class="form-control" id="location" name="location" rows="3" required><?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : $default_location; ?></textarea>
                                <div class="form-text">Enter the full address where the food can be picked up.</div>
                                
                                <button type="button" class="btn btn-secondary mt-2" id="getCurrentLocation">
                                    <i class="fas fa-location-arrow me-2"></i>Use Current Location
                                </button>
                                
                                <!-- OpenStreetMap Link -->
                                <div class="map-container mt-2" id="mapContainer" style="display: <?php echo (isset($_POST['location']) && !empty($_POST['location'])) || !empty($default_location) ? 'block' : 'none'; ?>;">
                                    <div class="alert alert-info">
                                        <p><strong>Location:</strong> <span id="locationText"><?php echo htmlspecialchars(isset($_POST['location']) ? $_POST['location'] : $default_location); ?></span></p>
                                        <p><a href="#" target="_blank" class="btn btn-primary" id="viewMapBtn">
                                            <i class="fas fa-map-marked-alt me-2"></i>View on Map
                                        </a></p>
                                        <small class="form-text">Click to view this location on OpenStreetMap (opens in new tab)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-green">Add Donation</button>
                            </div>
                        </form>
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
    <script>
        // Preview image before upload
        document.getElementById('image').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImage = document.getElementById('imagePreview');
            
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(event.target.files[0]);
            }
        });
        
        // Get current location
        document.getElementById('getCurrentLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    // Show loading message
                    const locationTextarea = document.getElementById('location');
                    locationTextarea.value = 'Getting address from coordinates...';
                    
                    // Show map container
                    document.getElementById('mapContainer').style.display = 'block';
                    
                    // Use reverse geocoding to get address
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            const address = data.display_name || `${lat}, ${lon}`;
                            locationTextarea.value = address;
                            document.getElementById('locationText').textContent = address;
                            // Create a proper map link with marker
                            document.getElementById('viewMapBtn').href = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=16/${lat}/${lon}`;
                        })
                        .catch(error => {
                            console.error('Error getting address:', error);
                            locationTextarea.value = `${lat}, ${lon}`;
                            document.getElementById('locationText').textContent = `${lat}, ${lon}`;
                            // Fallback map link with marker
                            document.getElementById('viewMapBtn').href = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=16/${lat}/${lon}`;
                        });
                }, function(error) {
                    alert('Unable to get your location: ' + error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });
        
        // Update map link when location changes - for manual address entry
        document.getElementById('location').addEventListener('input', function() {
            const locationValue = this.value;
            if (locationValue.trim() !== '') {
                document.getElementById('mapContainer').style.display = 'block';
                document.getElementById('locationText').textContent = locationValue;
                // For manually entered addresses, we'll search on OSM
                document.getElementById('viewMapBtn').href = `https://www.openstreetmap.org/search?query=${encodeURIComponent(locationValue)}`;
            } else {
                document.getElementById('mapContainer').style.display = 'none';
            }
        });
        
        // Also update map link on page load if there's already a location
        window.addEventListener('DOMContentLoaded', function() {
            const locationValue = document.getElementById('location').value;
            if (locationValue.trim() !== '') {
                document.getElementById('mapContainer').style.display = 'block';
                document.getElementById('locationText').textContent = locationValue;
                // For existing addresses, we'll search on OSM
                document.getElementById('viewMapBtn').href = `https://www.openstreetmap.org/search?query=${encodeURIComponent(locationValue)}`;
            }
        });
    </script>
</body>
</html>
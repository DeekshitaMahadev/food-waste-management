<?php
// Test database connection
include 'includes/db.php';

echo "<h1>Database Connection Test</h1>";

// Test connection
if ($conn) {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test query
    $result = $conn->query("SELECT COUNT(*) as count FROM donors");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Donors table accessible: " . $row['count'] . " records found</p>";
    } else {
        echo "<p style='color: red;'>✗ Error querying donors table: " . $conn->error . "</p>";
    }
    
    $result = $conn->query("SELECT COUNT(*) as count FROM ngos");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ NGOs table accessible: " . $row['count'] . " records found</p>";
    } else {
        echo "<p style='color: red;'>✗ Error querying NGOs table: " . $conn->error . "</p>";
    }
    
    $result = $conn->query("SELECT COUNT(*) as count FROM donations");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Donations table accessible: " . $row['count'] . " records found</p>";
    } else {
        echo "<p style='color: red;'>✗ Error querying donations table: " . $conn->error . "</p>";
    }
    
    $result = $conn->query("SELECT COUNT(*) as count FROM admin");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p style='color: green;'>✓ Admin table accessible: " . $row['count'] . " records found</p>";
    } else {
        echo "<p style='color: red;'>✗ Error querying admin table: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Database connection failed: " . $conn->connect_error . "</p>";
}

echo "<p><a href='index.php'>Go to Homepage</a></p>";
?>
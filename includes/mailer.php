<?php
// PHPMailer setup for sending emails

// Include PHPMailer library
// Note: In a real implementation, you would need to install PHPMailer via Composer
// For this project, we'll simulate the functionality

// Function to send email notifications
function sendMail($to, $subject, $message) {
    // In a real implementation, you would use PHPMailer here
    // For this project, we'll just log the email details
    
    // Create email log directory if it doesn't exist
    $logDir = __DIR__ . '/../email_logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    // Log email details
    $logFile = $logDir . '/email_log.txt';
    $logEntry = date('Y-m-d H:i:s') . " | To: $to | Subject: $subject\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    
    // Return success (in real implementation, this would depend on actual sending)
    return true;
}

// Function to send notification when NGO accepts a food donation
function sendDonationAcceptanceNotification($donorEmail, $donorName, $ngoName, $foodName) {
    $subject = "Your Food Donation Has Been Accepted";
    $message = "
    <html>
    <head>
        <title>Donation Accepted</title>
    </head>
    <body>
        <h2>Hello $donorName,</h2>
        <p>We're happy to inform you that your food donation of <strong>$foodName</strong> has been accepted by <strong>$ngoName</strong>.</p>
        <p>They will contact you soon to arrange pickup.</p>
        <br>
        <p>Thank you for helping reduce food wastage!</p>
        <p>Smart Food Donation Team</p>
    </body>
    </html>
    ";
    
    return sendMail($donorEmail, $subject, $message);
}

// Function to send notification when NGO updates status to Delivered
function sendDeliveryConfirmationNotification($donorEmail, $donorName, $ngoName, $foodName) {
    $subject = "Your Food Donation Has Been Delivered";
    $message = "
    <html>
    <head>
        <title>Donation Delivered</title>
    </head>
    <body>
        <h2>Hello $donorName,</h2>
        <p>Great news! Your food donation of <strong>$foodName</strong> has been successfully delivered by <strong>$ngoName</strong>.</p>
        <p>Your contribution has helped feed those in need. Thank you for your generosity!</p>
        <br>
        <p>Thank you for helping reduce food wastage!</p>
        <p>Smart Food Donation Team</p>
    </body>
    </html>
    ";
    
    return sendMail($donorEmail, $subject, $message);
}

// Function to send notification to NGO when a new donation is posted
function sendNewDonationNotification($ngoEmail, $ngoName, $donorName, $foodName) {
    $subject = "New Food Donation Available";
    $message = "
    <html>
    <head>
        <title>New Donation Available</title>
    </head>
    <body>
        <h2>Hello $ngoName,</h2>
        <p>A new food donation of <strong>$foodName</strong> has been posted by <strong>$donorName</strong>.</p>
        <p>Please log in to your dashboard to view and accept this donation.</p>
        <br>
        <p>Smart Food Donation Team</p>
    </body>
    </html>
    ";
    
    return sendMail($ngoEmail, $subject, $message);
}
?>
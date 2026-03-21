# Smart Food Wastage Donation & Tracking System

A complete full-stack web application for managing food donations, connecting donors with NGOs to reduce food waste and fight hunger.

## Features

### Donor Panel
- Donor Registration & Login
- Add food donations with details (name, quantity, expiry time, image)
- Auto location via Google Maps embed
- View donation status
- Email notifications when NGO accepts request
- Clean dashboard with statistics

### NGO Panel
- NGO Login
- List all available donations
- View detailed location using Google Maps
- Accept donations
- Update status (Picked → Delivered)
- Email notifications sent to donor
- View donation history

### Admin Panel
- Admin Login
- Manage donors
- Manage NGOs
- View all donations
- Delete fake or expired posts
- Display analytics with Chart.js (donations count, NGOs active, food saved)

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **APIs**: Google Maps Embed API, PHPMailer (simulated)

## Folder Structure

```
/food_donation_system
   /css
       styles.css
   /js
       script.js
       countdown.js
   /uploads
       (store uploaded images)
   /includes
       db.php
       auth_donor.php
       auth_ngo.php
       auth_admin.php
       mailer.php
       logout.php
   /donor
       donor_register.php
       donor_login.php
       donor_dashboard.php
       add_donation.php
       view_status.php
   /ngo
       ngo_login.php
       ngo_dashboard.php
       view_donations.php
   /admin
       admin_login.php
       admin_dashboard.php
       manage_donors.php
       manage_ngos.php
       donations_report.php
   index.php
   about.php
   contact.php
   food_donation_system.sql
```

## Setup Instructions

1. **Database Setup**:
   - Import the `food_donation_system.sql` file into your MySQL database
   - This will create the database and all required tables with sample data

2. **Web Server Setup**:
   - Place all files in your web server directory (e.g., XAMPP's htdocs folder)
   - Ensure your web server (Apache) and MySQL are running

3. **Database Configuration**:
   - Update database credentials in `includes/db.php` if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'food_donation_system');
     ```

4. **Email Configuration**:
   - The system simulates email functionality by logging emails to `email_logs/email_log.txt`
   - To enable real email functionality, integrate PHPMailer library properly

5. **Access the Application**:
   - Navigate to `http://localhost/food_donation_system/` in your browser

## Default Login Credentials

### Donor
- Email: john@example.com
- Password: password

### NGO
- Email: foodrelief@example.com
- Password: password

### Admin
- Username: admin
- Password: password

## Key Features Implemented

1. **Email Notification System**:
   - Simulated email functionality with logging
   - Notifications sent when NGO accepts donation
   - Notifications sent when NGO updates status to Delivered

2. **Google Maps Location Integration**:
   - Auto-location selection via Google Maps embed iframe
   - Location stored in database
   - Map display in donation details

3. **Live Expiry Countdown Timer**:
   - JavaScript-based countdown until expiry_time
   - Shows "Expires in X days/hours/minutes"
   - Updates dynamically on dashboard and detail pages

4. **Image Upload for Food Donations**:
   - File upload functionality for donation images
   - Images stored in `/uploads` directory
   - Preview before upload

5. **Multi-User Authentication**:
   - Separate panels for Donors, NGOs, and Admin
   - Session-based authentication
   - Role-specific access control

6. **Admin Analytics with Chart.js**:
   - Monthly donations chart (line chart)
   - Donation status distribution (doughnut chart)
   - System statistics dashboard

## Security Features

- Password hashing using PHP's `password_hash()` function
- Input sanitization and validation
- Prepared statements to prevent SQL injection
- Session management for authentication
- Role-based access control

## Customization

To customize the Google Maps integration:
1. Replace the iframe `src` attribute in the relevant files with your own Google Maps embed URL
2. Update the location data as needed

To enable real email functionality:
1. Install PHPMailer via Composer or manually
2. Update the `includes/mailer.php` file with your SMTP settings
3. Replace the simulated `sendMail()` function with actual PHPMailer implementation

## Notes

- The application is designed to run on XAMPP or similar local development environments
- All passwords in the sample data are hashed versions of "password"
- Uploaded images are stored in the `/uploads` directory
- Email logs are stored in the `/email_logs` directory (created automatically)
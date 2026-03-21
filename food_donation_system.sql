-- COMPLETE SQL EXPORT FOR FOOD DONATION SYSTEM

-- Create database
CREATE DATABASE IF NOT EXISTS food_donation_system;
USE food_donation_system;

-- Table structure for donors
CREATE TABLE IF NOT EXISTS donors (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for ngos
CREATE TABLE IF NOT EXISTS ngos (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  registration_number VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for admin
CREATE TABLE IF NOT EXISTS admin (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for donations
CREATE TABLE IF NOT EXISTS donations (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  donor_id INT(11) NOT NULL,
  food_name VARCHAR(100) NOT NULL,
  quantity VARCHAR(100) NOT NULL,
  expiry_time DATETIME NOT NULL,
  image VARCHAR(255),
  location TEXT NOT NULL,
  status ENUM('available', 'accepted', 'picked', 'delivered') DEFAULT 'available',
  ngo_id INT(11) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
  FOREIGN KEY (ngo_id) REFERENCES ngos(id) ON DELETE SET NULL
);

-- Insert sample data for donors
INSERT INTO donors (name, email, phone, password, address) VALUES
('John Smith', 'john@example.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Main St, New York, NY'),
('Alice Johnson', 'alice@example.com', '2345678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Oak Ave, Los Angeles, CA'),
('Robert Brown', 'robert@example.com', '3456789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Pine Rd, Chicago, IL'),
('Emily Davis', 'emily@example.com', '4567890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Elm St, Houston, TX'),
('Michael Wilson', 'michael@example.com', '5678901234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '654 Maple Dr, Phoenix, AZ');

-- Insert sample data for ngos
INSERT INTO ngos (name, email, phone, password, address, registration_number) VALUES
('Food Relief Foundation', 'foodrelief@example.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '100 Charity Ln, New York, NY', 'NGO-001'),
('Helping Hands', 'helpinghands@example.com', '8765432109', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '200 Giving Way, Los Angeles, CA', 'NGO-002'),
('Community Care', 'communitycare@example.com', '7654321098', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '300 Support Blvd, Chicago, IL', 'NGO-003'),
('Hope Network', 'hopenetwork@example.com', '6543210987', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '400 Aid St, Houston, TX', 'NGO-004'),
('Feeding America', 'feedingamerica@example.com', '5432109876', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '500 Nourish Ave, Phoenix, AZ', 'NGO-005');

-- Insert sample data for admin
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample data for donations
INSERT INTO donations (donor_id, food_name, quantity, expiry_time, image, location, status, ngo_id) VALUES
(1, 'Bread', '10 loaves', '2025-12-05 14:30:00', 'bread.jpg', '123 Main St, New York, NY', 'available', NULL),
(2, 'Vegetables', '5 kg', '2025-12-03 18:00:00', 'vegetables.jpg', '456 Oak Ave, Los Angeles, CA', 'accepted', 1),
(3, 'Rice', '20 kg', '2025-12-10 12:00:00', 'rice.jpg', '789 Pine Rd, Chicago, IL', 'picked', 2),
(4, 'Fruits', '15 kg', '2025-12-02 09:00:00', 'fruits.jpg', '321 Elm St, Houston, TX', 'delivered', 3),
(5, 'Canned Goods', '25 items', '2025-12-08 16:45:00', 'canned_goods.jpg', '654 Maple Dr, Phoenix, AZ', 'available', NULL);
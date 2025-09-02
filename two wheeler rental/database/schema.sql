-- database/schema.sql
-- SQL schema for the Two-Wheeler Rental System

CREATE DATABASE IF NOT EXISTS `two_wheeler_rental`;
USE `two_wheeler_rental`;

-- Table for Users
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Hashed password
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Bikes
CREATE TABLE IF NOT EXISTS `bikes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `make` VARCHAR(255) NOT NULL,
    `model` VARCHAR(255) NOT NULL,
    `year` INT,
    `license_plate` VARCHAR(50) UNIQUE NOT NULL,
    `rental_price_per_day` DECIMAL(10, 2) NOT NULL,
    `availability_status` ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    `image_url` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for Bookings
CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `bike_id` INT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_price` DECIMAL(10, 2) NOT NULL,
    `booking_status` ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`bike_id`) REFERENCES `bikes`(`id`) ON DELETE CASCADE
);

-- Optional: Add some initial data for testing
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyzaabcdefghijklmnopqrstuvwxyza', 'admin'), -- Replace with a real hashed password for 'password'
('Test User', 'user@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyzaabcdefghijklmnopqrstuvwxyza', 'user'); -- Replace with a real hashed password for 'password'

INSERT INTO `bikes` (`make`, `model`, `year`, `license_plate`, `rental_price_per_day`, `availability_status`) VALUES
('Honda', 'CBR500R', 2022, 'DL01AB1234', 50.00, 'available'),
('Yamaha', 'MT-07', 2021, 'DL02CD5678', 60.00, 'available'),
('Kawasaki', 'Ninja 400', 2023, 'UP16EF9012', 55.00, 'maintenance');

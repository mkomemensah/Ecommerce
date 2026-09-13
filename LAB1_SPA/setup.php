<?php

// I am connecting to the MySQL database provided by the school.
$host = "localhost";
$db_user = "maame.kome-mensah";
$db_pass = "your password";
$db_name = "ecommerce_2026A_maame_kome-mensah";

// I am creating the connection between my website and MySQL.
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// I want the website to stop if the database connection fails.
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// I am using UTF-8 so names and other text display correctly.
$conn->set_charset("utf8mb4");

// I am creating the services table.
// This table stores the different treatments offered by the spa.
$services_sql = "CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL DEFAULT 60,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($services_sql)) {
    die("Error creating services table: " . $conn->error);
}

// I am creating the bookings table.
// The service_id connects each booking to a service.
$bookings_sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    service_id INT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (service_id)
        REFERENCES services(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
)";

if (!$conn->query($bookings_sql)) {
    die("Error creating bookings table: " . $conn->error);
}

// I am displaying a confirmation so I know
// that my tables were created successfully.
echo "<h1>Serenity Spa setup is complete.</h1>";
echo "<p>The services and bookings tables are ready.</p>";
echo "<p><a href='index.php'>Go to Serenity Spa</a></p>";

$conn->close();

?>
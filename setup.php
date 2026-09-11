<?php

require "db.php";

$sql = "CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Setup complete!</h2>";
    echo "<p><a href='index.php'>Go to Spa Services App</a></p>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
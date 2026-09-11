<?php

require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $status = $_POST["status"];

    $stmt = $conn->prepare(
        "INSERT INTO services (name, description, price, status)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssds", $name, $description, $price, $status);

    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Service</title>
</head>

<body>

<h2>Add Spa Service</h2>

<form method="POST" action="create.php">

    <label>Service Name</label><br>
    <input type="text" name="name" required>

    <br><br>

    <label>Description</label><br>
    <textarea name="description"></textarea>

    <br><br>

    <label>Price (GHS)</label><br>
    <input type="number" name="price" step="0.01" min="0" required>

    <br><br>

    <label>Status</label><br>

    <select name="status">
        <option value="available">Available</option>
        <option value="unavailable">Unavailable</option>
    </select>

    <br><br>

    <button type="submit">Save Service</button>

</form>

<br>

<a href="index.php">Back</a>

</body>

</html>
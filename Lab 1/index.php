<?php

require "db.php";

$result = $conn->query("SELECT * FROM services ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Spa Services</title>
</head>

<body>

<h1>Spa Services Manager</h1>

<a href="create.php">+ Add Service</a>

<?php while ($row = $result->fetch_assoc()): ?>

<div>

    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <p>
        <?php echo htmlspecialchars($row['description']); ?>
    </p>

    <p>
        Price: GHS <?php echo number_format($row['price'], 2); ?>
    </p>

    <p>
        Status: <?php echo htmlspecialchars($row['status']); ?>
    </p>

    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>

    <a href="delete.php?id=<?php echo $row['id']; ?>"
       onclick="return confirm('Delete this service?');">
        Delete
    </a>

</div>

<?php endwhile; ?>

</body>

</html>

<?php

$conn->close();

?>
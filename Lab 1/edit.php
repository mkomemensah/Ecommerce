<?php

require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $status = $_POST["status"];
    $post_id = intval($_POST["id"]);

    $stmt = $conn->prepare(
        "UPDATE services
         SET name=?, description=?, price=?, status=?
         WHERE id=?"
    );

    $stmt->bind_param(
        "ssdsi",
        $name,
        $description,
        $price,
        $status,
        $post_id
    );

    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$service = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$service) {
    die("Service not found.");
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Service</title>
</head>

<body>

<h2>Edit Spa Service</h2>

<form method="POST" action="edit.php">

    <input
        type="hidden"
        name="id"
        value="<?php echo $service['id']; ?>"
    >

    <label>Service Name</label><br>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($service['name']); ?>"
        required
    >

    <br><br>

    <label>Description</label><br>

    <textarea name="description"><?php echo htmlspecialchars($service['description']); ?></textarea>

    <br><br>

    <label>Price (GHS)</label><br>

    <input
        type="number"
        name="price"
        value="<?php echo htmlspecialchars($service['price']); ?>"
        step="0.01"
        min="0"
        required
    >

    <br><br>

    <label>Status</label><br>

    <select name="status">

        <option
            value="available"
            <?php echo $service['status'] === 'available' ? 'selected' : ''; ?>
        >
            Available
        </option>

        <option
            value="unavailable"
            <?php echo $service['status'] === 'unavailable' ? 'selected' : ''; ?>
        >
            Unavailable
        </option>

    </select>

    <br><br>

    <button type="submit">Update Service</button>

</form>

<br>

<a href="index.php">Back</a>

</body>

</html>
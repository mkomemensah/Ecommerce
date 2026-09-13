<?php

require "db.php";

// I am getting the service ID from the URL.
$id = (int) ($_GET["id"] ?? 0);

if ($id > 0) {

    // I am using a prepared statement so only the selected
    // service is deleted.
    $stmt = $conn->prepare(
        "DELETE FROM services WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}

$conn->close();

// I am returning to the services page after deleting.
header("Location: services.php");
exit;

?>
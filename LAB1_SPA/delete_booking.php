<?php

require "db.php";

// I am getting the booking ID from the URL.
$id = (int) ($_GET["id"] ?? 0);

if ($id > 0) {

    // I am deleting only the booking that matches this ID.
    $stmt = $conn->prepare(
        "DELETE FROM bookings WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}

$conn->close();

// I am returning to the bookings page after deleting.
header("Location: bookings.php");
exit;

?>
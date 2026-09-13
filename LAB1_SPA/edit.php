<?php

require "db.php";

$page_title = "Edit Service | Serenity Spa";

$error = "";

// I am getting the service ID from the URL so I know
// which service I want to edit.
$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);

if ($id <= 0) {
    die("Invalid service ID.");
}


// I am handling the form when the user submits an update.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $duration = (int) $_POST["duration"];
    $status = $_POST["status"];

    if ($name === "" || $price < 0 || $duration <= 0) {

        $error = "Please enter valid service information.";

    } else {

        // I am updating the selected service using a prepared statement.
        $stmt = $conn->prepare(
            "UPDATE services
             SET name = ?,
                 description = ?,
                 price = ?,
                 duration = ?,
                 status = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssdisi",
            $name,
            $description,
            $price,
            $duration,
            $status,
            $id
        );

        if ($stmt->execute()) {

            $stmt->close();
            $conn->close();

            header("Location: services.php");
            exit;

        } else {

            $error = "Something went wrong while updating the service.";

            $stmt->close();
        }
    }
}


// I am retrieving the current information for this service
// so I can display it inside the form.
$stmt = $conn->prepare(
    "SELECT * FROM services WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$service = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$service) {
    die("Service not found.");
}

include "includes/header.php";

?>

<section class="page-hero small-page-hero">

    <div class="container">

        <p class="eyebrow">SERVICE MANAGEMENT</p>

        <h1>Edit Service</h1>

        <p>
            Update the information for this treatment.
        </p>

    </div>

</section>


<section class="form-section">

    <div class="container narrow-container">

        <div class="form-card">

            <?php if ($error !== ""): ?>

                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="edit.php">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $service["id"]; ?>"
                >


                <div class="form-group">

                    <label for="name">Service Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?php echo htmlspecialchars($service["name"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">Description</label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                    ><?php echo htmlspecialchars($service["description"]); ?></textarea>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="price">Price (GHS)</label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="<?php echo htmlspecialchars($service["price"]); ?>"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="duration">Duration (minutes)</label>

                        <input
                            type="number"
                            id="duration"
                            name="duration"
                            value="<?php echo htmlspecialchars($service["duration"]); ?>"
                            min="15"
                            step="15"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="status">Status</label>

                    <select id="status" name="status">

                        <option
                            value="available"
                            <?php echo $service["status"] === "available" ? "selected" : ""; ?>
                        >
                            Available
                        </option>

                        <option
                            value="unavailable"
                            <?php echo $service["status"] === "unavailable" ? "selected" : ""; ?>
                        >
                            Unavailable
                        </option>

                    </select>

                </div>


                <div class="form-actions">

                    <a href="services.php" class="button button-outline">
                        Cancel
                    </a>

                    <button type="submit" class="button button-primary">
                        Update Service
                    </button>

                </div>

            </form>

        </div>

    </div>

</section>


<?php

$conn->close();

include "includes/footer.php";

?>
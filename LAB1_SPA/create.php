<?php

require "db.php";

$page_title = "Add Service | Serenity Spa";

$error = "";

// I am checking whether the service form has been submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $duration = (int) $_POST["duration"];
    $status = $_POST["status"];

    // I am making sure the important information is valid.
    if ($name === "" || $price < 0 || $duration <= 0) {

        $error = "Please enter valid service information.";

    } else {

        // I am using a prepared statement to safely insert
        // the new service into my database.
        $stmt = $conn->prepare(
            "INSERT INTO services
            (name, description, price, duration, status)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdis",
            $name,
            $description,
            $price,
            $duration,
            $status
        );

        if ($stmt->execute()) {

            $stmt->close();
            $conn->close();

            header("Location: services.php");
            exit;

        } else {

            $error = "Something went wrong while saving the service.";

            $stmt->close();
        }
    }
}

include "includes/header.php";

?>

<section class="page-hero small-page-hero">

    <div class="container">

        <p class="eyebrow">SERVICE MANAGEMENT</p>

        <h1>Add a New Service</h1>

        <p>
            Add a treatment that customers can select when making
            an appointment.
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


            <form method="POST" action="create.php">

                <div class="form-group">

                    <label for="name">Service Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="e.g. Relaxation Massage"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="description">Description</label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Describe what the treatment includes..."
                    ></textarea>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="price">Price (GHS)</label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            min="0"
                            step="0.01"
                            placeholder="250.00"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="duration">Duration (minutes)</label>

                        <input
                            type="number"
                            id="duration"
                            name="duration"
                            min="15"
                            step="15"
                            value="60"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="status">Status</label>

                    <select id="status" name="status">

                        <option value="available">
                            Available
                        </option>

                        <option value="unavailable">
                            Unavailable
                        </option>

                    </select>

                </div>


                <div class="form-actions">

                    <a href="services.php" class="button button-outline">
                        Cancel
                    </a>

                    <button type="submit" class="button button-primary">
                        Save Service
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
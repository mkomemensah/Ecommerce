<?php

require "db.php";

$page_title = "Our Services | Serenity Spa";

// I am retrieving all of my services from the database.
$result = $conn->query(
    "SELECT * FROM services
     ORDER BY created_at DESC"
);

include "includes/header.php";

?>

<section class="page-hero">

    <div class="container">

        <p class="eyebrow">OUR SERVICES</p>

        <h1>Treat yourself to some time out.</h1>

        <p>
            Explore our treatments and choose the one that feels
            right for your next visit.
        </p>

    </div>

</section>


<section class="content-section">

    <div class="container">

        <div class="admin-toolbar">

            <div>

                <p class="eyebrow">SERVICE MANAGEMENT</p>

                <h2>All Spa Services</h2>

            </div>

            <a href="create.php" class="button button-primary">
                + Add Service
            </a>

        </div>


        <div class="service-grid">

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($service = $result->fetch_assoc()): ?>

                    <article class="service-card service-card-full">

                        <div class="service-card-top">

                            <span class="service-number">
                                <?php echo str_pad($service["id"], 2, "0", STR_PAD_LEFT); ?>
                            </span>

                            <?php if ($service["status"] === "available"): ?>

                                <span class="status available">
                                    Available
                                </span>

                            <?php else: ?>

                                <span class="status unavailable">
                                    Unavailable
                                </span>

                            <?php endif; ?>

                        </div>

                        <h3>
                            <?php echo htmlspecialchars($service["name"]); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($service["description"]); ?>
                        </p>

                        <div class="service-details">

                            <span>
                                <?php echo (int) $service["duration"]; ?> minutes
                            </span>

                            <strong>
                                GHS <?php echo number_format($service["price"], 2); ?>
                            </strong>

                        </div>

                        <div class="card-actions">

                            <a
                                href="edit.php?id=<?php echo $service["id"]; ?>"
                                class="button button-small button-outline"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?php echo $service["id"]; ?>"
                                class="button button-small button-danger"
                                onclick="return confirm('Are you sure you want to delete this service?');"
                            >
                                Delete
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty-state">

                    <h3>No services yet.</h3>

                    <p>
                        Add your first spa service to get started.
                    </p>

                    <a href="create.php" class="button button-primary">
                        Add Service
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<?php

$conn->close();

include "includes/footer.php";

?>
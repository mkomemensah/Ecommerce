<?php

require "db.php";

$page_title = "Bookings | Serenity Spa";

// I am joining bookings with services so I can display
// the actual service name instead of only the service ID.
$result = $conn->query(
    "SELECT
        bookings.*,
        services.name AS service_name
     FROM bookings
     LEFT JOIN services
        ON bookings.service_id = services.id
     ORDER BY appointment_date ASC, appointment_time ASC"
);

include "includes/header.php";

?>

<section class="page-hero small-page-hero">

    <div class="container">

        <p class="eyebrow">BOOKING MANAGEMENT</p>

        <h1>Appointment Bookings</h1>

        <p>
            View and manage appointments submitted through the website.
        </p>

    </div>

</section>


<section class="content-section">

    <div class="container">

        <div class="admin-toolbar">

            <div>

                <p class="eyebrow">APPOINTMENTS</p>

                <h2>All Bookings</h2>

            </div>

            <a href="booking.php" class="button button-primary">
                + New Booking
            </a>

        </div>


        <?php if ($result && $result->num_rows > 0): ?>

            <div class="booking-table-wrapper">

                <table class="booking-table">

                    <thead>

                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($booking = $result->fetch_assoc()): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?php echo htmlspecialchars($booking["customer_name"]); ?>
                                    </strong>

                                    <small>
                                        <?php echo htmlspecialchars($booking["customer_email"]); ?>
                                    </small>

                                    <small>
                                        <?php echo htmlspecialchars($booking["customer_phone"]); ?>
                                    </small>

                                </td>


                                <td>

                                    <?php
                                    echo $booking["service_name"]
                                        ? htmlspecialchars($booking["service_name"])
                                        : "Service removed";
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo date(
                                        "M d, Y",
                                        strtotime($booking["appointment_date"])
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo date(
                                        "g:i A",
                                        strtotime($booking["appointment_time"])
                                    );
                                    ?>

                                </td>


                                <td>

                                    <span class="status status-<?php echo htmlspecialchars($booking["status"]); ?>">

                                        <?php
                                        echo ucfirst(
                                            htmlspecialchars($booking["status"])
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="table-actions">

                                        <a
                                            href="edit_booking.php?id=<?php echo $booking["id"]; ?>"
                                            class="text-link"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="delete_booking.php?id=<?php echo $booking["id"]; ?>"
                                            class="text-link danger-link"
                                            onclick="return confirm('Are you sure you want to delete this booking?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <h3>No bookings yet.</h3>

                <p>
                    Appointments submitted through the booking form
                    will appear here.
                </p>

                <a href="booking.php" class="button button-primary">
                    Create a Booking
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


<?php

$conn->close();

include "includes/footer.php";

?>
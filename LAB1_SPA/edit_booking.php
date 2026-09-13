<?php

require "db.php";

$page_title = "Edit Booking | Serenity Spa";

$error = "";

// I am getting the booking ID so I know which appointment
// I am editing.
$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);

if ($id <= 0) {
    die("Invalid booking ID.");
}


// I am retrieving the available services for the dropdown.
$services_result = $conn->query(
    "SELECT id, name, price, duration
     FROM services
     WHERE status = 'available'
     ORDER BY name ASC"
);


// I am checking whether the edit form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer_name = trim($_POST["customer_name"]);
    $customer_email = trim($_POST["customer_email"]);
    $customer_phone = trim($_POST["customer_phone"]);
    $service_id = (int) $_POST["service_id"];
    $appointment_date = $_POST["appointment_date"];
    $appointment_time = $_POST["appointment_time"];
    $notes = trim($_POST["notes"]);
    $status = $_POST["status"];


    if (
        $customer_name === "" ||
        !filter_var($customer_email, FILTER_VALIDATE_EMAIL) ||
        $customer_phone === "" ||
        $service_id <= 0 ||
        $appointment_date === "" ||
        $appointment_time === ""
    ) {

        $error = "Please complete all required booking fields.";

    } elseif ($appointment_date < date("Y-m-d")) {

        $error = "Please choose a current or future appointment date.";

    } else {

        // I am checking whether another booking already exists
        // at the selected date and time.
        $check = $conn->prepare(
            "SELECT id
             FROM bookings
             WHERE appointment_date = ?
             AND appointment_time = ?
             AND status IN ('pending', 'confirmed')
             AND id != ?
             LIMIT 1"
        );

        $check->bind_param(
            "ssi",
            $appointment_date,
            $appointment_time,
            $id
        );

        $check->execute();

        $existing_booking = $check->get_result()->fetch_assoc();

        $check->close();


        if ($existing_booking) {

            $error = "That appointment time has already been booked.";

        } else {

            // I am updating the booking with the new information.
            $stmt = $conn->prepare(
                "UPDATE bookings
                 SET customer_name = ?,
                     customer_email = ?,
                     customer_phone = ?,
                     service_id = ?,
                     appointment_date = ?,
                     appointment_time = ?,
                     notes = ?,
                     status = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "sssissssi",
                $customer_name,
                $customer_email,
                $customer_phone,
                $service_id,
                $appointment_date,
                $appointment_time,
                $notes,
                $status,
                $id
            );


            if ($stmt->execute()) {

                $stmt->close();
                $conn->close();

                header("Location: bookings.php");
                exit;

            } else {

                $error = "Something went wrong while updating the booking.";

                $stmt->close();
            }
        }
    }
}


// I am retrieving the existing booking so I can
// display its current information in the form.
$stmt = $conn->prepare(
    "SELECT * FROM bookings WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$booking = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$booking) {
    die("Booking not found.");
}

include "includes/header.php";

?>

<section class="page-hero small-page-hero">

    <div class="container">

        <p class="eyebrow">BOOKING MANAGEMENT</p>

        <h1>Edit Appointment</h1>

        <p>
            Update the customer's appointment information.
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


            <form method="POST" action="edit_booking.php">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $booking["id"]; ?>"
                >


                <div class="form-section-heading">

                    <p class="eyebrow">CUSTOMER DETAILS</p>

                    <h2>Appointment Information</h2>

                </div>


                <div class="form-group">

                    <label for="customer_name">Full Name</label>

                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        value="<?php echo htmlspecialchars($booking["customer_name"]); ?>"
                        required
                    >

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="customer_email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="customer_email"
                            name="customer_email"
                            value="<?php echo htmlspecialchars($booking["customer_email"]); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="customer_phone">
                            Phone Number
                        </label>

                        <input
                            type="tel"
                            id="customer_phone"
                            name="customer_phone"
                            value="<?php echo htmlspecialchars($booking["customer_phone"]); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-section-heading booking-heading">

                    <p class="eyebrow">APPOINTMENT</p>

                    <h2>Update the booking.</h2>

                </div>


                <div class="form-group">

                    <label for="service_id">Service</label>

                    <select id="service_id" name="service_id" required>

                        <option value="">
                            Select a service
                        </option>

                        <?php if ($services_result): ?>

                            <?php while ($service = $services_result->fetch_assoc()): ?>

                                <option
                                    value="<?php echo $service["id"]; ?>"
                                    <?php echo $booking["service_id"] == $service["id"] ? "selected" : ""; ?>
                                >
                                    <?php echo htmlspecialchars($service["name"]); ?>
                                    -
                                    GHS <?php echo number_format($service["price"], 2); ?>
                                    -
                                    <?php echo (int) $service["duration"]; ?> min
                                </option>

                            <?php endwhile; ?>

                        <?php endif; ?>

                    </select>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="appointment_date">
                            Appointment Date
                        </label>

                        <input
                            type="date"
                            id="appointment_date"
                            name="appointment_date"
                            min="<?php echo date("Y-m-d"); ?>"
                            value="<?php echo htmlspecialchars($booking["appointment_date"]); ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="appointment_time">
                            Appointment Time
                        </label>

                        <input
                            type="time"
                            id="appointment_time"
                            name="appointment_time"
                            min="09:00"
                            max="19:00"
                            value="<?php echo htmlspecialchars($booking["appointment_time"]); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="status">Booking Status</label>

                    <select id="status" name="status">

                        <option
                            value="pending"
                            <?php echo $booking["status"] === "pending" ? "selected" : ""; ?>
                        >
                            Pending
                        </option>

                        <option
                            value="confirmed"
                            <?php echo $booking["status"] === "confirmed" ? "selected" : ""; ?>
                        >
                            Confirmed
                        </option>

                        <option
                            value="completed"
                            <?php echo $booking["status"] === "completed" ? "selected" : ""; ?>
                        >
                            Completed
                        </option>

                        <option
                            value="cancelled"
                            <?php echo $booking["status"] === "cancelled" ? "selected" : ""; ?>
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="notes">Additional Notes</label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                    ><?php echo htmlspecialchars($booking["notes"]); ?></textarea>

                </div>


                <div class="form-actions">

                    <a href="bookings.php" class="button button-outline">
                        Cancel
                    </a>

                    <button type="submit" class="button button-primary">
                        Update Booking
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
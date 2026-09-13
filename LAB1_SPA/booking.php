<?php

require "db.php";

$page_title = "Book an Appointment | Serenity Spa";

$error = "";
$success = "";

// I am retrieving all available services so customers
// can choose one from the booking form.
$services_result = $conn->query(
    "SELECT id, name, price, duration
     FROM services
     WHERE status = 'available'
     ORDER BY name ASC"
);


// I am checking whether the booking form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer_name = trim($_POST["customer_name"]);
    $customer_email = trim($_POST["customer_email"]);
    $customer_phone = trim($_POST["customer_phone"]);
    $service_id = (int) $_POST["service_id"];
    $appointment_date = $_POST["appointment_date"];
    $appointment_time = $_POST["appointment_time"];
    $notes = trim($_POST["notes"]);


    // I am checking that the required information was provided.
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

        $error = "Please choose a future appointment date.";

    } else {

        // I am checking whether another active booking already
        // exists at the selected date and time.
        $check = $conn->prepare(
            "SELECT id
             FROM bookings
             WHERE appointment_date = ?
             AND appointment_time = ?
             AND status IN ('pending', 'confirmed')
             LIMIT 1"
        );

        $check->bind_param(
            "ss",
            $appointment_date,
            $appointment_time
        );

        $check->execute();

        $existing_booking = $check->get_result()->fetch_assoc();

        $check->close();


        if ($existing_booking) {

            $error = "That appointment time has already been booked. Please choose another time.";

        } else {

            // I am saving the new booking in my database.
            $stmt = $conn->prepare(
                "INSERT INTO bookings
                (
                    customer_name,
                    customer_email,
                    customer_phone,
                    service_id,
                    appointment_date,
                    appointment_time,
                    notes
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssisss",
                $customer_name,
                $customer_email,
                $customer_phone,
                $service_id,
                $appointment_date,
                $appointment_time,
                $notes
            );


            if ($stmt->execute()) {

                $success = "Your appointment request has been submitted. We will confirm your booking shortly.";

                // I am clearing the submitted values after
                // the booking has been successfully saved.
                $_POST = [];

            } else {

                $error = "Something went wrong while creating your booking.";
            }

            $stmt->close();
        }
    }
}

include "includes/header.php";

?>

<section class="page-hero small-page-hero">

    <div class="container">

        <p class="eyebrow">BOOK WITH US</p>

        <h1>Make time for yourself.</h1>

        <p>
            Choose your treatment and a convenient appointment time.
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


            <?php if ($success !== ""): ?>

                <div class="alert alert-success">

                    <strong>Booking received.</strong>

                    <p>
                        <?php echo htmlspecialchars($success); ?>
                    </p>

                    <a href="index.php" class="text-link">
                        Return to homepage →
                    </a>

                </div>

            <?php endif; ?>


            <form method="POST" action="booking.php">

                <div class="form-section-heading">

                    <p class="eyebrow">YOUR DETAILS</p>

                    <h2>Tell us a little about you.</h2>

                </div>


                <div class="form-group">

                    <label for="customer_name">Full Name</label>

                    <input
                        type="text"
                        id="customer_name"
                        name="customer_name"
                        value="<?php echo htmlspecialchars($_POST["customer_name"] ?? ""); ?>"
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
                            value="<?php echo htmlspecialchars($_POST["customer_email"] ?? ""); ?>"
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
                            value="<?php echo htmlspecialchars($_POST["customer_phone"] ?? ""); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-section-heading booking-heading">

                    <p class="eyebrow">YOUR APPOINTMENT</p>

                    <h2>Choose your treatment.</h2>

                </div>


                <div class="form-group">

                    <label for="service_id">Service</label>

                    <select id="service_id" name="service_id" required>

                        <option value="">
                            Select a service
                        </option>

                        <?php if ($services_result && $services_result->num_rows > 0): ?>

                            <?php while ($service = $services_result->fetch_assoc()): ?>

                                <option
                                    value="<?php echo $service["id"]; ?>"
                                    <?php
                                    echo isset($_POST["service_id"])
                                        && $_POST["service_id"] == $service["id"]
                                        ? "selected"
                                        : "";
                                    ?>
                                >
                                    <?php echo htmlspecialchars($service["name"]); ?>
                                    -
                                    GHS <?php echo number_format($service["price"], 2); ?>
                                    -
                                    <?php echo (int) $service["duration"]; ?> min
                                </option>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <option value="" disabled>
                                No services are currently available
                            </option>

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
                            value="<?php echo htmlspecialchars($_POST["appointment_date"] ?? ""); ?>"
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
                            value="<?php echo htmlspecialchars($_POST["appointment_time"] ?? ""); ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="notes">
                        Additional Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="5"
                        placeholder="Anything you would like us to know?"
                    ><?php echo htmlspecialchars($_POST["notes"] ?? ""); ?></textarea>

                </div>


                <button
                    type="submit"
                    class="button button-primary button-full"
                >
                    Request Appointment
                </button>

            </form>

        </div>

    </div>

</section>


<?php

$conn->close();

include "includes/footer.php";

?>
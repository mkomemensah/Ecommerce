<?php

require "db.php";

$page_title = "Serenity Spa | Home";

// I am getting the first few available services from my database
// so visitors can immediately see what the spa offers.
$result = $conn->query(
    "SELECT * FROM services
     WHERE status = 'available'
     ORDER BY created_at DESC
     LIMIT 3"
);

include "includes/header.php";

?>

<section class="hero">

    <div class="container hero-content">

        <div class="hero-text">

            <p class="eyebrow">WELCOME TO SERENITY</p>

            <h1>
                A little time for yourself can change everything.
            </h1>

            <p class="hero-description">
                Step away from the busy schedule and give yourself
                time to relax, recharge and feel your best.
            </p>

            <div class="hero-buttons">

                <a href="booking.php" class="button button-primary">
                    Book an Appointment
                </a>

                <a href="services.php" class="button button-outline">
                    Explore Services
                </a>

            </div>

        </div>

        <div class="hero-card">

            <p class="hero-card-label">YOUR TIME</p>

            <h2>Pause. Breathe. Restore.</h2>

            <p>
                Thoughtfully designed treatments in a calm,
                welcoming environment.
            </p>

        </div>

    </div>

</section>


<section class="intro-section">

    <div class="container two-column">

        <div>

            <p class="eyebrow">WHY SERENITY</p>

            <h2>
                Wellness should feel simple.
            </h2>

        </div>

        <div>

            <p>
                At Serenity Spa, we believe taking care of yourself
                should not feel complicated. Our treatments are
                designed to give you a peaceful break from everyday life.
            </p>

            <p>
                Whether you have a full afternoon or only an hour,
                there is a treatment designed to help you slow down
                and reset.
            </p>

        </div>

    </div>

</section>


<section class="services-preview">

    <div class="container">

        <div class="section-heading">

            <div>

                <p class="eyebrow">OUR SERVICES</p>

                <h2>Something for every kind of reset.</h2>

            </div>

            <a href="services.php" class="text-link">
                View all services →
            </a>

        </div>

        <div class="service-grid">

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($service = $result->fetch_assoc()): ?>

                    <article class="service-card">

                        <div class="service-card-top">

                            <span class="service-number">
                                <?php echo str_pad($service["id"], 2, "0", STR_PAD_LEFT); ?>
                            </span>

                            <span class="status available">
                                Available
                            </span>

                        </div>

                        <h3>
                            <?php echo htmlspecialchars($service["name"]); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($service["description"]); ?>
                        </p>

                        <div class="service-details">

                            <span>
                                <?php echo (int) $service["duration"]; ?> min
                            </span>

                            <strong>
                                GHS <?php echo number_format($service["price"], 2); ?>
                            </strong>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty-state">

                    <h3>Our services are being prepared.</h3>

                    <p>
                        Add your first service from the Services page.
                    </p>

                    <a href="create.php" class="button button-primary">
                        Add a Service
                    </a>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<section class="booking-banner">

    <div class="container booking-banner-content">

        <div>

            <p class="eyebrow">READY WHEN YOU ARE</p>

            <h2>
                Your next moment of calm is only a booking away.
            </h2>

        </div>

        <a href="booking.php" class="button button-light">
            Book Your Appointment
        </a>

    </div>

</section>


<section class="features-section">

    <div class="container">

        <div class="section-heading centered">

            <p class="eyebrow">THE SERENITY EXPERIENCE</p>

            <h2>Why people choose us.</h2>

        </div>

        <div class="feature-grid">

            <div class="feature-item">

                <span>01</span>

                <h3>Peaceful Environment</h3>

                <p>
                    A calm space where you can step away from
                    the noise of everyday life.
                </p>

            </div>

            <div class="feature-item">

                <span>02</span>

                <h3>Thoughtful Treatments</h3>

                <p>
                    Services designed around relaxation,
                    restoration and personal care.
                </p>

            </div>

            <div class="feature-item">

                <span>03</span>

                <h3>Easy Booking</h3>

                <p>
                    Choose your service, select a convenient time
                    and submit your appointment in a few simple steps.
                </p>

            </div>

        </div>

    </div>

</section>


<?php

$conn->close();

include "includes/footer.php";

?>
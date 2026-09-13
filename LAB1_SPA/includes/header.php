<?php

// I am checking which page the visitor is currently viewing
// so I can highlight that page in the navigation.
$current_page = basename($_SERVER["PHP_SELF"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo isset($page_title) ? htmlspecialchars($page_title) : "Serenity Spa"; ?>
    </title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header class="site-header">

    <div class="container nav-container">

        <a href="index.php" class="logo">
            Serenity<span> Spa</span>
        </a>

        <nav class="main-nav">

            <a
                href="index.php"
                class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>"
            >
                Home
            </a>

            <a
                href="services.php"
                class="<?php echo $current_page === 'services.php' ? 'active' : ''; ?>"
            >
                Services
            </a>

            <a
                href="booking.php"
                class="<?php echo $current_page === 'booking.php' ? 'active' : ''; ?>"
            >
                Book
            </a>

            <a
                href="bookings.php"
                class="<?php echo $current_page === 'bookings.php' ? 'active' : ''; ?>"
            >
                Bookings
            </a>

            <a
                href="about.php"
                class="<?php echo $current_page === 'about.php' ? 'active' : ''; ?>"
            >
                About
            </a>

            <a
                href="contact.php"
                class="<?php echo $current_page === 'contact.php' ? 'active' : ''; ?>"
            >
                Contact
            </a>

        </nav>

        <a href="booking.php" class="nav-button">
            Book Now
        </a>

    </div>

</header>

<main>
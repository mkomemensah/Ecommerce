<?php

require_once '../core/core.php';
require_once 'layout/header.php';

?>

<h2>Create an Account</h2>

<?php

if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

?>

<form action="../actions/register_action.php" method="POST" id="registerForm">

    <label for="name">Full Name:</label>
    <input type="text" name="name" id="name">
    <span id="nameError"></span>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    <span id="emailError"></span>

    <br><br>

    <label for="pass">Password:</label>
    <input type="password" name="pass" id="pass">
    <span id="passError"></span>

    <br><br>

    <label for="country">Country:</label>
    <select name="country" id="country">
        <option value="">Select Country</option>
        <option value="Ghana">Ghana</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Kenya">Kenya</option>
        <option value="South Africa">South Africa</option>
    </select>
    <span id="countryError"></span>

    <br><br>

    <label for="city">City:</label>
    <input type="text" name="city" id="city">
    <span id="cityError"></span>

    <br><br>

    <label for="contact">Contact Number:</label>
    <input type="text" name="contact" id="contact">
    <span id="contactError"></span>

    <br><br>

    <label for="address">Address:</label>
    <input type="text" name="address" id="address">

    <br><br>

    <button type="submit">Register</button>

</form>

<script src="../js/validate.js"></script>

<?php

require_once 'layout/footer.php';

?>
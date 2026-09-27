<?php

require_once '../core/core.php';
require_once 'layout/header.php';

?>

<h2>Login</h2>

<?php

if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

?>

<form action="../actions/login_action.php" method="POST">

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <br><br>

    <label for="pass">Password:</label>
    <input type="password" name="pass" id="pass" required>

    <br><br>

    <button type="submit">Login</button>

</form>

<p>
    Don't have an account?
    <a href="register.php">Register here</a>
</p>

<?php

require_once 'layout/footer.php';

?>
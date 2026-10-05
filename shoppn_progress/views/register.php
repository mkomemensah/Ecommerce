<?php

require_once '../core/core.php';

$page_title = 'Create an account';
require_once 'layout/header.php';

?>

<div class="auth-wrap">
    <div class="card auth-card auth-card-wide">
        <h1>Create an account</h1>
        <p class="muted">It takes a minute. You can start browsing brands right away.</p>

        <?php flash(); ?>

        <form action="../actions/register_action.php" method="POST" id="registerForm" class="form" novalidate>

            <div class="field">
                <label for="name">Full name</label>
                <input type="text" name="name" id="name" autocomplete="name">
                <span class="field-error" id="nameError"></span>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="email">
                <span class="field-error" id="emailError"></span>
            </div>

            <div class="field">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass" autocomplete="new-password" aria-describedby="passHint passError">
                <span class="field-hint" id="passHint">At least 8 characters, with a letter and a number. Common passwords like 12345678 are not allowed.</span>
                <span class="field-error" id="passError"></span>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="country">Country</label>
                    <select name="country" id="country">
                        <option value="">Select country</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Kenya">Kenya</option>
                        <option value="South Africa">South Africa</option>
                    </select>
                    <span class="field-error" id="countryError"></span>
                </div>

                <div class="field">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" autocomplete="address-level2">
                    <span class="field-error" id="cityError"></span>
                </div>
            </div>

            <div class="field">
                <label for="contact">Contact number</label>
                <input type="text" name="contact" id="contact" autocomplete="tel">
                <span class="field-error" id="contactError"></span>
            </div>

            <div class="field">
                <label for="address">Address <span class="optional">(optional)</span></label>
                <input type="text" name="address" id="address" autocomplete="street-address">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Create account</button>
        </form>

        <p class="auth-switch">Already registered? <a href="login.php">Log in</a></p>
    </div>
</div>

<script src="../js/validate.js"></script>

<?php require_once 'layout/footer.php'; ?>

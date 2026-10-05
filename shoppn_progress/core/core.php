<?php

session_start();

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';

/*
 * BASE_URL: the web path to the project folder (e.g. "/~user/shoppn").
 * Worked out automatically so links keep working wherever the project is
 * placed. 
 */
if (!defined('BASE_URL')) {
    $root   = str_replace('\\', '/', (string) realpath(__DIR__ . '/..'));
    $script = str_replace('\\', '/', (string) realpath($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $name   = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $rel    = ($script !== '' && strpos($script, $root) === 0) ? substr($script, strlen($root)) : '';
    $base   = ($rel !== '' && substr($name, -strlen($rel)) === $rel) ? substr($name, 0, -strlen($rel)) : '';
    define('BASE_URL', rtrim($base, '/'));
}

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function get_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

function require_login() {
    if (!is_logged_in()) {
        redirect(url('views/login.php'));
    }
}

function require_admin() {
    if (!is_admin()) {
        $_SESSION['error'] = 'You need to be logged in as an admin to do that.';
        redirect(url('index.php'));
    }
}

/* Print (and clear) any success / error message stored in the session. */
function flash() {
    foreach (['success', 'error'] as $type) {
        if (isset($_SESSION[$type])) {
            echo '<div class="alert alert-' . $type . '" role="' . ($type === 'error' ? 'alert' : 'status') . '">'
                . e($_SESSION[$type]) . '</div>';
            unset($_SESSION[$type]);
        }
    }
}

/* A stable colour (0-359) for a brand, used for its monogram tile. */
function brand_hue($name) {
    return crc32(strtolower((string) $name)) % 360;
}

function brand_initials($name) {
    $name = trim((string) $name);
    return $name === '' ? '?' : strtoupper(mb_substr($name, 0, 2));
}

/*
 * Password rules. Returns an error message, or null when the password is OK.
 * Keep in sync with js/validate.js.
 */
function validate_password($pass) {
    $common = [
        '12345678', '123456789', '1234567890', '87654321', '11111111', '00000000',
        'password', 'password1', 'password12', 'password123', 'passw0rd', 'p@ssw0rd',
        'qwerty123', 'qwertyui', 'qwertyuiop', 'abc12345', 'abcd1234', 'a1b2c3d4',
        'iloveyou', 'iloveyou1', 'welcome1', 'welcome123', 'admin123', 'letmein123',
        'changeme', 'monkey123', 'football1', 'dragon123',
    ];

    if (strlen($pass) < 8) {
        return 'Password must be at least 8 characters.';
    }

    $lower = strtolower($pass);

    if (in_array($lower, $common, true)
        || preg_match('/^(.)\1+$/', $pass)
        || (ctype_digit($pass) && (strpos('01234567890123456789', $pass) !== false
            || strpos('98765432109876543210', $pass) !== false))) {
        return 'That password is too easy to guess. Choose something less predictable.';
    }

    if (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/\d/', $pass)) {
        return 'Password must include at least one letter and one number.';
    }

    return null;
}
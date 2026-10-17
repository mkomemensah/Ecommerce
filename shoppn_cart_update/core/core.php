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
    // Work out the web path from SCRIPT_NAME alone (no filesystem lookups, which
    // can fail on shared/school servers). Anything inside actions/, views/, etc.
    // sits one or more folders below the project root, so cut the path there;
    // for root files (index.php, logout.php) the project folder is the script's folder.
    $name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (preg_match('#^(.*?)/(actions|views|core|classes|controllers)/#', $name, $m)) {
        $base = $m[1];
    } else {
        $base = dirname($name);
    }
    define('BASE_URL', rtrim($base === '.' ? '' : $base, '/'));
}

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/*
 * Returns the ID that identifies this visitor's cart (stored in the cart's
 * ip_add column). Many students on the school network share one public IP, so
 * using the real IP would make everyone share a cart. Instead each browser gets
 * its own random guest ID, kept in a cookie for 30 days.
 * (Function name kept as get_ip() so the rest of the code does not change.)
 */
function get_ip() {
    static $id = null;
    if ($id !== null) {
        return $id;
    }

    $cookie = $_COOKIE['guest_id'] ?? '';

    if (is_string($cookie) && preg_match('/^g[a-f0-9]{32}$/', $cookie)) {
        $id = $cookie;
    } else {
        $id = 'g' . bin2hex(random_bytes(16));   // 33 characters, fits varchar(50)
        setcookie('guest_id', $id, [
            'expires'  => time() + 60 * 60 * 24 * 30,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE['guest_id'] = $id;
    }

    return $id;
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

/*
 * Only allow redirects back to this same site (stops "open redirect" tricks).
 * Returns a safe URL, falling back to the home page.
 */
function safe_return_url($target) {
    $fallback = url('index.php');
    $target = (string) $target;
    if ($target === '') {
        return $fallback;
    }
    $parts = parse_url($target);
    if ($parts === false) {
        return $fallback;
    }
    if (isset($parts['host']) && strcasecmp($parts['host'], $_SERVER['HTTP_HOST'] ?? '') !== 0) {
        return $fallback;
    }
    return $target;
}

/* The page the visitor is on right now, as a path (used to come back after login). */
function current_url() {
    return $_SERVER['REQUEST_URI'] ?? url('index.php');
}

/*
 * Send guests to the login page with a friendly message, and remember
 * where to bring them back to once they have logged in.
 */
function require_login($message = 'Please log in to continue.') {
    if (!is_logged_in()) {
        $_SESSION['info'] = $message;
        $_SESSION['after_login'] = current_url();
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
    foreach (['success', 'error', 'info'] as $type) {
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
    if ($name === '') {
        return '?';
    }
    $part = function_exists('mb_substr') ? mb_substr($name, 0, 2) : substr($name, 0, 2);
    return strtoupper($part);
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
<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fitness_reservation');

function connect_database() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Връзката с базата данни е неуспешна: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}

function sanitize_input($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generate_salt() {
    return bin2hex(random_bytes(16));
}

function hash_password($password, $salt) {
    return hash('sha256', $password . $salt);
}
?>
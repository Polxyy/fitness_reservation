<?php
require_once 'config.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Не сте влезли в системата.']);
    exit();
}

$user_id = $_SESSION['user_id'];


$conn = connect_database();


$stmt = mysqli_prepare($conn, "SELECT name, email FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $current_name, $current_email);
mysqli_stmt_fetch($stmt);


if ($current_name && $current_email) {
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'name' => $current_name,
            'email' => $current_email
        ]
    ]);
} else {
    
    echo json_encode(['status' => 'error', 'message' => 'Не може да бъде заредена информацията за профила.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

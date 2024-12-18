<?php
require_once 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $user_id = $_SESSION['user_id'];

    $conn = connect_database();
    $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['user_name'] = $name;  
        $_SESSION['user_email'] = $email; 

        echo json_encode([
            'status' => 'success',
            'message' => 'Профилът е успешно обновен.',
            'redirect' => 'dashboard.php' 
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Възникна грешка при обновяването на профила.'
        ]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

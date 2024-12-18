<?php
require_once 'config.php';

$error = '';
$redirect_url = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Всички полета са задължителни';
    } elseif (!validate_email($email)) {
        $error = 'Невалиден имейл адрес';
    } elseif ($password !== $confirm_password) {
        $error = 'Паролите не съвпадат';
    } else {
        $conn = connect_database();
        
        $check_email = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_email, "s", $email);
        mysqli_stmt_execute($check_email);
        mysqli_stmt_store_result($check_email);
        
        if (mysqli_stmt_num_rows($check_email) > 0) {
            $error = 'Имейлът вече съществува';
        } else {
            $salt = generate_salt();
            $hashed_password = hash_password($password, $salt);
            
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, salt) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $salt);
            
            if (mysqli_stmt_execute($stmt)) {
                session_start();
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                $redirect_url = 'dashboard.php';
            } else {
                $error = 'Възникна грешка при регистрацията';
            }
        }
        
        mysqli_stmt_close($check_email);
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function redirectToDashboard(url) {
            if (url) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body onload="redirectToDashboard('<?php echo $redirect_url; ?>')">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Регистрация</div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Име</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Имейл</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Парола</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Потвърди парола</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Регистрирай се</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

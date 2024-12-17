<?php
require_once 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Фитнес Резервации - Начало</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1538805060514-97d9cc17730c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Фитнес Резервации</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Табло</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_reservation.php">Нова Резервация</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Изход</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Регистрация</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-4">Вашата Фитнес Резервационна Система</h1>
            <p class="lead">Лесно и бързо запазване на часове за вашите любими фитнес класове</p>
            <?php if (!$is_logged_in): ?>
                <div class="mt-4">
                    <a href="register.php" class="btn btn-primary btn-lg me-3">Регистрирай се</a>
                    <a href="login.php" class="btn btn-outline-light btn-lg">Влез</a>
                </div>
            <?php else: ?>
                <div class="mt-4 text-center text-white">
                    <p>Заповядайте, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="feature-icon">📅</div>
                <h3>Лесни Резервации</h3>
                <p>Резервирайте часове за вашите любими фитнес класове с няколко клика</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-icon">🕒</div>
                <h3>Гъвкаво Планиране</h3>
                <p>Преглеждайте и управлявайте вашите резервации по всяко време</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="feature-icon">📊</div>
                <h3>Разнообразие от Класове</h3>
                <p>Йога, Пилатес, Кардио и много други фитнес дейности</p>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center pt-3 pb-5">
        <div class="container">
            <p class="mb-0">&copy; Корай Мустафов, ИУ-ВАРНА 2024</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
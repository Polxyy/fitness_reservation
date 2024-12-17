<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Обнови профил</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Обнови профила</h1>
        
        <div id="message" class="alert" style="display: none;"></div>

        <form id="update-profile-form">
            <div class="mb-3">
                <label for="name" class="form-label">Име</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Имейл</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Обнови профил</button>
            <a href="./dashboard.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {

        var currentName, currentEmail;  

        
        $.ajax({
            url: 'profile_fetch.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#name').val(response.data.name);
                    $('#email').val(response.data.email);

                    currentName = response.data.name;
                    currentEmail = response.data.email;
                } else {
                    $('#message').addClass('alert-danger').text('Не може да бъде заредена информацията за профила.').show();
                }
            },
            error: function() {
                
                $('#message').addClass('alert-danger').text('Възникна грешка при зареждането на профила.').show();
            }
        });

        
        $("#update-profile-form").on("submit", function(e) {
            e.preventDefault();  

            
            if (!currentName || !currentEmail) {
                Swal.fire({
                    icon: 'error',
                    title: 'Грешка!',
                    text: 'Профилът не е зареден правилно.',
                    confirmButtonText: 'ОК'
                });
                return;
            }

            var name = $("#name").val();
            var email = $("#email").val();

            
            if (name === currentName && email === currentEmail) {
                Swal.fire({
                    icon: 'info',
                    title: 'Няма промени!',
                    text: 'Не сте направили промени в профила си.',
                    confirmButtonText: 'ОК'
                });
                return;  
            }

            $.ajax({
                url: "profile_update_handler.php", 
                method: "POST",
                data: {
                    ajax: 'true',  
                    name: name,
                    email: email
                },
                dataType: "json", 
                success: function(response) {
                    if (response.status === "success") {
                            Swal.fire({
                            icon: 'success',
                            title: 'Профилът е успешно обновен.',
                            text: response.message,
                            confirmButtonText: 'ОК',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = response.redirect; 
                            }
                        });
                    } else {
                            Swal.fire({
                            icon: 'error',
                            title: 'Грешка!',
                            text: response.message,
                            confirmButtonText: 'ОК',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Грешка!',
                        text: "Възникна грешка при обработката на заявката.",
                        confirmButtonText: 'ОК',
                    });
                }
            });
        });

    });

    </script>
</body>
</html>

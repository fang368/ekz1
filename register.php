<?php
require 'vendor/connect.php';
$message_error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role_id_user = 1;

    if(strlen($password) < 6 ){
        $message_error = 'Длина пароля должна быть более 6 символов';
    }

    if(strlen($phone) < 11){
        $message_error .= ' Некорректный номер';
    }

    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $check_email_result = mysqli_query($conn, $check_email);
    if(mysqli_num_rows($check_email_result) > 0){
        $message_error .= " Данный пользователь уже зарегистрирован с этим email";
    }

    $check_login = "SELECT id FROM users WHERE login = '$login'";
    $check_login_result = mysqli_query($conn, $check_login);
    if(mysqli_num_rows($check_login_result) > 0){
        $message_error .= " Данный пользователь уже зарегистрирован с этим логином";
    }

    if(empty($message_error)){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO users (id_role, full_name, login, email, password) VALUES ('$role_id_user', '$full_name', '$login', '$email', '$hashed_password')";

        if(mysqli_query($conn, $insert_sql)){
            header('location: login.php');
            exit();
        } else {
            $message_error = 'Ошибка при регистрации: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Регистрация</title>
</head>
<body>
    <section class="main-section">
        <div class="container main-section__container">
            <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form action="profile.php" class="d-flex" role="search">
                    <button class="btn btn-outline-success" type="submit">Профиль</button>
                </form>
                </div>
            </div>
            </nav>
        </div>

        <div class="container main-form__container">
            <form action="register.php" method="POST" class="form__container-main">

                <?php if(!empty($message_error)) :?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $message_error; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="full_name" class="form-label">ФИО</label>
                    <input type="text" name="full_name" class="form-control" placeholder="Иванов Иван Иванович" required>
                </div>
                
                <div class="form-group">
                    <label for="login" class="form-label">Логин</label>
                    <input type="text" name="login" class="form-control" placeholder="Введите логин" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Почта</label>
                    <input type="email" name="email" class="form-control" placeholder="Почта" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Введите номер телефона" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" placeholder="Введите пароль" required>
                </div>

                <div class="form-group">
                    <a href="login.php">Уже есть аккаунт ? - авторизоваться</a>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </div>
            </form>
        </div>
    </section>

<script src="js/bootstrap.bundle.js"></script>
</body>
</html>

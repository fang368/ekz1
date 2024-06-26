<?php
require 'vendor/connect.php';
session_start();
$message_error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE login = '$login'";
    $result = mysqli_query($conn, $sql);
    if($result && mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            
            if($row['id_role'] == 2){
                header('location: admin_panel.php');
            }else{
                header('location: index.php');
            }
        }else{
            $message_error = "Неккоректные данные";
        }
    }else{
        $message_error = "Пользователь с таким email не найден";
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
    <title>Авторизация</title>
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
            <form action="login.php" method="POST" class="form__container-main">

                <?php if(!empty($message_error)) :?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $message_error; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="login" class="form-label">Логин</label>
                    <input type="text" name="login" class="form-control" placeholder="Введите логин" require>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" placeholder="Введите пароль" require>
                </div>

                <div class="form-group">
                    <a href="register.php">Еще нет аккаунта? - зарегистрироваться</a>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn_max-width">Войти</button>
                </div>
            </form>
        </div>
    </section>


<script src="js/bootstrap.bundle.js"></script>
</body>
</html>

<?php

session_start();

include 'db/db.php';

$message = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");

    $stmt->bind_param("ss", $username, $password);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        header("Location: bookRegistration/bookInventory.php");
        exit();

    }else{

        $message = "Invalid Username or Password!";

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - LibraCore</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background: linear-gradient(to right, #162E93, #0d1b5e);
            height: 100vh;
            overflow: hidden;
        }

        /* LOGIN CARD */

        .login-card{
            border: none;
            border-radius: 20px;
            animation: fadeIn 0.8s ease;
        }

        .form-control{
            padding: 12px;
            border-radius: 10px;
        }

        .btn-login{
            background-color: #162E93;
            border: none;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-login:hover{
            background-color: #0d1b5e;
            transform: translateY(-2px);
        }

        /* PASSWORD ICON */

        .password-wrapper{
            position: relative;
        }

        .toggle-password{
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: gray;
            font-size: 18px;
        }

        /* FADE ANIMATION */

        @keyframes fadeIn{

            from{
                opacity: 0;
                transform: translateY(30px);
            }

            to{
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* LOADER */

        #loader-wrapper{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #162E93, #0d1b5e);

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            z-index: 99999;

            opacity: 0;
            visibility: hidden;

            transition: 0.3s ease;
        }

        #loader-wrapper.active{
            opacity: 1;
            visibility: visible;
        }

        /* BOOK */

        .book{
            position: relative;
            width: 120px;
            height: 80px;
            perspective: 1000px;
        }

        /* PAGES */

        .book-page{
            position: absolute;
            width: 60px;
            height: 80px;
            background: white;
            border-radius: 5px;
            transform-origin: left;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .page-left{
            left: 0;
            background: #dfe6ff;
        }

        .page-middle{
            left: 30px;
            background: white;
            animation: flip 1.2s infinite ease-in-out;
        }

        .page-right{
            left: 60px;
            background: #cfd9ff;
        }

        .loading-text{
            margin-top: 35px;
            color: white;
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* BOOK FLIP */

        @keyframes flip{

            0%{
                transform: rotateY(0deg);
            }

            50%{
                transform: rotateY(-180deg);
            }

            100%{
                transform: rotateY(0deg);
            }
        }

    </style>

</head>

<body>

<div class="container h-100 d-flex justify-content-center align-items-center">

    <div class="card shadow-lg login-card p-4" style="width: 420px;">

        <!-- HEADER -->

        <div class="text-center mb-4">

            <i class="bi bi-book-half text-primary" style="font-size: 60px;"></i>

            <h2 class="fw-bold mt-2">
                LibraCore
            </h2>

            <p class="text-muted">
                Library Management System
            </p>

        </div>

        <!-- ERROR MESSAGE -->

        <?php if($message != ""){ ?>

            <div class="alert alert-danger">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <!-- LOGIN FORM -->

        <form method="POST">

            <!-- USERNAME -->

            <div class="mb-3">

                <label class="form-label">
                    Username
                </label>

                <input type="text"
                       name="username"
                       class="form-control"
                       placeholder="Enter username"
                       required>

            </div>

            <!-- PASSWORD -->

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="password-wrapper">

                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter password"
                           required>

                    <i class="bi bi-eye-slash toggle-password"
                       id="togglePassword"></i>

                </div>

            </div>

            <!-- LOGIN BUTTON -->

            <button type="submit"
                    name="login"
                    class="btn btn-primary w-100 btn-login">

                <i class="bi bi-box-arrow-in-right"></i>
                Login

            </button>

            <div class="text-center mt-3 text-muted">
                Secure Staff Access Portal
            </div>

            <div class="text-center mt-2">

                <span class="text-muted">
                    Don't have an account?
                </span>

                <a href="createAccount.php"
                class="text-decoration-none fw-semibold">

                    Create Account

                </a>

            </div>

        </form>

    </div>

</div>

<!-- BOOK LOADER -->

<div id="loader-wrapper">

    <div class="book">

        <div class="book-page page-left"></div>

        <div class="book-page page-middle"></div>

        <div class="book-page page-right"></div>

    </div>

    <div class="loading-text">
        Opening Library...
    </div>

</div>

<!-- SCRIPTS -->

<script>

    // LOGIN LOADER

    const loginForm = document.querySelector("form");
    const loader = document.getElementById("loader-wrapper");

    loginForm.addEventListener("submit", function(){

        const username = document.querySelector("input[name='username']").value;
        const password = document.querySelector("input[name='password']").value;

        if(username !== "" && password !== ""){

            loader.classList.add("active");

        }

    });

    // SHOW / HIDE PASSWORD

    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", function(){

        const type = passwordField.getAttribute("type") === "password"
            ? "text"
            : "password";

        passwordField.setAttribute("type", type);

        this.classList.toggle("bi-eye");
        this.classList.toggle("bi-eye-slash");

    });

</script>

</body>
</html>
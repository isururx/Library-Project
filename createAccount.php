<?php

include 'db/db.php';

$message = "";
$message_type = "";

/* GENERATE NEXT USER ID */

$nextUserID = "U001";

$resultID = $conn->query("
    SELECT user_id
    FROM user
    ORDER BY CAST(SUBSTRING(user_id, 2) AS UNSIGNED) DESC
    LIMIT 1
");

if($resultID->num_rows > 0){

    $rowID = $resultID->fetch_assoc();

    $lastID = $rowID['user_id'];

    $number = (int) substr($lastID, 1);

    $number++;

    $nextUserID = "U" . str_pad($number, 3, "0", STR_PAD_LEFT);

}

/* REGISTER USER */

if(isset($_POST['register'])){

    $user_id = trim($_POST['user_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    /* USER ID VALIDATION */

    if(!preg_match('/^U[0-9]{3}$/', $user_id)){

        $message = "Invalid User ID Format";
        $message_type = "danger";

    }

    /* PASSWORD VALIDATION */

    elseif(strlen($password) < 8){

        $message = "Password must contain at least 8 characters";
        $message_type = "danger";

    }

    /* EMAIL VALIDATION */

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $message = "Invalid Email Format";
        $message_type = "danger";

    }

    else{

        /* CHECK EXISTING USERNAME OR EMAIL */

        $check = $conn->prepare("
            SELECT *
            FROM user
            WHERE username = ? OR email = ?
        ");

        $check->bind_param("ss", $username, $email);

        $check->execute();

        $result = $check->get_result();

        if($result->num_rows > 0){

            $message = "Username or Email already exists";
            $message_type = "danger";

        }else{

            $stmt = $conn->prepare("
                INSERT INTO user
                (user_id, first_name, last_name, username, password, email)
                VALUES
                (?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssss",
                $user_id,
                $first_name,
                $last_name,
                $username,
                $password,
                $email
            );

            if($stmt->execute()){

                header("Location: login.php?success=registered");
                exit();

            }else{

                $message = "Error: " . $conn->error;
                $message_type = "danger";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account - LibraCore</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body{
            background: linear-gradient(to right, #162E93, #0d1b5e);
            min-height: 100vh;
        }

        .register-card{
            border: none;
            border-radius: 20px;
            animation: fadeIn 0.7s ease;
        }

        .form-control{
            padding: 12px;
            border-radius: 10px;
        }

        .btn-register{
            background-color: #162E93;
            border: none;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-register:hover{
            background-color: #0d1b5e;
            transform: translateY(-2px);
        }

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

    </style>

</head>

<body>

<div class="container py-5 d-flex justify-content-center align-items-center">

    <div class="card shadow-lg register-card p-4" style="width: 650px;">

        <div class="text-center mb-4">

            <i class="bi bi-person-plus-fill text-primary" style="font-size: 60px;"></i>

            <h2 class="fw-bold mt-2">
                Create Staff Account
            </h2>

            <p class="text-muted">
                LibraCore Library Management System
            </p>

        </div>

        <?php if($message != ""){ ?>

            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        User ID
                    </label>

                    <input type="text"
                           name="user_id"
                           class="form-control"
                           value="<?php echo $nextUserID; ?>"
                           readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input type="text"
                           name="username"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        First Name
                    </label>

                    <input type="text"
                           name="first_name"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Last Name
                    </label>

                    <input type="text"
                           name="last_name"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <div class="password-wrapper">

                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control"
                               required>

                        <i class="bi bi-eye-slash toggle-password"
                           id="togglePassword"></i>

                    </div>

                    <small class="text-muted">
                        Minimum 8 characters
                    </small>

                </div>

            </div>

            <button type="submit"
                    name="register"
                    class="btn btn-primary w-100 btn-register">

                <i class="bi bi-person-check-fill"></i>
                Create Account

            </button>

            <div class="text-center mt-3">

                <a href="login.php"
                   class="text-decoration-none">

                    Already have an account? Login

                </a>

            </div>

        </form>

    </div>

</div>

<script>

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
```

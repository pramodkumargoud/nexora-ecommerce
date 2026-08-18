<?php

include('../includes/db.php');

session_start();

$error_message = '';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    /* =========================================
       FIND USER
    ========================================= */

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    /* =========================================
       VERIFY LOGIN
    ========================================= */

    if (
        $user &&
        password_verify(
            $password,
            $user['password']
        )
    ) {

        $_SESSION['user_id'] =
            $user['id'];

        header(
            "Location: ../index.php"
        );

        exit();

    } else {

        $error_message =
            "Invalid email or password.";

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Customer Login</title>


    <style>

        /* =========================================
           RESET
        ========================================= */

        * {
            box-sizing: border-box;
        }


        /* =========================================
           BODY
        ========================================= */

        body {

            margin: 0;

            min-height: 100vh;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 30px 20px;

            background:
                linear-gradient(
                    135deg,
                    #667eea 0%,
                    #764ba2 100%
                );

            position: relative;

            overflow: hidden;

        }


        /* =========================================
           BACKGROUND CIRCLES
        ========================================= */

        body::before {

            content: "";

            position: fixed;

            width: 400px;

            height: 400px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.08
                );

            top: -160px;

            left: -130px;

            pointer-events: none;

        }


        body::after {

            content: "";

            position: fixed;

            width: 500px;

            height: 500px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.07
                );

            bottom: -250px;

            right: -180px;

            pointer-events: none;

        }


        /* =========================================
           LOGIN CARD
        ========================================= */

        .login-container {

            width: 100%;

            max-width: 430px;

            padding: 40px;

            background: #ffffff;

            border-radius: 20px;

            position: relative;

            z-index: 2;

            box-shadow:
                0 25px 60px
                rgba(
                    0,
                    0,
                    0,
                    0.22
                );

            animation:
                loginAppear
                0.6s ease;

        }


        @keyframes loginAppear {

            from {

                opacity: 0;

                transform:
                    translateY(25px)
                    scale(0.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }


        /* =========================================
           USER ICON
        ========================================= */

        .login-icon {

            width: 70px;

            height: 70px;

            margin:
                0 auto 18px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 18px;

            color: white;

            font-size: 30px;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            box-shadow:
                0 10px 25px
                rgba(
                    102,
                    126,
                    234,
                    0.30
                );

        }


        /* =========================================
           TITLE
        ========================================= */

        h2 {

            margin: 0;

            text-align: center;

            color: #222;

            font-size: 30px;

            font-weight: 700;

        }


        .subtitle {

            margin:
                10px 0 30px;

            text-align: center;

            color: #888;

            font-size: 14px;

        }


        /* =========================================
           ERROR
        ========================================= */

        .error-message {

            padding:
                12px 14px;

            margin-bottom: 20px;

            border-radius: 10px;

            background: #fff1f0;

            border:
                1px solid #ffd6d2;

            color: #d63031;

            text-align: center;

            font-size: 13px;

            animation:
                errorShake
                0.35s ease;

        }


        @keyframes errorShake {

            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }

        }


        /* =========================================
           FORM GROUP
        ========================================= */

        .form-group {

            margin-bottom: 20px;

        }


        /* =========================================
           LABEL
        ========================================= */

        label {

            display: block;

            margin-bottom: 8px;

            color: #333;

            font-size: 14px;

            font-weight: 600;

        }


        /* =========================================
           INPUT WRAPPER
        ========================================= */

        .input-wrapper {

            position: relative;

        }


        .input-icon {

            position: absolute;

            left: 15px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #999;

            font-size: 16px;

            pointer-events: none;

        }


        /* =========================================
           INPUT
        ========================================= */

        input[type="email"],
        input[type="password"] {

            width: 100%;

            height: 50px;

            padding:
                0 15px 0 45px;

            border:
                1px solid #ddd;

            border-radius: 10px;

            color: #333;

            background: #fff;

            font-size: 14px;

            outline: none;

            transition:
                border-color 0.3s ease,
                box-shadow 0.3s ease;

        }


        input::placeholder {

            color: #aaa;

        }


        input:focus {

            border-color: #667eea;

            box-shadow:
                0 0 0 3px
                rgba(
                    102,
                    126,
                    234,
                    0.12
                );

        }


        /* =========================================
           PASSWORD
        ========================================= */

        .password-wrapper {

            position: relative;

        }


        .password-wrapper input {

            padding-right: 50px;

        }


        .toggle-password {

            position: absolute;

            right: 12px;

            top: 50%;

            transform:
                translateY(-50%);

            border: none;

            background: transparent;

            color: #999;

            font-size: 16px;

            cursor: pointer;

            padding: 6px;

        }


        .toggle-password:hover {

            color: #667eea;

        }


        /* =========================================
           LOGIN BUTTON
        ========================================= */

        .login-button {

            width: 100%;

            height: 52px;

            margin-top: 5px;

            border: none;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 8px 20px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;

        }


        .login-button:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 12px 28px
                rgba(
                    102,
                    126,
                    234,
                    0.35
                );

        }


        .login-button:active {

            transform:
                translateY(0)
                scale(0.98);

        }


        /* =========================================
           REGISTER
        ========================================= */

        .register-section {

            text-align: center;

            margin-top: 25px;

            padding-top: 20px;

            border-top:
                1px solid #eee;

            color: #888;

            font-size: 13px;

        }


        .register-section a {

            color: #667eea;

            font-weight: 700;

            text-decoration: none;

            transition:
                color 0.3s ease;

        }


        .register-section a:hover {

            color: #764ba2;

            text-decoration:
                underline;

        }


        /* =========================================
           BACK TO SHOP
        ========================================= */

        .back-shop {

            display: flex;

            align-items: center;

            justify-content: center;

            margin-top: 18px;

            color: #888;

            text-decoration: none;

            font-size: 12px;

            transition:
                color 0.3s ease;

        }


        .back-shop:hover {

            color: #667eea;

        }


        /* =========================================
           SECURITY
        ========================================= */

        .security-message {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            margin-top: 18px;

            color: #aaa;

            font-size: 11px;

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 500px) {

            body {

                padding:
                    20px 12px;

            }


            .login-container {

                padding:
                    30px 22px;

                border-radius: 16px;

            }


            h2 {

                font-size: 26px;

            }


            .login-icon {

                width: 60px;

                height: 60px;

                font-size: 26px;

                border-radius: 15px;

            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <!-- USER ICON -->

    <div class="login-icon">
        👤
    </div>


    <!-- TITLE -->

    <h2>
        Welcome Back
    </h2>


    <p class="subtitle">
        Sign in to continue shopping with us
    </p>


    <!-- ERROR -->

    <?php if (!empty($error_message)): ?>

        <div class="error-message">

            ⚠
            <?= htmlspecialchars(
                $error_message
            ); ?>

        </div>

    <?php endif; ?>


    <!-- LOGIN FORM -->

    <form method="POST">


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <div class="input-wrapper">

                <span class="input-icon">
                    ✉
                </span>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    autocomplete="email"
                    required
                >

            </div>

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <div class="password-wrapper">

                <span class="input-icon">
                    🔒
                </span>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

                <button
                    type="button"
                    class="toggle-password"
                    id="togglePassword"
                    aria-label="Show password"
                >
                    👁
                </button>

            </div>

        </div>


        <!-- LOGIN -->

        <button
            type="submit"
            name="login"
            class="login-button"
        >
            Sign In
        </button>


    </form>


    <!-- REGISTER -->

    <div class="register-section">

        Don't have an account?

        <a href="register.php">
            Create Account
        </a>

    </div>


    <!-- BACK -->

    <a
        href="../index.php"
        class="back-shop"
    >
        ← Back to Shop
    </a>


    <!-- SECURITY -->

    <div class="security-message">

        🔒 Secure & protected login

    </div>


</div>


<script>

    const passwordInput =
        document.getElementById(
            'password'
        );

    const togglePassword =
        document.getElementById(
            'togglePassword'
        );


    togglePassword.addEventListener(
        'click',
        function () {

            if (
                passwordInput.type ===
                'password'
            ) {

                passwordInput.type =
                    'text';

                togglePassword.textContent =
                    '🙈';

                togglePassword.setAttribute(
                    'aria-label',
                    'Hide password'
                );

            } else {

                passwordInput.type =
                    'password';

                togglePassword.textContent =
                    '👁';

                togglePassword.setAttribute(
                    'aria-label',
                    'Show password'
                );

            }

        }
    );

</script>


</body>

</html>
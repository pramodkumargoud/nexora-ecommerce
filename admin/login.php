<?php

include '../includes/db.php';

session_start();

$error = '';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ? AND role = 'admin'"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['admin_id'] = $user['id'];

        header("Location: dashboard.php");

        exit();

    } else {

        $error = "Invalid credentials or you are not an admin.";

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

    <title>Admin Login</title>


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
           BACKGROUND DECORATION
        ========================================= */

        body::before {

            content: "";

            position: fixed;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            background:
                rgba(255, 255, 255, 0.08);

            top: -120px;

            left: -100px;

        }


        body::after {

            content: "";

            position: fixed;

            width: 450px;

            height: 450px;

            border-radius: 50%;

            background:
                rgba(255, 255, 255, 0.07);

            bottom: -200px;

            right: -150px;

        }


        /* =========================================
           LOGIN CONTAINER
        ========================================= */

        .login-container {

            width: 100%;

            max-width: 430px;

            background: #ffffff;

            padding: 40px;

            border-radius: 20px;

            position: relative;

            z-index: 2;

            box-shadow:
                0 25px 60px
                rgba(0, 0, 0, 0.22);

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
           LOGIN ICON
        ========================================= */

        .login-icon {

            width: 70px;

            height: 70px;

            margin: 0 auto 18px;

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

            text-align: center;

            color: #222;

            margin: 0;

            font-size: 30px;

            font-weight: 700;

        }


        .subtitle {

            text-align: center;

            color: #888;

            font-size: 14px;

            margin:
                10px 0 30px;

        }


        /* =========================================
           ERROR MESSAGE
        ========================================= */

        .error-message {

            background: #fff1f0;

            border:
                1px solid #ffd6d2;

            color: #d63031;

            padding: 12px 14px;

            border-radius: 10px;

            font-size: 13px;

            text-align: center;

            margin-bottom: 20px;

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

            font-size: 14px;

            font-weight: 600;

            color: #333;

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

            font-size: 16px;

            color: #999;

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

            background: #fff;

            color: #333;

            font-size: 14px;

            outline: none;

            transition:
                border-color 0.3s ease,
                box-shadow 0.3s ease,
                transform 0.2s ease;

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


        input:focus + .input-focus {

            width: 100%;

        }


        /* =========================================
           PASSWORD TOGGLE
        ========================================= */

        .password-wrapper {

            position: relative;

        }


        .password-wrapper input {

            padding-right: 48px;

        }


        .toggle-password {

            position: absolute;

            right: 14px;

            top: 50%;

            transform:
                translateY(-50%);

            border: none;

            background: transparent;

            color: #999;

            cursor: pointer;

            font-size: 16px;

            padding: 5px;

        }


        .toggle-password:hover {

            color: #667eea;

        }


        /* =========================================
           LOGIN BUTTON
        ========================================= */

        button[type="submit"] {

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


        button[type="submit"]:hover {

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


        button[type="submit"]:active {

            transform:
                translateY(0)
                scale(0.98);

        }


        /* =========================================
           FOOTER
        ========================================= */

        .form-footer {

            text-align: center;

            margin-top: 25px;

            padding-top: 20px;

            border-top:
                1px solid #eee;

            color: #888;

            font-size: 13px;

        }


        .form-footer span {

            color: #667eea;

            font-weight: 600;

        }


        /* =========================================
           SECURITY TEXT
        ========================================= */

        .security-message {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            margin-top: 15px;

            color: #999;

            font-size: 11px;

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 500px) {

            body {

                padding: 20px 12px;

            }


            .login-container {

                padding: 30px 22px;

                border-radius: 16px;

            }


            h2 {

                font-size: 26px;

            }


            .login-icon {

                width: 60px;

                height: 60px;

                font-size: 25px;

                border-radius: 15px;

            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <!-- LOGIN ICON -->

    <div class="login-icon">
        🔐
    </div>


    <!-- TITLE -->

    <h2>
        Admin Login
    </h2>


    <p class="subtitle">
        Sign in to access your admin dashboard
    </p>


    <!-- ERROR -->

    <?php if (!empty($error)): ?>

        <div class="error-message">

            <?= htmlspecialchars($error); ?>

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
                    placeholder="Enter your admin email"
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
        >
            Sign In to Dashboard
        </button>


    </form>


    <!-- FOOTER -->

    <div class="form-footer">

        <span>Admin Portal</span>

        <div class="security-message">

            🔒 Secure administrator access

        </div>

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
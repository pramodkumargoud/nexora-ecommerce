<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
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

    <title>Admin Dashboard</title>


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

            background:
                linear-gradient(
                    135deg,
                    #667eea 0%,
                    #764ba2 100%
                );

            color: #333;

            padding: 40px 20px;

            position: relative;

            overflow-x: hidden;

        }


        /* =========================================
           BACKGROUND DECORATION
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

            top: -150px;

            left: -120px;

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
           MAIN CONTAINER
        ========================================= */

        .container {

            width: 100%;

            max-width: 1100px;

            margin: 0 auto;

            background: #ffffff;

            padding: 40px;

            border-radius: 22px;

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
                dashboardAppear
                0.6s ease;

        }


        @keyframes dashboardAppear {

            from {

                opacity: 0;

                transform:
                    translateY(25px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        /* =========================================
           HEADER
        ========================================= */

        .dashboard-header {

            text-align: center;

            margin-bottom: 40px;

        }


        .dashboard-icon {

            width: 75px;

            height: 75px;

            margin: 0 auto 18px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 20px;

            font-size: 32px;

            color: white;

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


        h2 {

            margin: 0;

            font-size: 32px;

            font-weight: 700;

            color: #222;

        }


        .subtitle {

            margin:
                10px 0 0;

            color: #888;

            font-size: 14px;

        }


        /* =========================================
           DASHBOARD CARDS
        ========================================= */

        .dashboard-grid {

            display: grid;

            grid-template-columns:
                repeat(
                    3,
                    1fr
                );

            gap: 22px;

        }


        .dashboard-card {

            position: relative;

            overflow: hidden;

            text-decoration: none;

            background: #ffffff;

            border:
                1px solid #eee;

            border-radius: 16px;

            padding: 28px 24px;

            min-height: 210px;

            display: flex;

            flex-direction: column;

            justify-content: space-between;

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease,
                border-color 0.35s ease;

        }


        .dashboard-card::before {

            content: "";

            position: absolute;

            width: 140px;

            height: 140px;

            border-radius: 50%;

            top: -70px;

            right: -70px;

            background:
                rgba(
                    102,
                    126,
                    234,
                    0.08
                );

            transition:
                transform 0.4s ease;

        }


        .dashboard-card:hover {

            transform:
                translateY(-8px);

            box-shadow:
                0 18px 40px
                rgba(
                    0,
                    0,
                    0,
                    0.12
                );

            border-color:
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .dashboard-card:hover::before {

            transform:
                scale(1.5);

        }


        /* =========================================
           CARD ICON
        ========================================= */

        .card-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            font-size: 25px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            box-shadow:
                0 8px 18px
                rgba(
                    102,
                    126,
                    234,
                    0.20
                );

            position: relative;

            z-index: 2;

        }


        /* =========================================
           CARD CONTENT
        ========================================= */

        .card-content {

            position: relative;

            z-index: 2;

        }


        .card-content h3 {

            margin:
                18px 0 7px;

            color: #222;

            font-size: 19px;

        }


        .card-content p {

            margin: 0;

            color: #888;

            font-size: 13px;

            line-height: 1.5;

        }


        /* =========================================
           CARD ARROW
        ========================================= */

        .card-arrow {

            position: absolute;

            right: 22px;

            bottom: 22px;

            color: #667eea;

            font-size: 20px;

            transition:
                transform 0.3s ease;

            z-index: 2;

        }


        .dashboard-card:hover
        .card-arrow {

            transform:
                translateX(5px);

        }


        /* =========================================
           LOGOUT CARD
        ========================================= */

        .logout-card {

            border-color:
                #ffe1de;

        }


        .logout-card
        .card-icon {

            background:
                linear-gradient(
                    135deg,
                    #ff6b6b,
                    #e74c3c
                );

            box-shadow:
                0 8px 18px
                rgba(
                    231,
                    76,
                    60,
                    0.20
                );

        }


        .logout-card:hover {

            border-color:
                rgba(
                    231,
                    76,
                    60,
                    0.30
                );

        }


        .logout-card
        .card-arrow {

            color: #e74c3c;

        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {

            position: relative;

            z-index: 2;

            text-align: center;

            margin-top: 25px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.85
                );

            font-size: 13px;

        }


        footer p {

            margin: 0;

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 800px) {

            body {

                padding: 20px 12px;

            }


            .container {

                padding: 30px 20px;

                border-radius: 17px;

            }


            .dashboard-grid {

                grid-template-columns:
                    1fr;

            }


            .dashboard-card {

                min-height: 180px;

            }


            h2 {

                font-size: 27px;

            }

        }


        @media (max-width: 450px) {

            .container {

                padding: 25px 15px;

            }


            .dashboard-icon {

                width: 65px;

                height: 65px;

                font-size: 27px;

            }


            h2 {

                font-size: 24px;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- HEADER -->

    <div class="dashboard-header">

        <div class="dashboard-icon">
            ⚙️
        </div>

        <h2>
            Admin Dashboard
        </h2>

        <p class="subtitle">
            Manage your ecommerce store from one place
        </p>

    </div>


    <!-- DASHBOARD CARDS -->

    <div class="dashboard-grid">


        <!-- ADD PRODUCT -->

        <a
            href="add_product.php"
            class="dashboard-card"
        >

            <div class="card-icon">
                ➕
            </div>

            <div class="card-content">

                <h3>
                    Add Product
                </h3>

                <p>
                    Add new products to your
                    ecommerce store.
                </p>

            </div>

            <div class="card-arrow">
                →
            </div>

        </a>


        <!-- MANAGE PRODUCTS -->

        <a
            href="manage_products.php"
            class="dashboard-card"
        >

            <div class="card-icon">
                📦
            </div>

            <div class="card-content">

                <h3>
                    Manage Products
                </h3>

                <p>
                    View, edit and delete
                    products from your store.
                </p>

            </div>

            <div class="card-arrow">
                →
            </div>

        </a>


        <!-- LOGOUT -->

        <a
            href="logout.php"
            class="dashboard-card logout-card"
        >

            <div class="card-icon">
                🚪
            </div>

            <div class="card-content">

                <h3>
                    Logout
                </h3>

                <p>
                    Securely sign out of
                    your administrator account.
                </p>

            </div>

            <div class="card-arrow">
                →
            </div>

        </a>


    </div>


</div>


<footer>

    <p>
        &copy;
        <?php echo date("Y"); ?>
        Ecommerce Admin Panel
    </p>

</footer>


</body>

</html>
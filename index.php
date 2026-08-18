<?php

session_start();

/* =========================================
   LOGOUT
========================================= */

if (isset($_POST['logout'])) {

    session_unset();
    session_destroy();

    header("Location: pages/login.php");
    exit();
}


/* =========================================
   CHECK LOGIN
========================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: pages/login.php");
    exit();

}


/* =========================================
   DATABASE
========================================= */

include 'includes/db.php';

$stmt = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);

$products =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Online Store</title>

    <style>

        /* =========================================
           RESET
        ========================================= */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {

            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #222;

            background:
                #f7f8fc;

        }


        /* =========================================
           HEADER
        ========================================= */

        header {

            position: sticky;

            top: 0;

            z-index: 1000;

            background:
                rgba(
                    17,
                    24,
                    39,
                    0.95
                );

            backdrop-filter:
                blur(15px);

            box-shadow:
                0 5px 25px
                rgba(
                    0,
                    0,
                    0,
                    0.12
                );

        }


        .header-container {

            width: 92%;

            max-width: 1250px;

            min-height: 75px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            gap: 30px;

        }


        /* =========================================
           LOGO
        ========================================= */

        .logo {

            display: flex;

            align-items: center;

            gap: 10px;

            color: white;

            text-decoration: none;

        }


        .logo-icon {

            width: 42px;

            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            font-size: 20px;

            box-shadow:
                0 6px 15px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .logo-text {

            font-size: 20px;

            font-weight: 800;

            letter-spacing:
                -0.5px;

        }


        /* =========================================
           SEARCH
        ========================================= */

        .search-box {

            flex: 1;

            max-width: 400px;

            position: relative;

        }


        .search-box input {

            width: 100%;

            height: 42px;

            padding:
                0 15px 0 40px;

            border: none;

            outline: none;

            border-radius: 10px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.12
                );

            color: white;

            font-size: 13px;

            transition:
                background 0.3s ease;

        }


        .search-box input::placeholder {

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.65
                );

        }


        .search-box input:focus {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.18
                );

        }


        .search-icon {

            position: absolute;

            left: 14px;

            top: 50%;

            transform:
                translateY(-50%);

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.7
                );

        }


        /* =========================================
           NAV
        ========================================= */

        nav {

            display: flex;

            align-items: center;

            gap: 5px;

        }


        nav a {

            position: relative;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.85
                );

            text-decoration: none;

            padding:
                10px 12px;

            border-radius: 9px;

            font-size: 13px;

            font-weight: 600;

            transition:
                all 0.3s ease;

        }


        nav a:hover {

            color: white;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.10
                );

        }


        /* =========================================
           CART
        ========================================= */

        .cart-link {

            display: flex;

            align-items: center;

            gap: 7px;

        }


        .cart-icon {

            width: 22px;

            height: 22px;

            transition:
                transform 0.3s ease;

        }


        .cart-link:hover
        .cart-icon {

            transform:
                scale(1.15);

        }


        /* =========================================
           LOGOUT
        ========================================= */

        .logout-button {

            border: none;

            padding:
                10px 15px;

            border-radius: 9px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #ff6b6b,
                    #e74c3c
                );

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .logout-button:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 7px 18px
                rgba(
                    231,
                    76,
                    60,
                    0.25
                );

        }


        /* =========================================
           HERO
        ========================================= */

        .hero {

            width: 92%;

            max-width: 1250px;

            margin:
                35px auto 0;

            min-height: 300px;

            padding:
                50px;

            border-radius: 24px;

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            color: white;

            box-shadow:
                0 20px 45px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .hero::before {

            content: "";

            position: absolute;

            width: 300px;

            height: 300px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.10
                );

            right: -80px;

            top: -100px;

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 200px;

            height: 200px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.08
                );

            right: 180px;

            bottom: -120px;

        }


        .hero-content {

            position: relative;

            z-index: 2;

            max-width: 650px;

        }


        .hero-badge {

            display: inline-block;

            padding:
                7px 12px;

            border-radius: 20px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.15
                );

            font-size: 11px;

            font-weight: 700;

            margin-bottom: 15px;

        }


        .hero h2 {

            margin: 0;

            font-size:
                clamp(
                    32px,
                    5vw,
                    50px
                );

            line-height: 1.1;

            letter-spacing:
                -1.5px;

        }


        .hero p {

            margin:
                18px 0 25px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.85
                );

            font-size: 15px;

            line-height: 1.6;

        }


        .hero-button {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding:
                13px 20px;

            border-radius: 10px;

            background: white;

            color: #667eea;

            text-decoration: none;

            font-size: 13px;

            font-weight: 700;

            transition:
                all 0.3s ease;

        }


        .hero-button:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 10px 25px
                rgba(
                    0,
                    0,
                    0,
                    0.15
                );

        }


        /* =========================================
           MAIN
        ========================================= */

        .main-container {

            width: 92%;

            max-width: 1250px;

            margin:
                45px auto;

        }


        /* =========================================
           SECTION HEADER
        ========================================= */

        .section-header {

            display: flex;

            align-items: end;

            justify-content:
                space-between;

            margin-bottom: 25px;

        }


        .section-header h2 {

            margin: 0;

            font-size: 28px;

            color: #222;

        }


        .section-header p {

            margin:
                6px 0 0;

            color: #888;

            font-size: 13px;

        }


        .product-count {

            color: #667eea;

            font-size: 13px;

            font-weight: 600;

        }


        /* =========================================
           PRODUCT GRID
        ========================================= */

        .product-list {

            display: grid;

            grid-template-columns:
                repeat(
                    4,
                    1fr
                );

            gap: 22px;

        }


        /* =========================================
           PRODUCT CARD
        ========================================= */

        .product {

            position: relative;

            background: white;

            border:
                1px solid #eee;

            border-radius: 17px;

            padding: 12px;

            overflow: hidden;

            transition:
                transform 0.35s ease,
                box-shadow 0.35s ease,
                border-color 0.35s ease;

        }


        .product:hover {

            transform:
                translateY(-8px);

            border-color:
                rgba(
                    102,
                    126,
                    234,
                    0.20
                );

            box-shadow:
                0 18px 40px
                rgba(
                    0,
                    0,
                    0,
                    0.11
                );

        }


        /* =========================================
           PRODUCT BADGE
        ========================================= */

        .product-badge {

            position: absolute;

            top: 22px;

            left: 22px;

            z-index: 5;

            padding:
                6px 9px;

            border-radius: 20px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #ff7675,
                    #fd79a8
                );

            font-size: 9px;

            font-weight: 800;

            letter-spacing:
                0.5px;

        }


        /* =========================================
           IMAGE
        ========================================= */

        .image-container {

            width: 100%;

            height: 230px;

            overflow: hidden;

            border-radius: 13px;

            background:
                #f5f5f7;

        }


        .product-image {

            width: 100%;

            height: 100%;

            object-fit: cover;

            display: block;

            transition:
                transform 0.5s ease;

        }


        .product:hover
        .product-image {

            transform:
                scale(1.06);

        }


        /* =========================================
           PRODUCT INFO
        ========================================= */

        .product-info {

            padding:
                15px 5px 5px;

        }


        .product h3 {

            margin:
                0 0 7px;

            color: #222;

            font-size: 16px;

            font-weight: 700;

        }


        .description {

            height: 40px;

            margin:
                0 0 12px;

            color: #888;

            font-size: 12px;

            line-height: 1.5;

            overflow: hidden;

        }


        .product-bottom {

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            gap: 10px;

        }


        .price {

            color: #667eea;

            font-size: 19px;

            font-weight: 800;

        }


        /* =========================================
           ADD TO CART
        ========================================= */

        .add-to-cart-button {

            border: none;

            padding:
                10px 13px;

            border-radius: 9px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            font-size: 11px;

            font-weight: 700;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .add-to-cart-button:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 7px 17px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .add-to-cart-button:active {

            transform:
                scale(0.97);

        }


        /* =========================================
           EMPTY PRODUCTS
        ========================================= */

        .empty-products {

            background: white;

            padding:
                70px 30px;

            border-radius: 18px;

            text-align: center;

            box-shadow:
                0 10px 30px
                rgba(
                    0,
                    0,
                    0,
                    0.06
                );

        }


        .empty-products-icon {

            font-size: 50px;

            margin-bottom: 15px;

        }


        .empty-products h3 {

            margin: 0 0 8px;

            font-size: 22px;

        }


        .empty-products p {

            margin: 0;

            color: #888;

        }


        /* =========================================
           FOOTER
        ========================================= */

        footer {

            margin-top: 70px;

            padding:
                35px 20px;

            background:
                #111827;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.65
                );

            text-align: center;

        }


        footer p {

            margin: 0;

            font-size: 12px;

        }


        /* =========================================
           TABLET
        ========================================= */

        @media (max-width: 1050px) {

            .product-list {

                grid-template-columns:
                    repeat(
                        3,
                        1fr
                    );

            }


            .search-box {

                display: none;

            }

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 750px) {

            .header-container {

                flex-wrap: wrap;

                padding:
                    12px 0;

                gap: 10px;

            }


            nav {

                width: 100%;

                justify-content:
                    center;

                flex-wrap: wrap;

            }


            .hero {

                padding:
                    35px 25px;

                min-height:
                    280px;

            }


            .product-list {

                grid-template-columns:
                    repeat(
                        2,
                        1fr
                    );

            }


            .section-header {

                align-items:
                    flex-start;

                flex-direction:
                    column;

                gap: 8px;

            }

        }


        /* =========================================
           SMALL MOBILE
        ========================================= */

        @media (max-width: 480px) {

            .header-container {

                width: 94%;

            }


            .logo-text {

                font-size: 17px;

            }


            nav a {

                padding:
                    8px 8px;

                font-size: 11px;

            }


            .hero {

                width: 94%;

                padding:
                    30px 20px;

                border-radius: 18px;

            }


            .hero h2 {

                font-size: 30px;

            }


            .main-container {

                width: 94%;

            }


            .product-list {

                grid-template-columns:
                    1fr;

            }


            .image-container {

                height: 250px;

            }

        }

    </style>

</head>


<body>


<!-- =========================================
     HEADER
========================================= -->

<header>

    <div class="header-container">


        <!-- LOGO -->

        <a
            href="index.php"
            class="logo"
        >

            <div class="logo-icon">
                🛍️
            </div>

            <span class="logo-text">
                Nexora
            </span>

        </a>


        <!-- SEARCH -->

        <div class="search-box">

            <span class="search-icon">
                🔍
            </span>

            <input
                type="text"
                id="searchInput"
                placeholder="Search products..."
            >

        </div>


        <!-- NAV -->

        <nav>

            <a href="index.php">
                Home
            </a>

            <a href="pages/cart.php"
               class="cart-link">

                <img
                    src="images/cart-icon.png"
                    alt="Cart"
                    class="cart-icon"
                >

                Cart

            </a>


            <form
                method="POST"
                style="display:inline;"
            >

                <button
                    type="submit"
                    name="logout"
                    class="logout-button"
                >
                    Logout
                </button>

            </form>

        </nav>


    </div>

</header>


<!-- =========================================
     HERO
========================================= -->

<section class="hero">

    <div class="hero-content">

        <span class="hero-badge">
            ✨ NEW COLLECTION
        </span>

        <h2>
            Find Something
            You'll Love.
        </h2>

        <p>
            Discover our latest products,
            carefully selected to bring style,
            quality and value to your everyday life.
        </p>

        <a
            href="#products"
            class="hero-button"
        >
            Explore Products →
        </a>

    </div>

</section>


<!-- =========================================
     PRODUCTS
========================================= -->

<div
    class="main-container"
    id="products"
>


    <div class="section-header">

        <div>

            <h2>
                Featured Products
            </h2>

            <p>
                Explore our latest collection
            </p>

        </div>

        <span class="product-count">

            <?= count($products); ?>
            Products

        </span>

    </div>


    <?php if (empty($products)): ?>


        <div class="empty-products">

            <div class="empty-products-icon">
                📦
            </div>

            <h3>
                No Products Available
            </h3>

            <p>
                Check back soon for new products.
            </p>

        </div>


    <?php else: ?>


        <div class="product-list">


            <?php foreach (
                $products as $index => $product
            ): ?>


                <div
                    class="product"
                    data-name="<?= htmlspecialchars(
                        strtolower(
                            $product['name']
                        )
                    ); ?>"
                >


                    <?php if ($index < 3): ?>

                        <span class="product-badge">
                            NEW
                        </span>

                    <?php endif; ?>


                    <!-- IMAGE -->

                    <div class="image-container">

                        <?php if (
                            !empty(
                                $product['image']
                            )
                        ): ?>

                            <img
                                src="images/<?= htmlspecialchars(
                                    $product['image']
                                ); ?>"
                                alt="<?= htmlspecialchars(
                                    $product['name']
                                ); ?>"
                                class="product-image"
                            >

                        <?php else: ?>

                            <div
                                style="
                                    width:100%;
                                    height:100%;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    font-size:50px;
                                "
                            >
                                📦
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- INFO -->

                    <div class="product-info">


                        <h3>

                            <?= htmlspecialchars(
                                $product['name']
                            ); ?>

                        </h3>


                        <p class="description">

                            <?= htmlspecialchars(
                                $product['description']
                            ); ?>

                        </p>


                        <div class="product-bottom">


                            <span class="price">

                                $<?= number_format(
                                    $product['price'],
                                    2
                                ); ?>

                            </span>


                            <form
                                method="POST"
                                action="pages/cart.php"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= $product['id']; ?>"
                                >


                                <button
                                    type="submit"
                                    name="add_to_cart"
                                    class="add-to-cart-button"
                                >
                                    🛒 Add to Cart
                                </button>

                            </form>


                        </div>


                    </div>


                </div>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</div>


<!-- =========================================
     FOOTER
========================================= -->

<footer>

    <p>

        &copy;
        <?= date('Y'); ?>

        My Store.

        All rights reserved.

    </p>

</footer>


<!-- =========================================
     SEARCH JAVASCRIPT
========================================= -->

<script>

    const searchInput =
        document.getElementById(
            'searchInput'
        );

    const products =
        document.querySelectorAll(
            '.product'
        );


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const search =
                    this.value
                        .toLowerCase()
                        .trim();


                products.forEach(
                    function (product) {

                        const name =
                            product.dataset.name;

                        if (
                            name.includes(
                                search
                            )
                        ) {

                            product.style.display =
                                '';

                        } else {

                            product.style.display =
                                'none';

                        }

                    }
                );

            }
        );

    }

</script>


</body>

</html>
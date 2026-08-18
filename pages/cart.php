<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

/* =========================================
   ADD TO CART
========================================= */

if (isset($_POST['add_to_cart'])) {

    $product_id = (int) $_POST['product_id'];

    $quantity = isset($_POST['quantity'])
        ? max(1, (int) $_POST['quantity'])
        : 1;

    $stmt = $conn->prepare(
        "SELECT * FROM cart
         WHERE user_id = ?
         AND product_id = ?"
    );

    $stmt->execute([
        $user_id,
        $product_id
    ]);

    $cart_item =
        $stmt->fetch(PDO::FETCH_ASSOC);


    if ($cart_item) {

        $new_quantity =
            $cart_item['quantity']
            + $quantity;

        $stmt = $conn->prepare(
            "UPDATE cart
             SET quantity = ?
             WHERE user_id = ?
             AND product_id = ?"
        );

        $stmt->execute([
            $new_quantity,
            $user_id,
            $product_id
        ]);

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO cart
             (user_id, product_id, quantity)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $user_id,
            $product_id,
            $quantity
        ]);
    }
}


/* =========================================
   REMOVE FROM CART
========================================= */

if (isset($_POST['remove_from_cart'])) {

    $product_id =
        (int) $_POST['product_id'];

    $stmt = $conn->prepare(
        "DELETE FROM cart
         WHERE user_id = ?
         AND product_id = ?"
    );

    $stmt->execute([
        $user_id,
        $product_id
    ]);
}


/* =========================================
   UPDATE QUANTITY
========================================= */

if (isset($_POST['update_quantity'])) {

    $product_id =
        (int) $_POST['product_id'];

    $quantity =
        max(1, (int) $_POST['quantity']);


    $stmt = $conn->prepare(
        "UPDATE cart
         SET quantity = ?
         WHERE user_id = ?
         AND product_id = ?"
    );

    $stmt->execute([
        $quantity,
        $user_id,
        $product_id
    ]);
}


/* =========================================
   FETCH CART
========================================= */

$stmt = $conn->prepare(
    "SELECT *
     FROM cart
     WHERE user_id = ?"
);

$stmt->execute([
    $user_id
]);

$cart_items =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Your Shopping Cart</title>


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

            top: -160px;

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

            max-width: 1150px;

            margin: 0 auto;

            position: relative;

            z-index: 2;

        }


        /* =========================================
           HEADER
        ========================================= */

        .cart-header {

            background: white;

            border-radius: 18px;

            padding: 25px 30px;

            margin-bottom: 20px;

            box-shadow:
                0 20px 45px
                rgba(
                    0,
                    0,
                    0,
                    0.16
                );

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

        }


        .cart-title {

            display: flex;

            align-items: center;

            gap: 15px;

        }


        .cart-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            color: white;

            font-size: 25px;

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
                    0.25
                );

        }


        .cart-header h2 {

            margin: 0;

            color: #222;

            font-size: 28px;

        }


        .cart-header p {

            margin: 5px 0 0;

            color: #888;

            font-size: 13px;

        }


        /* =========================================
           CART CONTENT
        ========================================= */

        .cart-layout {

            display: grid;

            grid-template-columns:
                1fr 320px;

            gap: 20px;

            align-items: start;

        }


        /* =========================================
           ITEMS
        ========================================= */

        .cart-items {

            background: white;

            padding: 20px;

            border-radius: 18px;

            box-shadow:
                0 20px 45px
                rgba(
                    0,
                    0,
                    0,
                    0.16
                );

        }


        /* =========================================
           CART ITEM
        ========================================= */

        .cart-item {

            display: grid;

            grid-template-columns:
                100px 1fr auto;

            align-items: center;

            gap: 20px;

            padding: 18px;

            margin-bottom: 15px;

            border:
                1px solid #eee;

            border-radius: 14px;

            transition:
                all 0.3s ease;

        }


        .cart-item:last-child {

            margin-bottom: 0;

        }


        .cart-item:hover {

            border-color:
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

            box-shadow:
                0 8px 25px
                rgba(
                    0,
                    0,
                    0,
                    0.06
                );

            transform:
                translateY(-2px);

        }


        /* =========================================
           PRODUCT IMAGE
        ========================================= */

        .item-image {

            width: 100px;

            height: 100px;

            object-fit: cover;

            border-radius: 12px;

            border:
                1px solid #eee;

            padding: 3px;

            transition:
                transform 0.3s ease;

        }


        .cart-item:hover
        .item-image {

            transform:
                scale(1.04);

        }


        /* =========================================
           ITEM DETAILS
        ========================================= */

        .item-name {

            font-size: 17px;

            font-weight: 700;

            color: #222;

            margin-bottom: 8px;

        }


        .item-price {

            color: #667eea;

            font-size: 15px;

            font-weight: 600;

        }


        .item-subtotal {

            color: #999;

            font-size: 12px;

            margin-top: 7px;

        }


        /* =========================================
           ACTIONS
        ========================================= */

        .item-actions {

            display: flex;

            flex-direction: column;

            align-items: flex-end;

            gap: 10px;

        }


        .quantity-form {

            display: flex;

            align-items: center;

            gap: 7px;

        }


        .quantity {

            width: 65px;

            height: 40px;

            padding: 5px;

            text-align: center;

            border:
                1px solid #ddd;

            border-radius: 8px;

            font-size: 14px;

            outline: none;

        }


        .quantity:focus {

            border-color: #667eea;

            box-shadow:
                0 0 0 3px
                rgba(
                    102,
                    126,
                    234,
                    0.10
                );

        }


        .update-btn {

            height: 40px;

            padding: 0 13px;

            border: none;

            border-radius: 8px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .update-btn:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 6px 15px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .remove-form {

            width: 100%;

            text-align: right;

        }


        .remove-btn {

            border: none;

            background: transparent;

            color: #e74c3c;

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            padding: 5px;

            transition:
                color 0.3s ease;

        }


        .remove-btn:hover {

            color: #c0392b;

            text-decoration:
                underline;

        }


        /* =========================================
           ORDER SUMMARY
        ========================================= */

        .order-summary {

            background: white;

            padding: 25px;

            border-radius: 18px;

            box-shadow:
                0 20px 45px
                rgba(
                    0,
                    0,
                    0,
                    0.16
                );

            position: sticky;

            top: 20px;

        }


        .order-summary h3 {

            margin:
                0 0 20px;

            color: #222;

            font-size: 20px;

        }


        .summary-row {

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            padding: 10px 0;

            color: #777;

            font-size: 14px;

        }


        .summary-row.total {

            margin-top: 10px;

            padding-top: 18px;

            border-top:
                1px solid #eee;

            color: #222;

            font-size: 20px;

            font-weight: 700;

        }


        .total-price {

            color: #667eea;

        }


        /* =========================================
           CHECKOUT BUTTON
        ========================================= */

        .checkout-btn {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 100%;

            height: 50px;

            margin-top: 20px;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            text-decoration: none;

            font-size: 15px;

            font-weight: 700;

            box-shadow:
                0 8px 20px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

            transition:
                all 0.3s ease;

        }


        .checkout-btn:hover {

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


        /* =========================================
           CONTINUE SHOPPING
        ========================================= */

        .continue-shopping {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 100%;

            height: 45px;

            margin-top: 10px;

            border-radius: 10px;

            color: #555;

            background: #f1f1f1;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition:
                all 0.3s ease;

        }


        .continue-shopping:hover {

            background: #e5e5e5;

            transform:
                translateY(-2px);

        }


        /* =========================================
           EMPTY CART
        ========================================= */

        .empty-cart {

            background: white;

            padding: 70px 30px;

            border-radius: 18px;

            text-align: center;

            box-shadow:
                0 20px 45px
                rgba(
                    0,
                    0,
                    0,
                    0.16
                );

        }


        .empty-icon {

            width: 85px;

            height: 85px;

            margin:
                0 auto 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #f1efff;

            color: #667eea;

            font-size: 38px;

        }


        .empty-cart h3 {

            margin:
                0 0 10px;

            color: #333;

            font-size: 22px;

        }


        .empty-cart p {

            margin:
                0 0 25px;

            color: #888;

            font-size: 14px;

        }


        .shop-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding:
                13px 25px;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition:
                all 0.3s ease;

        }


        .shop-btn:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 10px 20px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 850px) {

            body {

                padding:
                    20px 12px;

            }


            .cart-layout {

                grid-template-columns:
                    1fr;

            }


            .order-summary {

                position: static;

            }

        }


        @media (max-width: 600px) {

            .cart-header {

                padding: 20px;

            }


            .cart-header h2 {

                font-size: 23px;

            }


            .cart-item {

                grid-template-columns:
                    75px 1fr;

                gap: 15px;

            }


            .item-image {

                width: 75px;

                height: 75px;

            }


            .item-actions {

                grid-column:
                    1 / -1;

                align-items:
                    stretch;

            }


            .quantity-form {

                justify-content:
                    flex-start;

            }


            .remove-form {

                text-align:
                    left;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <?php if (empty($cart_items)): ?>


        <!-- =====================================
             EMPTY CART
        ====================================== -->

        <div class="empty-cart">

            <div class="empty-icon">
                🛒
            </div>

            <h3>
                Your Cart is Empty
            </h3>

            <p>
                Looks like you haven't added
                anything to your cart yet.
            </p>

            <a
                href="../index.php"
                class="shop-btn"
            >
                ← Continue Shopping
            </a>

        </div>


    <?php else: ?>


        <!-- =====================================
             CART HEADER
        ====================================== -->

        <div class="cart-header">

            <div class="cart-title">

                <div class="cart-icon">
                    🛒
                </div>

                <div>

                    <h2>
                        Your Cart
                    </h2>

                    <p>
                        Review your items before checkout
                    </p>

                </div>

            </div>

        </div>


        <!-- =====================================
             CART LAYOUT
        ====================================== -->

        <div class="cart-layout">


            <!-- CART ITEMS -->

            <div class="cart-items">


                <?php

                $product_ids =
                    array_column(
                        $cart_items,
                        'product_id'
                    );

                $placeholders =
                    implode(
                        ',',
                        array_fill(
                            0,
                            count($product_ids),
                            '?'
                        )
                    );


                $stmt = $conn->prepare(
                    "SELECT *
                     FROM products
                     WHERE id IN ($placeholders)"
                );

                $stmt->execute(
                    $product_ids
                );

                $products =
                    $stmt->fetchAll(
                        PDO::FETCH_ASSOC
                    );


                foreach ($products as $product):

                    $quantity = 0;


                    foreach (
                        $cart_items
                        as $cart_item
                    ) {

                        if (
                            $cart_item['product_id']
                            == $product['id']
                        ) {

                            $quantity =
                                (int)
                                $cart_item['quantity'];

                            break;

                        }

                    }


                    $subtotal =
                        $product['price']
                        * $quantity;

                    $total_cost +=
                        $subtotal;

                ?>


                    <div class="cart-item">


                        <!-- IMAGE -->

                        <img
                            src="../images/<?= htmlspecialchars(
                                $product['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $product['name']
                            ); ?>"
                            class="item-image"
                        >


                        <!-- DETAILS -->

                        <div class="item-details">

                            <div class="item-name">

                                <?= htmlspecialchars(
                                    $product['name']
                                ); ?>

                            </div>

                            <div class="item-price">

                                $<?= number_format(
                                    $product['price'],
                                    2
                                ); ?>

                                × <?= $quantity; ?>

                            </div>

                            <div class="item-subtotal">

                                Subtotal:
                                <strong>
                                    $<?= number_format(
                                        $subtotal,
                                        2
                                    ); ?>
                                </strong>

                            </div>

                        </div>


                        <!-- ACTIONS -->

                        <div class="item-actions">


                            <!-- UPDATE -->

                            <form
                                method="POST"
                                class="quantity-form"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= $product['id']; ?>"
                                >

                                <input
                                    type="number"
                                    name="quantity"
                                    value="<?= $quantity; ?>"
                                    class="quantity"
                                    min="1"
                                    required
                                >

                                <button
                                    type="submit"
                                    name="update_quantity"
                                    class="update-btn"
                                >
                                    Update
                                </button>

                            </form>


                            <!-- REMOVE -->

                            <form
                                method="POST"
                                class="remove-form"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= $product['id']; ?>"
                                >

                                <button
                                    type="submit"
                                    name="remove_from_cart"
                                    class="remove-btn"
                                >
                                    🗑 Remove
                                </button>

                            </form>


                        </div>


                    </div>


                <?php endforeach; ?>


            </div>


            <!-- =================================
                 ORDER SUMMARY
            ================================== -->

            <div class="order-summary">

                <h3>
                    Order Summary
                </h3>


                <div class="summary-row">

                    <span>
                        Items
                    </span>

                    <strong>
                        <?= count($cart_items); ?>
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Shipping
                    </span>

                    <span>
                        Free
                    </span>

                </div>


                <div class="summary-row total">

                    <span>
                        Total
                    </span>

                    <span class="total-price">

                        $<?= number_format(
                            $total_cost,
                            2
                        ); ?>

                    </span>

                </div>


                <a
                    href="checkout.php"
                    class="checkout-btn"
                >
                    Proceed to Checkout →
                </a>


                <a
                    href="../index.php"
                    class="continue-shopping"
                >
                    ← Continue Shopping
                </a>

            </div>


        </div>


    <?php endif; ?>


</div>


</body>

</html>
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

$total_cost = 0;
$cart_products = [];

/* =========================================
   FETCH CART + PRODUCT INFORMATION
========================================= */

$stmt = $conn->prepare("
    SELECT
        cart.product_id,
        cart.quantity,
        products.name,
        products.price,
        products.image
    FROM cart
    INNER JOIN products
        ON cart.product_id = products.id
    WHERE cart.user_id = ?
");

$stmt->execute([$user_id]);

$cart_products = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================
   CALCULATE TOTAL
========================================= */

foreach ($cart_products as $item) {

    $total_cost +=
        $item['price'] * $item['quantity'];

}


/* =========================================
   PLACE ORDER
========================================= */

if (isset($_POST['place_order'])) {

    if (empty($cart_products)) {

        header("Location: cart.php");
        exit();

    }


    $customer_name =
        trim($_POST['customer_name']);

    $email =
        trim($_POST['email']);

    $address =
        trim($_POST['address']);

    $city =
        trim($_POST['city']);

    $phone =
        trim($_POST['phone']);


    /* =========================================
       VALIDATION
    ========================================= */

    if (
        empty($customer_name) ||
        empty($email) ||
        empty($address) ||
        empty($city) ||
        empty($phone)
    ) {

        $error_message =
            "Please fill in all the fields.";

    } else {


        try {

            $conn->beginTransaction();


            /* =========================================
               CREATE ORDER

               IMPORTANT:
               Your orders table must contain:
               id
               user_id
               total_amount
               customer_name
               email
               address
               city
               phone
               created_at
            ========================================= */

            $stmt = $conn->prepare("
                INSERT INTO orders
                (
                    user_id,
                    total_amount,
                    customer_name,
                    email,
                    address,
                    city,
                    phone
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $user_id,
                $total_cost,
                $customer_name,
                $email,
                $address,
                $city,
                $phone
            ]);


            $order_id =
                $conn->lastInsertId();


            /* =========================================
               CREATE ORDER ITEMS
            ========================================= */

            $stmt = $conn->prepare("
                INSERT INTO order_items
                (
                    order_id,
                    product_id,
                    quantity,
                    price
                )
                VALUES (?, ?, ?, ?)
            ");


            foreach (
                $cart_products
                as $item
            ) {

                $stmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);

            }


            /* =========================================
               CLEAR CART
            ========================================= */

            $stmt = $conn->prepare("
                DELETE FROM cart
                WHERE user_id = ?
            ");

            $stmt->execute([
                $user_id
            ]);


            $conn->commit();


            /* =========================================
               SUCCESS
            ========================================= */

            header(
                "Location: order_success.php?order_id="
                . $order_id
            );

            exit();


        } catch (Exception $e) {

            $conn->rollBack();

            $error_message =
                "Unable to place your order. "
                . $e->getMessage();

        }

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

    <title>Checkout - Nexora</title>


    <style>

        * {
            box-sizing: border-box;
        }


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
                    #667eea,
                    #764ba2
                );

            padding: 40px 20px;

            color: #333;

        }


        .container {

            max-width: 1100px;

            margin: auto;

        }


        .checkout-header {

            background: white;

            padding: 25px 30px;

            border-radius: 18px;

            margin-bottom: 20px;

            box-shadow:
                0 20px 45px
                rgba(0,0,0,0.16);

        }


        .checkout-header h1 {

            margin: 0;

            color: #222;

            font-size: 28px;

        }


        .checkout-header p {

            margin:
                7px 0 0;

            color: #888;

            font-size: 13px;

        }


        .checkout-layout {

            display: grid;

            grid-template-columns:
                1fr 350px;

            gap: 20px;

            align-items: start;

        }


        .checkout-card {

            background: white;

            padding: 30px;

            border-radius: 18px;

            box-shadow:
                0 20px 45px
                rgba(0,0,0,0.16);

        }


        .checkout-card h2 {

            margin:
                0 0 25px;

            font-size: 20px;

            color: #222;

        }


        .form-group {

            margin-bottom: 18px;

        }


        label {

            display: block;

            margin-bottom: 7px;

            font-size: 13px;

            font-weight: 600;

            color: #333;

        }


        input,
        textarea {

            width: 100%;

            border:
                1px solid #ddd;

            border-radius: 9px;

            padding:
                13px;

            font-size: 14px;

            outline: none;

        }


        input {

            height: 48px;

        }


        textarea {

            min-height: 100px;

            resize: vertical;

        }


        input:focus,
        textarea:focus {

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


        .error {

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 9px;

            background: #fff1f0;

            color: #d63031;

            border:
                1px solid #ffd6d2;

            font-size: 13px;

        }


        .order-summary {

            background: white;

            padding: 25px;

            border-radius: 18px;

            box-shadow:
                0 20px 45px
                rgba(0,0,0,0.16);

            position: sticky;

            top: 20px;

        }


        .order-summary h2 {

            margin:
                0 0 20px;

            font-size: 20px;

        }


        .order-item {

            display: flex;

            gap: 12px;

            padding:
                12px 0;

            border-bottom:
                1px solid #eee;

        }


        .order-item img {

            width: 60px;

            height: 60px;

            object-fit: cover;

            border-radius: 9px;

        }


        .order-item-details {

            flex: 1;

        }


        .order-item-name {

            font-size: 13px;

            font-weight: 700;

        }


        .order-item-quantity {

            margin-top: 5px;

            color: #888;

            font-size: 11px;

        }


        .order-item-price {

            font-size: 13px;

            font-weight: 700;

            color: #667eea;

        }


        .total {

            display: flex;

            justify-content:
                space-between;

            padding-top: 20px;

            margin-top: 10px;

            border-top:
                1px solid #ddd;

            font-size: 20px;

            font-weight: 700;

        }


        .total span:last-child {

            color: #667eea;

        }


        .place-order {

            width: 100%;

            height: 52px;

            margin-top: 20px;

            border: none;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            font-size: 14px;

            font-weight: 700;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .place-order:hover {

            transform:
                translateY(-3px);

            box-shadow:
                0 10px 25px
                rgba(
                    102,
                    126,
                    234,
                    0.3
                );

        }


        .back-cart {

            display: block;

            margin-top: 12px;

            text-align: center;

            color: #777;

            text-decoration: none;

            font-size: 12px;

        }


        .back-cart:hover {

            color: #667eea;

        }


        @media (max-width: 800px) {

            body {

                padding:
                    20px 12px;

            }


            .checkout-layout {

                grid-template-columns: 1fr;

            }


            .order-summary {

                position: static;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- HEADER -->

    <div class="checkout-header">

        <h1>
            Secure Checkout
        </h1>

        <p>
            Complete your details to place your order.
        </p>

    </div>


    <div class="checkout-layout">


        <!-- =================================
             CUSTOMER INFORMATION
        ================================== -->

        <div class="checkout-card">

            <h2>
                Customer Information
            </h2>


            <?php if (
                !empty($error_message)
            ): ?>

                <div class="error">

                    ⚠
                    <?= htmlspecialchars(
                        $error_message
                    ); ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
            >


                <div class="form-group">

                    <label>
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="customer_name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Phone Number
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Enter your phone number"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Address
                    </label>

                    <textarea
                        name="address"
                        placeholder="Enter your delivery address"
                        required
                    ></textarea>

                </div>


                <div class="form-group">

                    <label>
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        placeholder="Enter your city"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="place_order"
                    class="place-order"
                >
                    🛍️ Place Order
                </button>


            </form>


            <a
                href="cart.php"
                class="back-cart"
            >
                ← Back to Cart
            </a>


        </div>


        <!-- =================================
             ORDER SUMMARY
        ================================== -->

        <div class="order-summary">

            <h2>
                Your Order
            </h2>


            <?php foreach (
                $cart_products
                as $item
            ): ?>


                <div class="order-item">


                    <?php if (
                        !empty(
                            $item['image']
                        )
                    ): ?>

                        <img
                            src="../images/<?= htmlspecialchars(
                                $item['image']
                            ); ?>"
                            alt="<?= htmlspecialchars(
                                $item['name']
                            ); ?>"
                        >

                    <?php else: ?>

                        <div
                            style="
                                width:60px;
                                height:60px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:#f1f1f1;
                                border-radius:9px;
                                font-size:25px;
                            "
                        >
                            📦
                        </div>

                    <?php endif; ?>


                    <div class="order-item-details">

                        <div class="order-item-name">

                            <?= htmlspecialchars(
                                $item['name']
                            ); ?>

                        </div>


                        <div class="order-item-quantity">

                            Quantity:
                            <?= $item['quantity']; ?>

                        </div>


                    </div>


                    <div class="order-item-price">

                        $<?= number_format(
                            $item['price']
                            * $item['quantity'],
                            2
                        ); ?>

                    </div>


                </div>


            <?php endforeach; ?>


            <div class="total">

                <span>
                    Total
                </span>

                <span>
                    $<?= number_format(
                        $total_cost,
                        2
                    ); ?>
                </span>

            </div>


        </div>


    </div>


</div>


</body>

</html>
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

$order_id = isset($_GET['order_id'])
    ? (int) $_GET['order_id']
    : 0;

if ($order_id <= 0) {
    header("Location: ../index.php");
    exit();
}


/* =========================================
   GET ORDER
========================================= */

$stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE id = ?
    AND user_id = ?
");

$stmt->execute([
    $order_id,
    $user_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: ../index.php");
    exit();
}


/* =========================================
   GET ORDER ITEMS
========================================= */

$stmt = $conn->prepare("
    SELECT
        order_items.*,
        products.name,
        products.image
    FROM order_items
    INNER JOIN products
        ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");

$stmt->execute([$order_id]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order Successful</title>


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

        }


        .container {

            max-width: 750px;

            margin: auto;

        }


        .success-card {

            background: white;

            border-radius: 20px;

            padding: 40px;

            text-align: center;

            box-shadow:
                0 25px 60px
                rgba(0,0,0,0.20);

        }


        .success-icon {

            width: 80px;

            height: 80px;

            margin:
                0 auto 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #e8fff3;

            color: #20bf6b;

            font-size: 40px;

        }


        h1 {

            margin: 0;

            color: #222;

            font-size: 30px;

        }


        .message {

            color: #777;

            font-size: 14px;

            margin:
                10px 0 25px;

        }


        .order-number {

            display: inline-block;

            padding:
                10px 18px;

            border-radius: 10px;

            background: #f1efff;

            color: #667eea;

            font-weight: 700;

            font-size: 14px;

        }


        .items {

            margin-top: 30px;

            text-align: left;

            border-top:
                1px solid #eee;

            padding-top: 20px;

        }


        .item {

            display: flex;

            align-items: center;

            gap: 15px;

            padding: 12px 0;

            border-bottom:
                1px solid #eee;

        }


        .item img {

            width: 65px;

            height: 65px;

            object-fit: cover;

            border-radius: 10px;

        }


        .item-info {

            flex: 1;

        }


        .item-name {

            font-size: 14px;

            font-weight: 700;

            color: #222;

        }


        .item-quantity {

            margin-top: 5px;

            color: #888;

            font-size: 12px;

        }


        .item-price {

            color: #667eea;

            font-weight: 700;

        }


        .total {

            display: flex;

            justify-content:
                space-between;

            margin-top: 20px;

            padding-top: 18px;

            border-top:
                2px solid #eee;

            font-size: 20px;

            font-weight: 700;

        }


        .total span:last-child {

            color: #667eea;

        }


        .buttons {

            display: flex;

            gap: 12px;

            margin-top: 30px;

        }


        .button {

            flex: 1;

            padding: 14px;

            border-radius: 10px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 700;

            transition:
                all 0.3s ease;

        }


        .primary {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

        }


        .secondary {

            color: #555;

            background: #eee;

        }


        .button:hover {

            transform:
                translateY(-3px);

        }


        @media (max-width: 550px) {

            body {

                padding:
                    20px 12px;

            }


            .success-card {

                padding:
                    30px 20px;

            }


            h1 {

                font-size: 25px;

            }


            .buttons {

                flex-direction: column;

            }

        }

    </style>

</head>


<body>


<div class="container">

    <div class="success-card">


        <div class="success-icon">
            ✓
        </div>


        <h1>
            Order Placed Successfully!
        </h1>


        <p class="message">

            Thank you for your purchase.
            Your order has been received.

        </p>


        <div class="order-number">

            Order #<?= $order_id; ?>

        </div>


        <div class="items">


            <?php foreach ($items as $item): ?>

                <div class="item">


                    <img
                        src="../images/<?= htmlspecialchars(
                            $item['image']
                        ); ?>"
                        alt="<?= htmlspecialchars(
                            $item['name']
                        ); ?>"
                    >


                    <div class="item-info">

                        <div class="item-name">

                            <?= htmlspecialchars(
                                $item['name']
                            ); ?>

                        </div>

                        <div class="item-quantity">

                            Quantity:
                            <?= $item['quantity']; ?>

                        </div>

                    </div>


                    <div class="item-price">

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
                        $order['total_amount'],
                        2
                    ); ?>

                </span>

            </div>


        </div>


        <div class="buttons">

            <a
                href="../index.php"
                class="button primary"
            >
                Continue Shopping
            </a>

            <a
                href="my_orders.php"
                class="button secondary"
            >
                My Orders
            </a>

        </div>


    </div>

</div>


</body>

</html>
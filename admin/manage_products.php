<?php

include '../includes/db.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Products</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            min-height: 100vh;
            padding: 40px 20px;
            color: #333;
        }

        /* =========================================
           MAIN CONTAINER
        ========================================= */

        .container {
            width: 100%;
            max-width: 1250px;
            margin: auto;

            background: #fff;

            padding: 35px;

            border-radius: 18px;

            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.2);
        }

        /* =========================================
           TOP HEADER
        ========================================= */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 20px;

            margin-bottom: 30px;
        }

        .page-title h2 {
            margin: 0;

            color: #222;

            font-size: 30px;
            font-weight: 700;
        }

        .page-title p {
            margin: 8px 0 0;

            color: #888;

            font-size: 14px;
        }

        /* =========================================
           ADD PRODUCT BUTTON
        ========================================= */

        .add-product-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 13px 20px;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            color: white;

            text-decoration: none;

            border-radius: 10px;

            font-size: 15px;
            font-weight: 600;

            box-shadow:
                0 8px 20px rgba(
                    102,
                    126,
                    234,
                    0.25
                );

            transition: all 0.3s ease;
        }

        .add-product-btn:hover {
            transform: translateY(-3px);

            box-shadow:
                0 12px 28px rgba(
                    102,
                    126,
                    234,
                    0.35
                );
        }

        .plus {
            font-size: 20px;
            line-height: 1;
        }

        /* =========================================
           STATS
        ========================================= */

        .stats {
            display: flex;
            gap: 15px;

            margin-bottom: 25px;
        }

        .stat-card {
            display: flex;
            align-items: center;

            gap: 14px;

            padding: 15px 20px;

            background: #f8f7ff;

            border: 1px solid #ece9ff;

            border-radius: 12px;

            min-width: 180px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            color: white;

            font-size: 20px;
        }

        .stat-info span {
            display: block;

            color: #888;

            font-size: 12px;

            margin-bottom: 3px;
        }

        .stat-info strong {
            color: #222;

            font-size: 20px;
        }

        /* =========================================
           SEARCH
        ========================================= */

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 15px;

            margin-bottom: 20px;
        }

        .search-box {
            position: relative;

            width: 350px;
            max-width: 100%;
        }

        .search-box input {
            width: 100%;

            padding: 13px 15px 13px 42px;

            border: 1px solid #ddd;

            border-radius: 10px;

            font-size: 14px;

            outline: none;

            transition: 0.3s;
        }

        .search-box input:focus {
            border-color: #667eea;

            box-shadow:
                0 0 0 3px rgba(
                    102,
                    126,
                    234,
                    0.12
                );
        }

        .search-icon {
            position: absolute;

            left: 15px;
            top: 50%;

            transform: translateY(-50%);

            color: #999;

            font-size: 16px;
        }

        /* =========================================
           TABLE
        ========================================= */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            border: 1px solid #eee;

            border-radius: 12px;
        }

        table {
            width: 100%;

            min-width: 900px;

            border-collapse: collapse;
        }

        th {
            padding: 16px;

            text-align: left;

            color: white;

            font-size: 13px;

            font-weight: 600;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );
        }

        td {
            padding: 15px;

            border-bottom: 1px solid #eee;

            vertical-align: middle;

            font-size: 14px;
        }

        tbody tr {
            background: white;

            transition: all 0.25s ease;
        }

        tbody tr:hover {
            background: #f8f7ff;

            box-shadow:
                0 5px 15px rgba(
                    102,
                    126,
                    234,
                    0.08
                );
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================================
           ID
        ========================================= */

        .product-id {
            color: #999;

            font-weight: 600;
        }

        /* =========================================
           PRODUCT NAME
        ========================================= */

        .product-name {
            font-weight: 600;

            color: #222;
        }

        /* =========================================
           PRICE
        ========================================= */

        .product-price {
            color: #667eea;

            font-size: 16px;

            font-weight: 700;

            white-space: nowrap;
        }

        /* =========================================
           DESCRIPTION
        ========================================= */

        .description {
            max-width: 280px;

            color: #777;

            line-height: 1.5;
        }

        /* =========================================
           IMAGE
        ========================================= */

        .product-image {
            width: 65px;
            height: 65px;

            object-fit: cover;

            border-radius: 10px;

            border: 2px solid #eee;

            padding: 2px;

            background: white;

            transition: 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.15);

            border-color: #667eea;

            box-shadow:
                0 8px 20px rgba(
                    102,
                    126,
                    234,
                    0.2
                );
        }

        .no-image {
            width: 65px;
            height: 65px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f1f1f1;

            color: #aaa;

            border-radius: 10px;

            font-size: 11px;
        }

        /* =========================================
           ACTIONS
        ========================================= */

        .actions {
            white-space: nowrap;
        }

        .actions a {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            margin: 3px;

            padding: 9px 13px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition: all 0.3s ease;
        }

        /* EDIT */

        .edit-btn {
            color: #667eea;

            background: #f0efff;

            border: 1px solid #dcd8ff;
        }

        .edit-btn:hover {
            color: white;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            border-color: transparent;

            transform: translateY(-2px);

            box-shadow:
                0 6px 15px rgba(
                    102,
                    126,
                    234,
                    0.25
                );
        }

        /* DELETE */

        .delete-btn {
            color: #e74c3c;

            background: #fff0ef;

            border: 1px solid #ffd8d4;
        }

        .delete-btn:hover {
            color: white;

            background: linear-gradient(
                135deg,
                #ff6b6b,
                #e74c3c
            );

            border-color: transparent;

            transform: translateY(-2px);

            box-shadow:
                0 6px 15px rgba(
                    231,
                    76,
                    60,
                    0.25
                );
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty {
            text-align: center;

            padding: 60px 20px;

            color: #888;
        }

        .empty-icon {
            font-size: 50px;

            margin-bottom: 15px;
        }

        .empty h3 {
            margin: 0 0 8px;

            color: #333;
        }

        .empty p {
            margin: 0 0 20px;
        }

        /* =========================================
           BACK BUTTON
        ========================================= */

        .bottom-actions {
            display: flex;

            justify-content: center;

            gap: 12px;

            margin-top: 30px;
        }

        .btn-back {
            display: inline-block;

            padding: 13px 25px;

            background: #f1f1f1;

            color: #444;

            border-radius: 10px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #e5e5e5;

            transform: translateY(-2px);
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 768px) {

            body {
                padding: 20px 10px;
            }

            .container {
                padding: 20px 15px;

                border-radius: 14px;
            }

            .page-header {
                flex-direction: column;

                align-items: stretch;

                text-align: center;
            }

            .page-title h2 {
                font-size: 25px;
            }

            .add-product-btn {
                width: 100%;
            }

            .stats {
                justify-content: center;
            }

            .stat-card {
                flex: 1;
            }

            .toolbar {
                flex-direction: column;

                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }

            .bottom-actions {
                flex-direction: column;
            }

            .btn-back {
                text-align: center;
            }
        }

    </style>

</head>

<body>

<div class="container">

    <!-- PAGE HEADER -->

    <div class="page-header">

        <div class="page-title">

            <h2>Manage Products</h2>

            <p>
                Add, edit and manage your store products
            </p>

        </div>

        <a
            href="add_product.php"
            class="add-product-btn"
        >
            <span class="plus">+</span>
            Add Product
        </a>

    </div>


    <!-- PRODUCT STATS -->

    <div class="stats">

        <div class="stat-card">

            <div class="stat-icon">
                📦
            </div>

            <div class="stat-info">

                <span>
                    Total Products
                </span>

                <strong>
                    <?= count($products); ?>
                </strong>

            </div>

        </div>

    </div>


    <!-- SEARCH -->

    <div class="toolbar">

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

    </div>


    <?php if (count($products) > 0): ?>

        <div class="table-wrapper">

            <table id="productTable">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Product</th>

                        <th>Price</th>

                        <th>Description</th>

                        <th>Image</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($products as $product): ?>

                        <tr>

                            <td class="product-id">

                                #<?= $product['id']; ?>

                            </td>


                            <td class="product-name">

                                <?= htmlspecialchars(
                                    $product['name']
                                ); ?>

                            </td>


                            <td class="product-price">

                                $<?= number_format(
                                    $product['price'],
                                    2
                                ); ?>

                            </td>


                            <td class="description">

                                <?= htmlspecialchars(
                                    $product['description']
                                ); ?>

                            </td>


                            <td>

                                <?php if (!empty($product['image'])): ?>

                                    <img
                                        class="product-image"
                                        src="../images/<?= htmlspecialchars(
                                            $product['image']
                                        ); ?>"
                                        alt="Product Image"
                                    >

                                <?php else: ?>

                                    <div class="no-image">
                                        No Image
                                    </div>

                                <?php endif; ?>

                            </td>


                            <td class="actions">

                                <a
                                    href="edit_product.php?id=<?= $product['id']; ?>"
                                    class="edit-btn"
                                >
                                    ✏️ Edit
                                </a>

                                <a
                                    href="delete_product.php?id=<?= $product['id']; ?>"
                                    class="delete-btn"
                                    onclick="return confirm(
                                        'Are you sure you want to delete this product?'
                                    );"
                                >
                                    🗑 Delete
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty">

            <div class="empty-icon">
                📦
            </div>

            <h3>
                No Products Yet
            </h3>

            <p>
                Start building your store by adding your first product.
            </p>

            <a
                href="add_product.php"
                class="add-product-btn"
            >
                + Add Your First Product
            </a>

        </div>

    <?php endif; ?>


    <!-- BOTTOM -->

    <div class="bottom-actions">

        <a
            href="dashboard.php"
            class="btn-back"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>


<!-- SEARCH JAVASCRIPT -->

<script>

    const searchInput =
        document.getElementById('searchInput');

    const table =
        document.getElementById('productTable');

    if (searchInput && table) {

        searchInput.addEventListener(
            'keyup',
            function () {

                const search =
                    this.value.toLowerCase();

                const rows =
                    table
                    .getElementsByTagName('tbody')[0]
                    .getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {

                    const rowText =
                        rows[i]
                        .textContent
                        .toLowerCase();

                    if (rowText.includes(search)) {

                        rows[i].style.display = '';

                    } else {

                        rows[i].style.display = 'none';

                    }

                }

            }
        );

    }

</script>

</body>

</html>
<?php

include '../includes/db.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* Check product ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$id = (int) $_GET['id'];

/* Get existing product */
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

/* Update product */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    if ($name === '' || $price === '') {
        $error = "Product name and price are required.";
    } else {

        /* Keep existing image */
        $imageName = $product['image'];

        /* If a new image was uploaded */
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $uploadDir = '../images/';

            $originalName = $_FILES['image']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                $error = "Invalid image type.";
            } else {

                $newImageName = uniqid('product_', true) . '.' . $extension;

                if (move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $uploadDir . $newImageName
                )) {

                    /* Delete old image */
                    if (!empty($product['image'])) {

                        $oldImage = $uploadDir . $product['image'];

                        if (file_exists($oldImage)) {
                            unlink($oldImage);
                        }
                    }

                    $imageName = $newImageName;
                }
            }
        }

        if (!isset($error)) {

            $update = $conn->prepare("
                UPDATE products
                SET name = ?, price = ?, description = ?, image = ?
                WHERE id = ?
            ");

            $update->execute([
                $name,
                $price,
                $description,
                $imageName,
                $id
            ]);

            header("Location: manage_products.php");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow:
                0 20px 50px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
            color: #222;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input,
        textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #667eea;
            box-shadow:
                0 0 0 3px rgba(102,126,234,0.15);
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .current-image {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .current-image img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #ddd;
        }

        .error {
            background: #ffe5e5;
            color: #d63031;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }

        .btn-save {
            background: #667eea;
            color: white;
        }

        .btn-save:hover {
            background: #5568d8;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #eee;
            color: #333;
        }

        .btn-cancel:hover {
            background: #ddd;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>✏️ Edit Product</h2>

    <?php if (isset($error)): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST"
          enctype="multipart/form-data">

        <div class="form-group">

            <label for="name">
                Product Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($product['name']) ?>"
                required
            >

        </div>


        <div class="form-group">

            <label for="price">
                Price
            </label>

            <input
                type="number"
                id="price"
                name="price"
                step="0.01"
                min="0"
                value="<?= htmlspecialchars($product['price']) ?>"
                required
            >

        </div>


        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
            ><?= htmlspecialchars($product['description']) ?></textarea>

        </div>


        <div class="form-group">

            <label>
                Current Image
            </label>

            <?php if (!empty($product['image'])): ?>

                <div class="current-image">

                    <img
                        src="../images/<?= htmlspecialchars($product['image']) ?>"
                        alt="Current Product"
                    >

                </div>

            <?php else: ?>

                <p>No image uploaded.</p>

            <?php endif; ?>

        </div>


        <div class="form-group">

            <label for="image">
                Change Image
            </label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
            >

        </div>


        <div class="buttons">

            <a
                href="manage_products.php"
                class="btn btn-cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-save"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

</body>

</html>
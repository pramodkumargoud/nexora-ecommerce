<?php

include '../includes/db.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if (isset($_POST['add_product'])) {

    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    /* ==============================
       VALIDATE IMAGE
    ============================== */

    if (
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] !== UPLOAD_ERR_OK
    ) {

        $errorMessage = "Please select a product image.";

    } else {

        $image = $_FILES['image'];

        $extension = strtolower(
            pathinfo(
                $image['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp'
        ];

        if (!in_array($extension, $allowedExtensions)) {

            $errorMessage =
                "Only JPG, JPEG, PNG, GIF and WEBP images are allowed.";

        } else {

            /* Generate unique image name */

            $imageName =
                uniqid('product_', true)
                . '.'
                . $extension;

            $uploadPath =
                "../images/"
                . $imageName;


            /* ==============================
               UPLOAD IMAGE
            ============================== */

            if (
                move_uploaded_file(
                    $image['tmp_name'],
                    $uploadPath
                )
            ) {

                /* ==============================
                   INSERT PRODUCT
                ============================== */

                $stmt = $conn->prepare(
                    "INSERT INTO products
                    (name, price, description, image)
                    VALUES (?, ?, ?, ?)"
                );

                $stmt->execute([
                    $name,
                    $price,
                    $description,
                    $imageName
                ]);

                $successMessage =
                    "Product added successfully!";


                /* Clear form */

                $name = '';
                $price = '';
                $description = '';

            } else {

                $errorMessage =
                    "Failed to upload the image.";

            }
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

    <title>Add Product</title>


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
                    #667eea,
                    #764ba2
                );

            padding: 40px 20px;

            color: #333;

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
           MAIN CONTAINER
        ========================================= */

        .container {

            width: 100%;

            max-width: 650px;

            margin: 0 auto;

            background: #fff;

            padding: 40px;

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
                formAppear
                0.6s ease;

        }


        @keyframes formAppear {

            from {

                opacity: 0;

                transform:
                    translateY(25px)
                    scale(0.98);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }


        /* =========================================
           HEADER
        ========================================= */

        .form-header {

            text-align: center;

            margin-bottom: 30px;

        }


        .form-icon {

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


        h2 {

            margin: 0;

            color: #222;

            font-size: 30px;

            font-weight: 700;

        }


        .subtitle {

            margin:
                10px 0 0;

            color: #888;

            font-size: 14px;

        }


        /* =========================================
           MESSAGES
        ========================================= */

        .message {

            padding: 13px 15px;

            border-radius: 10px;

            margin-bottom: 22px;

            text-align: center;

            font-size: 14px;

        }


        .success-message {

            background: #ecfdf5;

            color: #059669;

            border:
                1px solid #a7f3d0;

        }


        .error-message {

            background: #fff1f0;

            color: #d63031;

            border:
                1px solid #ffd6d2;

        }


        /* =========================================
           FORM
        ========================================= */

        form {

            display: flex;

            flex-direction: column;

        }


        .form-group {

            margin-bottom: 20px;

        }


        label {

            display: block;

            margin-bottom: 8px;

            color: #333;

            font-size: 14px;

            font-weight: 600;

        }


        /* =========================================
           INPUT
        ========================================= */

        input[type="text"],
        input[type="number"],
        textarea {

            width: 100%;

            padding:
                13px 15px;

            border:
                1px solid #ddd;

            border-radius: 10px;

            background: #fff;

            color: #333;

            font-size: 14px;

            outline: none;

            transition:
                border-color 0.3s ease,
                box-shadow 0.3s ease;

        }


        input[type="text"],
        input[type="number"] {

            height: 50px;

        }


        input::placeholder,
        textarea::placeholder {

            color: #aaa;

        }


        input[type="text"]:focus,
        input[type="number"]:focus,
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


        /* =========================================
           TEXTAREA
        ========================================= */

        textarea {

            min-height: 130px;

            resize: vertical;

            line-height: 1.5;

        }


        /* =========================================
           FILE UPLOAD
        ========================================= */

        .file-upload {

            position: relative;

            width: 100%;

        }


        input[type="file"] {

            width: 100%;

            padding: 12px;

            border:
                1px dashed #c9c9c9;

            border-radius: 10px;

            background: #fafafa;

            color: #666;

            font-size: 13px;

            cursor: pointer;

            transition:
                border-color 0.3s ease,
                background 0.3s ease;

        }


        input[type="file"]:hover {

            border-color: #667eea;

            background: #f8f7ff;

        }


        .file-help {

            display: block;

            margin-top: 7px;

            color: #999;

            font-size: 11px;

        }


        /* =========================================
           IMAGE PREVIEW
        ========================================= */

        .image-preview {

            display: none;

            margin-top: 15px;

            padding: 10px;

            border:
                1px solid #eee;

            border-radius: 12px;

            background: #fafafa;

            text-align: center;

        }


        .image-preview img {

            max-width: 180px;

            max-height: 180px;

            object-fit: cover;

            border-radius: 10px;

        }


        /* =========================================
           BUTTONS
        ========================================= */

        .buttons {

            display: flex;

            gap: 12px;

            margin-top: 10px;

        }


        .btn {

            flex: 1;

            height: 52px;

            display: flex;

            align-items: center;

            justify-content: center;

            border: none;

            border-radius: 10px;

            text-decoration: none;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;

        }


        /* ADD */

        .btn-add {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            box-shadow:
                0 8px 20px
                rgba(
                    102,
                    126,
                    234,
                    0.25
                );

        }


        .btn-add:hover {

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


        /* CANCEL */

        .btn-cancel {

            color: #444;

            background: #eee;

        }


        .btn-cancel:hover {

            background: #ddd;

            transform:
                translateY(-2px);

        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 600px) {

            body {

                padding:
                    20px 12px;

            }


            .container {

                padding:
                    30px 20px;

                border-radius: 16px;

            }


            h2 {

                font-size: 26px;

            }


            .form-icon {

                width: 60px;

                height: 60px;

                font-size: 26px;

            }


            .buttons {

                flex-direction: column;

            }


            .btn {

                width: 100%;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- HEADER -->

    <div class="form-header">

        <div class="form-icon">
            ➕
        </div>

        <h2>
            Add Product
        </h2>

        <p class="subtitle">
            Add a new product to your ecommerce store
        </p>

    </div>


    <!-- SUCCESS -->

    <?php if (!empty($successMessage)): ?>

        <div class="message success-message">

            ✓
            <?= htmlspecialchars(
                $successMessage
            ); ?>

        </div>

    <?php endif; ?>


    <!-- ERROR -->

    <?php if (!empty($errorMessage)): ?>

        <div class="message error-message">

            ⚠
            <?= htmlspecialchars(
                $errorMessage
            ); ?>

        </div>

    <?php endif; ?>


    <!-- FORM -->

    <form
        method="POST"
        enctype="multipart/form-data"
    >


        <!-- PRODUCT NAME -->

        <div class="form-group">

            <label for="name">
                Product Name
            </label>

            <input
                type="text"
                name="name"
                id="name"
                placeholder="Enter product name"
                value="<?= htmlspecialchars(
                    $name ?? ''
                ); ?>"
                required
            >

        </div>


        <!-- PRICE -->

        <div class="form-group">

            <label for="price">
                Price
            </label>

            <input
                type="number"
                step="0.01"
                min="0"
                name="price"
                id="price"
                placeholder="Enter product price"
                value="<?= htmlspecialchars(
                    $price ?? ''
                ); ?>"
                required
            >

        </div>


        <!-- DESCRIPTION -->

        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                name="description"
                id="description"
                placeholder="Write a description for your product..."
                required
            ><?= htmlspecialchars(
                $description ?? ''
            ); ?></textarea>

        </div>


        <!-- IMAGE -->

        <div class="form-group">

            <label for="image">
                Product Image
            </label>

            <div class="file-upload">

                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                    required
                >

            </div>

            <span class="file-help">
                JPG, JPEG, PNG, GIF or WEBP
            </span>


            <!-- IMAGE PREVIEW -->

            <div
                class="image-preview"
                id="imagePreview"
            >

                <img
                    id="previewImage"
                    src=""
                    alt="Image Preview"
                >

            </div>

        </div>


        <!-- BUTTONS -->

        <div class="buttons">

            <a
                href="manage_products.php"
                class="btn btn-cancel"
            >
                ← Cancel
            </a>

            <button
                type="submit"
                name="add_product"
                class="btn btn-add"
            >
                ➕ Add Product
            </button>

        </div>


    </form>


</div>


<!-- IMAGE PREVIEW SCRIPT -->

<script>

    const imageInput =
        document.getElementById(
            'image'
        );

    const imagePreview =
        document.getElementById(
            'imagePreview'
        );

    const previewImage =
        document.getElementById(
            'previewImage'
        );


    imageInput.addEventListener(
        'change',
        function () {

            const file =
                this.files[0];

            if (file) {

                const reader =
                    new FileReader();

                reader.onload =
                    function (event) {

                        previewImage.src =
                            event.target.result;

                        imagePreview.style.display =
                            'block';

                    };

                reader.readAsDataURL(file);

            } else {

                imagePreview.style.display =
                    'none';

                previewImage.src = '';

            }

        }
    );

</script>


</body>

</html>
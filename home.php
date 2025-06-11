<?php
session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Sample products - 10 items now
$products = [
    1 => ['name' => 'Wireless Headphones', 'price' => 99.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/0310bd0e-157f-48ca-b00e-b44e607c3389.png'],
    2 => ['name' => 'Smart Watch', 'price' => 149.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/491fb9b5-3d02-4413-b6ce-49c5a70eaa0f.png'],
    3 => ['name' => 'Portable Speaker', 'price' => 79.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a1ed7002-18fd-4b1a-b7b3-cd3ce6e0bbc7.png'],
    4 => ['name' => 'Bluetooth Keyboard', 'price' => 49.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/70ed36ab-04f0-4ede-a505-2d3e0470a913.png'],
    5 => ['name' => 'Gaming Mouse', 'price' => 39.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/b86719b4-763c-4acb-8b9a-568cd0d9a821.png'],
    6 => ['name' => 'HD Webcam', 'price' => 59.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/ccae270b-cb2c-4b7c-883b-678af5ecd71c.png'],
    7 => ['name' => 'Laptop Stand', 'price' => 29.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/7764f262-3b98-4dfb-b2b0-9d2b8c6e6948.png'],
    8 => ['name' => 'External Hard Drive', 'price' => 89.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/ac8b55f8-eebe-4d36-a7c6-ea9c50f1182e.png'],
    9 => ['name' => 'Smartphone Tripod', 'price' => 19.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/70a598f7-60f7-4ab7-a6f1-5bf9bce45b12.png'],
    10 => ['name' => 'Noise Cancelling Earbuds', 'price' => 129.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/85e2b791-8372-42b3-888d-eb51a5a39e02.png'],
];

// Initialize cart if not present
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding product to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    if (isset($products[$id])) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
        header('Location: home.php');
        exit;
    }
}

// Count total items in cart
$cartCount = array_sum($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ElectroBay - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            margin: 0;
            color: #374151;
        }
        header {
            background: #fff;
            padding: 16px 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }
        header h1 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            color: #111827;
        }
        nav a {
            margin-left: 24px;
            font-weight: 600;
            font-size: 16px;
            color: #2563eb;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        nav a .material-icons {
            font-size: 20px;
        }
        .btn-logout {
            background: none;
            border: none;
            color: #dc2626;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .btn-logout:hover {
            color: #b91c1c;
        }
        main {
            max-width: 1200px;
            margin: 48px auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
        }
        .product-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.3s ease;
        }
        .product-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            max-height: 180px;
            margin-bottom: 16px;
        }
        .product-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }
        .product-price {
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 16px;
            font-size: 18px;
        }
        .btn {
            background-color: #2563eb;
            color: #fff;
            border: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            gap: 8px;
            align-items: center;
        }
        .btn:hover {
            background-color: #1e40af;
        }
        @media (max-width: 640px) {
            header {
                padding: 12px 16px;
            }
            main {
                margin: 32px 16px;
                gap: 24px;
            }
            .product-price {
                font-size: 16px;
            }
            .product-name {
                font-size: 18px;
            }
            .btn {
                font-size: 14px;
                padding: 10px 14px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>ElectroBay</h1>
    <nav>
        <a href="cart.php" aria-label="View Cart">
            <span class="material-icons" aria-hidden="true">shopping_cart</span> Cart
            <?php if ($cartCount > 0): ?>
                <sup style="color:#dc2626;font-weight:bold;margin-left:4px;"><?=$cartCount?></sup>
            <?php endif; ?>
        </a>
        <form method="POST" action="logout.php" style="display:inline;">
            <button type="submit" class="btn-logout" aria-label="Logout">Logout</button>
        </form>
    </nav>
</header>
<main>
    <?php foreach ($products as $id => $product) : ?>
        <div class="product-card">
            <img src="<?=htmlspecialchars($product['image'])?>" alt="<?=htmlspecialchars($product['name'])?>" class="product-image" onerror="this.onerror=null;this.src='https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4bf38b78-1d49-44bb-9c16-cc51f9ac60dd.png';" />
            <div class="product-name"><?=htmlspecialchars($product['name'])?></div>
            <div class="product-price">$<?=number_format($product['price'], 2)?></div>
            <form method="POST" action="home.php">
                <input type="hidden" name="product_id" value="<?= $id ?>" />
                <button type="submit" class="btn" aria-label="Add <?=htmlspecialchars($product['name'])?> to cart">
                    <span class="material-icons" aria-hidden="true">add_shopping_cart</span> Add to Cart
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</main>
</body>
</html>


